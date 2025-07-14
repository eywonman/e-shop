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
        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)->with('guitar')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Calculate total price
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->guitar->price * $item->quantity;
        }

        DB::beginTransaction();

        try {
            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending', // or any status you want
            ]);

            // Create Order Items and adjust stock
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->guitar->stock) {
                    // Not enough stock, rollback & return error
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Not enough stock for ' . $item->guitar->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'guitar_id' => $item->guitar->id,
                    'quantity' => $item->quantity,
                    'price' => $item->guitar->price,
                ]);

                // Decrease stock
                $item->guitar->decrement('stock', $item->quantity);
            }

            // Clear user's cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
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
