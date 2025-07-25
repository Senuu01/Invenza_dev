<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
                   ->where('status', 'active')
                   ->with('items.product')
                   ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.dashboard')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Billing Information
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_country' => 'required|string|max:255',
            
            // Shipping Information
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            
            // Payment Information
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'card_number' => 'required_if:payment_method,credit_card|string|max:20',
            'card_expiry' => 'required_if:payment_method,credit_card|string|max:7',
            'card_cvv' => 'required_if:payment_method,credit_card|string|max:4',
            'card_holder' => 'required_if:payment_method,credit_card|string|max:255',
            
            // Additional Information
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Cart::where('user_id', Auth::id())
                   ->where('status', 'active')
                   ->with('items.product')
                   ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.dashboard')->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->quantity) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cart->total;
            $tax = $subtotal * 0.08; // 8% tax
            $shipping_cost = $subtotal > 100 ? 0 : 10; // Free shipping over $100
            $total = $subtotal + $tax + $shipping_cost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                
                // Billing Information
                'billing_first_name' => $request->billing_first_name,
                'billing_last_name' => $request->billing_last_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_zip' => $request->billing_zip,
                'billing_country' => $request->billing_country,
                
                // Shipping Information
                'shipping_first_name' => $request->shipping_first_name,
                'shipping_last_name' => $request->shipping_last_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_country' => $request->shipping_country,
                
                // Financial Information
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'notes' => $request->notes,
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

                // Update product stock
                $item->product->decrement('quantity', $item->quantity);
            }

            // Process payment (simulated)
            $paymentSuccess = $this->processPayment($request, $total);
            
            if ($paymentSuccess) {
                $order->update(['payment_status' => 'paid']);
            } else {
                $order->update(['payment_status' => 'failed']);
                throw new \Exception('Payment processing failed.');
            }

            // Clear cart
            $cart->update(['status' => 'converted']);
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order processing failed. Please try again.');
        }
    }

    private function processPayment(Request $request, $amount)
    {
        // Simulate payment processing
        // In a real application, you would integrate with Stripe, PayPal, etc.
        
        $paymentMethod = $request->payment_method;
        
        switch ($paymentMethod) {
            case 'credit_card':
                // Simulate credit card processing
                return rand(1, 10) > 1; // 90% success rate
                
            case 'paypal':
                // Simulate PayPal processing
                return rand(1, 10) > 2; // 80% success rate
                
            case 'bank_transfer':
                // Simulate bank transfer
                return rand(1, 10) > 3; // 70% success rate
                
            default:
                return false;
        }
    }
}
