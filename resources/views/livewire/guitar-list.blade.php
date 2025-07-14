<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($guitars as $guitar)
        <div class="border rounded p-4 shadow hover:shadow-lg flex flex-col items-center text-center bg-white dark:bg-gray-800">
            {{-- Guitar image --}}
            <img src="{{ $guitar->image_url ?? 'https://via.placeholder.com/150' }}"
                 alt="{{ $guitar->name }}"
                 class="w-60 h-48 object-cover rounded shadow mb-4" />

            {{-- Guitar info --}}
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guitar->name }}</h3>
            <p class="text-gray-600 dark:text-gray-300">{{ $guitar->brand }}</p>
            <p class="text-blue-600 font-bold">₱{{ number_format($guitar->price, 2) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Stock: {{ $guitar->stock }}</p>

            {{-- Quantity Controls --}}
            <div class="flex items-center mb-3">
                <button wire:click="decrement({{ $guitar->id }})"
                        class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded-l">
                    –
                </button>
                <input type="text"
                       wire:model="quantities.{{ $guitar->id }}"
                       class="w-12 text-center border-t border-b text-sm"
                       readonly />
                <button wire:click="increment({{ $guitar->id }})"
                        class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded-r">
                    +
                </button>
            </div>

            {{-- Add to cart button --}}
            <button wire:click="addToCart({{ $guitar->id }})"
                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition duration-200">
                Add to Cart
            </button>
        </div>
    @endforeach
</div>
