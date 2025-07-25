<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Request $request)
    {
        try {
            $cart = Cart::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('items.product')
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'error' => 'Cart is empty'
                ], 400);
            }

            // Calculate totals
            $subtotal = $cart->total;
            $tax = $subtotal * 0.08; // 8% tax
            $shipping = $subtotal > 100 ? 0 : 10; // Free shipping over $100
            $total = $subtotal + $tax + $shipping;

            // Create line items for Stripe
            $lineItems = [];
            foreach ($cart->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->name,
                            'description' => $item->product->description ?? 'Product from Invenza',
                        ],
                        'unit_amount' => (int)($item->price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add tax and shipping as separate line items
            if ($tax > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Tax (8%)',
                        ],
                        'unit_amount' => (int)($tax * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            if ($shipping > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Shipping',
                        ],
                        'unit_amount' => (int)($shipping * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Create Stripe checkout session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                    'cart_id' => $cart->id,
                    'user_id' => Auth::id(),
                ],
                'customer_email' => Auth::user()->email,
                'billing_address_collection' => 'required',
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA', 'GB', 'AU'],
                ],
            ]);

            return response()->json([
                'session_id' => $session->id,
                'url' => $session->url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create checkout session: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                return redirect()->route('cart.index')->with('error', 'Invalid session');
            }

            $session = Session::retrieve($sessionId);
            
            if ($session->payment_status !== 'paid') {
                return redirect()->route('cart.index')->with('error', 'Payment not completed');
            }

            // Get cart from metadata
            $cartId = $session->metadata->cart_id;
            $cart = Cart::find($cartId);

            if (!$cart) {
                return redirect()->route('cart.index')->with('error', 'Cart not found');
            }

            // Create order
            DB::beginTransaction();
            
            try {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'payment_method' => 'stripe',
                    'subtotal' => $cart->total,
                    'tax' => $cart->total * 0.08,
                    'shipping_cost' => $cart->total > 100 ? 0 : 10,
                    'total' => $cart->total + ($cart->total * 0.08) + ($cart->total > 100 ? 0 : 10),
                    'stripe_session_id' => $sessionId,
                    'stripe_payment_intent_id' => $session->payment_intent,
                    
                    // Billing information from Stripe
                    'billing_first_name' => $session->customer_details->name ?? Auth::user()->name,
                    'billing_last_name' => '',
                    'billing_email' => $session->customer_details->email ?? Auth::user()->email,
                    'billing_phone' => $session->customer_details->phone ?? '',
                    'billing_address' => $session->customer_details->address->line1 ?? '',
                    'billing_city' => $session->customer_details->address->city ?? '',
                    'billing_state' => $session->customer_details->address->state ?? '',
                    'billing_zip' => $session->customer_details->address->postal_code ?? '',
                    'billing_country' => $session->customer_details->address->country ?? '',
                    
                    // Shipping information from Stripe
                    'shipping_first_name' => $session->shipping->name ?? Auth::user()->name,
                    'shipping_last_name' => '',
                    'shipping_email' => $session->customer_details->email ?? Auth::user()->email,
                    'shipping_phone' => $session->customer_details->phone ?? '',
                    'shipping_address' => $session->shipping->address->line1 ?? '',
                    'shipping_city' => $session->shipping->address->city ?? '',
                    'shipping_state' => $session->shipping->address->state ?? '',
                    'shipping_zip' => $session->shipping->address->postal_code ?? '',
                    'shipping_country' => $session->shipping->address->country ?? '',
                ]);

                // Create order items
                foreach ($cart->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                    ]);

                    // Decrease product stock
                    $item->product->decrement('quantity', $item->quantity);
                }

                // Clear cart
                $cart->update(['status' => 'converted']);
                $cart->items()->delete();

                DB::commit();

                return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('cart.index')->with('error', 'Payment was cancelled');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentFailed($paymentIntent);
                break;
            case 'payment_intent.canceled':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentCanceled($paymentIntent);
                break;
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $this->handleInvoicePaymentSucceeded($invoice);
                break;
            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $this->handleInvoicePaymentFailed($invoice);
                break;
            default:
                // Log unexpected event type but don't return error
                \Log::info('Unhandled Stripe webhook event', ['type' => $event->type]);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        // This is handled in the success method, but we can add additional logic here
        \Log::info('Checkout session completed', [
            'session_id' => $session->id,
            'customer_email' => $session->customer_details->email ?? 'N/A',
            'amount_total' => $session->amount_total,
            'payment_status' => $session->payment_status
        ]);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Update order payment status if needed
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['payment_status' => 'paid']);
            \Log::info('Payment succeeded for order', [
                'order_id' => $order->id, 
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount
            ]);
        }
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        // Update order payment status if needed
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['payment_status' => 'failed']);
            \Log::info('Payment failed for order', ['order_id' => $order->id, 'payment_intent_id' => $paymentIntent->id]);
        }
    }

    private function handlePaymentIntentCanceled($paymentIntent)
    {
        // Update order payment status if needed
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            \Log::info('Payment canceled for order', ['order_id' => $order->id, 'payment_intent_id' => $paymentIntent->id]);
        }
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        // Handle subscription payment success
        \Log::info('Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer,
            'amount' => $invoice->amount_paid
        ]);
        
        // You can add subscription logic here in the future
        // For now, just log the event
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        // Handle subscription payment failure
        \Log::info('Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer,
            'attempt_count' => $invoice->attempt_count
        ]);
        
        // You can add subscription failure logic here in the future
        // For now, just log the event
    }
}
