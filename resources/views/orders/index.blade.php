<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Orders') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6">
        @forelse ($orders as $order)
            <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow mb-6 relative">

                {{-- Order Info --}}
                <h3 class="text-xl font-semibold mb-2">Order #{{ $order->id }}</h3>
                <p class="mb-4">Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>

                {{-- Order items list with images --}}
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 mb-12">
                    @foreach ($order->items as $item)
                        <li class="py-2 flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->guitar->image_url ?? 'https://via.placeholder.com/60' }}"
                                     alt="{{ $item->guitar->name }}"
                                     class="w-16 h-16 object-cover rounded shadow" />
                                <div>
                                    <div class="font-semibold">{{ $item->guitar->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->guitar->brand }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                Quantity: {{ $item->quantity }} <br />
                                Price: ₱{{ number_format($item->guitar->price, 2) }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Cancel Button at lower right --}}
                <div class="absolute bottom-4 right-4">
                    @if ($order->isCancellable())
                        <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-200">
                                Cancel Order
                            </button>
                        </form>
                    @else
                        <span class="text-gray-500 italic">Order cannot be cancelled</span>
                    @endif
                </div>

            </div>
        @empty
            <p class="text-center text-gray-600 dark:text-gray-400">You have no orders yet.</p>
        @endforelse
    </div>
</x-app-layout>
