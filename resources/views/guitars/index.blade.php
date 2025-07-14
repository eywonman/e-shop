<x-app-layout>
    <div class="flex">
        {{-- Sidebar --}}
        <div class="w-64 bg-white dark:bg-gray-900 h-screen px-4 py-6 border-r">
            <nav class="space-y-4">
                <a href="{{ route('guitars.index') }}"
                   class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 font-medium">
                    🎸 Guitars
                </a>
                <a href="{{ route('orders.index') }}"
                   class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 font-medium">
                    📦 Orders
                </a>
                <a href="{{ route('cart.index') }}"
                    class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 font-medium">
                    🛒 View Cart
                </a>
            </nav>
        </div>

        {{-- Main content --}}
        <div class="flex-1 p-6 space-y-6">
            <h3 class="text-2xl font-semibold mb-2 text-gray-900 dark:text-white">Welcome to A&A Guitar Store!</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Browse all available guitars below.</p>

            {{-- Livewire Guitar List --}}
            <livewire:guitar-list />
        </div>
    </div>
</x-app-layout>
