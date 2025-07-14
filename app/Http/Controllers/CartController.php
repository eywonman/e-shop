<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('guitar')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);

        if ($request->action === 'increment') {
            if ($cart->quantity < $cart->guitar->stock) {
                $cart->increment('quantity');
            }
        } elseif ($request->action === 'decrement') {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            }
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->findOrFail($id)->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}

