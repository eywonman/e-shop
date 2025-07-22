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
            'house_number' => 'required|string|max:50',
            'street_name' => 'required|string|max:100',
            'barangay' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('guitar')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        do {
            $orderNumber = 'ORD-' . strtoupper(\Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        // 🔧 Concatenate full address
        $fullAddress = "{$request->house_number}, {$request->street_name}, Brgy. {$request->barangay}, {$request->city}, {$request->province}";

        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($cartItems as $item) {
                if ($item->guitar->stock < $item->quantity) {
                    DB::rollBack();
                    return back()->with('error', "Not enough stock for '{$item->guitar->name}'. Only {$item->guitar->stock} left.");
                }

                $total += $item->guitar->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_price' => $total,
                'status' => 'pending',
                'address' => $fullAddress,
                'payment_method' => 'cod',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'guitar_id' => $item->guitar_id,
                    'quantity' => $item->quantity,
                    'price' => $item->guitar->price,
                ]);

                $item->guitar->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed. Please try again.');
        }
    }


    public function cancel($id)
    {
        $order = Order::with('items.guitar')->findOrFail($id);

        if (! $order->isCancellable()) {
            return redirect()->back()->with('error', 'Order cannot be cancelled.');
        }

        // Begin DB transaction to ensure stock and order update are consistent
        DB::beginTransaction();

        try {
            // Restore the stock
            foreach ($order->items as $item) {
                if ($item->guitar) {
                    $item->guitar->increment('stock', $item->quantity);
                }
            }

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancellable' => false,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Order cancelled and stock restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

}
