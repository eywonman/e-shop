<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Orders') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6">
        @forelse ($orders as $order)
            <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow mb-6">

                {{-- Order Header --}}
                <div class="mb-4">
                    <h3 class="text-xl font-semibold">Order #{{ $order->id }}</h3>
                    <p>Status: 
                        <span class="font-medium {{ $order->status === 'cancelled' ? 'text-red-600' : 'text-green-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>

                {{-- Items List --}}
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 mb-6">
                    @foreach ($order->items as $item)
                        <li class="py-4 flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->guitar->image_url ?? 'https://via.placeholder.com/60' }}"
                                     alt="{{ $item->guitar->name }}"
                                     class="w-16 h-16 object-cover rounded shadow" />
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->guitar->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->guitar->brand }}</div>
                                </div>
                            </div>
                            <div class="text-right text-sm text-gray-700 dark:text-gray-300">
                                Qty: {{ $item->quantity }} <br>
                                Price: ₱{{ number_format($item->guitar->price, 2) }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Footer: Total & Cancel --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-t pt-4">
                    <div class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
                        Order Total: ₱{{ number_format($order->total_price, 2) }}
                    </div>

                    <div>
                        @if ($order->isCancellable())
                            <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                    Cancel Order
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 italic">Order cannot be cancelled</span>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <p class="text-center text-gray-600 dark:text-gray-400">You have no orders yet.</p>
        @endforelse
    </div>
</x-app-layout>
