<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.category');
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity
        ]);

        $cart = $this->getOrCreateCart();
        $quantity = $request->quantity;

        // Check if product is already in cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            
            if ($newQuantity > $product->quantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough stock available.'
                    ]);
                }
                return back()->with('error', 'Not enough stock available.');
            }
            
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        // Reload cart to get updated data
        $cart->load('items.product.category');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart' => [
                    'items' => $cart->items,
                    'total' => $cart->total,
                    'item_count' => $cart->item_count
                ]
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Ensure user owns this cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->quantity
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        // Reload cart to get updated totals
        $cart = $cartItem->cart;
        $cart->load('items.product.category');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_total' => $cart->total,
            'item_total' => $cartItem->total,
            'item_count' => $cart->item_count
        ]);
    }

    public function remove(CartItem $cartItem)
    {
        // Ensure user owns this cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            return back()->with('error', 'Unauthorized access.');
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        // Reload cart to get updated totals
        $cart->load('items.product.category');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully!',
                'cart_total' => $cart->total,
                'item_count' => $cart->item_count
            ]);
        }

        return back()->with('success', 'Item removed from cart successfully!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!'
            ]);
        }

        return back()->with('success', 'Cart cleared successfully!');
    }

    public function getCartCount()
    {
        $cart = $this->getOrCreateCart();
        return response()->json(['count' => $cart->item_count]);
    }

    public function getCartItems()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.category');
        
        return response()->json([
            'items' => $cart->items,
            'total' => $cart->total,
            'item_count' => $cart->item_count
        ]);
    }

    private function getOrCreateCart()
    {
        $cart = Cart::where('user_id', Auth::id())
                   ->where('status', 'active')
                   ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'status' => 'active'
            ]);
        }

        return $cart;
    }
}
