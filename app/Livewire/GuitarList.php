<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Guitar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuitarList extends Component
{
    public $quantities = [];

    public function mount()
    {
        $this->quantities = Guitar::all()->pluck(fn () => 1, 'id')->toArray(); // default qty = 1
    }

    public function increment($guitarId)
    {
        $guitar = Guitar::findOrFail($guitarId);
        if (($this->quantities[$guitarId] ?? 1) < $guitar->stock) {
            $this->quantities[$guitarId]++;
        }
    }

    public function decrement($guitarId)
    {
        if (($this->quantities[$guitarId] ?? 1) > 1) {
            $this->quantities[$guitarId]--;
        }
    }

    public function addToCart($guitarId)
    {
        $guitar = Guitar::findOrFail($guitarId);
        $user = Auth::user();
        $quantity = $this->quantities[$guitarId] ?? 1;

        // ❌ Prevent adding item with 0 stock
        if ($guitar->stock < 1) {
            $this->js(<<<JS
                Swal.fire({
                    icon: 'error',
                    title: 'Out of Stock',
                    text: 'Sorry, this guitar is currently unavailable.',
                });
            JS);
            return;
        }

        $cartItem = Cart::where('user_id', $user->id)
                        ->where('guitar_id', $guitarId)
                        ->first();

        // 🧠 Stock validation based on cart quantity
        if ($cartItem) {
            $totalQuantity = $cartItem->quantity + $quantity;

            if ($totalQuantity > $guitar->stock) {
                $this->js(<<<JS
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock Limit Reached',
                        text: 'You\'ve already added the max available quantity.',
                    });
                JS);
                return;
            }

            $cartItem->increment('quantity', $quantity);
        } else {
            if ($quantity > $guitar->stock) {
                $this->js(<<<JS
                    Swal.fire({
                        icon: 'warning',
                        title: 'Not Enough Stock',
                        text: 'Requested quantity exceeds available stock.',
                    });
                JS);
                return;
            }

            Cart::create([
                'user_id' => $user->id,
                'guitar_id' => $guitarId,
                'quantity' => $quantity,
            ]);
        }

        $this->dispatch('cartUpdated');

        $this->js(<<<JS
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Guitar added to cart!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        JS);
    }

    public function render()
    {
        $guitars = Guitar::all();
        return view('livewire.guitar-list', compact('guitars'));
    }
}
