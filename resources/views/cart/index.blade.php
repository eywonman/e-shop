<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6">
        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if ($cartItems->count() > 0)
            <div class="overflow-x-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Guitar</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $subtotal = $item->guitar->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="px-4 py-2 flex items-center space-x-3">
                                    <img src="{{ $item->guitar->image_url }}" class="w-16 h-16 object-cover rounded" alt="{{ $item->guitar->name }}">
                                    <div>
                                        <div class="font-semibold">{{ $item->guitar->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->guitar->brand }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">₱{{ number_format($item->guitar->price, 2) }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="action" value="decrement"
                                                class="px-2 py-1 bg-gray-300 rounded hover:bg-gray-400">–</button>
                                        <span>{{ $item->quantity }}</span>
                                        <button type="submit" name="action" value="increment"
                                                class="px-2 py-1 bg-gray-300 rounded hover:bg-gray-400"
                                                {{ $item->quantity >= $item->guitar->stock ? 'disabled' : '' }}>+</button>
                                    </form>
                                </td>
                                <td class="px-4 py-2">₱{{ number_format($subtotal, 2) }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-50 dark:bg-gray-900">
                            <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                            <td class="px-4 py-2">₱{{ number_format($total, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 flex justify-end space-x-4">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Clear Cart</button>
                    </form>
                    <form method="POST" action="{{ route('checkout') }}">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Checkout
                        </button>
                    </form>

                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-500 dark:text-gray-300">
                Your cart is empty.
            </div>
        @endif
    </div>
</x-app-layout>
