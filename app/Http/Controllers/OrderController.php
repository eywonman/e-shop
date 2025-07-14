<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.guitar')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            // No need to validate payment_method since it's fixed to COD
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('guitar')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate total price
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->guitar->price * $item->quantity;
        }

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'status' => 'pending',
            'address' => $request->address,
            'payment_method' => 'cod',
        ]);

        // Create Order Items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'guitar_id' => $item->guitar_id,
                'quantity' => $item->quantity,
                'price' => $item->guitar->price,
            ]);
        }

        // Clear Cart
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if (! $order->isCancellable()) {
            return redirect()->back()->with('error', 'Order cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancellable' => false,
        ]);

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
}
