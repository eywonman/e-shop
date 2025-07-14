<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>A&A Guitar Shop</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans">
    <x-guest-layout class="bg-gray-900 text-gray-100 font-sans min-h-screen flex flex-col justify-between">

    <!-- 🧭 Navbar -->
    <nav class="bg-gray-800 shadow p-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-yellow-400">🎸 A&A Guitar Shop</div>
        <div>
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-yellow-300 hover:text-yellow-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-yellow-300 hover:text-yellow-500">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-yellow-300 hover:text-yellow-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Carousel -->
    <section class="relative overflow-hidden bg-black">
        <div x-data="carousel()" class="relative w-full h-[600px] sm:h-[600px]">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="current === index" x-transition class="absolute inset-0">
                    <img :src="'/images/' + slide" class="w-full h-full object-cover" alt="Guitar image">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <h2 class="text-white text-3xl sm:text-4xl font-bold text-center">
                            🔥 Summer Sale — Up to 30% Off!
                        </h2>
                    </div>
                </div>
            </template>

            <!-- Dots -->
            <div class="absolute inset-x-0 bottom-0 flex justify-center space-x-2 pb-4 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="current = index"
                            class="w-3 h-3 rounded-full"
                            :class="current === index ? 'bg-white' : 'bg-gray-600'"></button>
                </template>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="flex-grow py-16 text-center bg-gray-900 text-gray-100">
        <h1 class="text-4xl sm:text-5xl font-bold text-yellow-400 mb-4">Welcome to A&A Guitar Shop</h1>
        <p class="text-lg text-gray-300 mb-8">Explore the best guitars at affordable prices.</p>
        <a href="{{ route('login') }}" class="bg-yellow-400 text-gray-900 font-semibold px-6 py-3 rounded hover:bg-yellow-300 transition">Shop Now</a>

        <section id="products" class="py-16">
            <h2 class="text-3xl font-bold text-center mb-10 text-white">🎸 Featured Guitars</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 px-4">
                @foreach ([
                    ['name' => 'Fender Stratocaster', 'price' => '₱7,000', 'image' => 'strat.jpg'],
                    ['name' => 'Gibson Les Paul', 'price' => '₱7,500', 'image' => 'lespaul.jpg'],
                    ['name' => 'Yamaha Acoustic', 'price' => '₱6,300', 'image' => 'yamaha.jpg'],
                ] as $product)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                        <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-52 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-yellow-300 mb-2">{{ $product['name'] }}</h3>
                            <p class="text-gray-300 mb-4">{{ $product['price'] }}</p>
                            <a href="#" class="inline-block bg-yellow-400 text-gray-900 px-4 py-2 rounded hover:bg-yellow-300 transition">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 text-center p-4 shadow-inner">
        &copy; {{ date('Y') }} A&A Guitar Shop. All rights reserved.
    </footer>

    <!-- Alpine.js for Carousel -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', () => ({
                current: 0,
                slides: ['strat.jpg', 'lespaul.jpg', 'yamaha.jpg'],
                init() {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.slides.length;
                    }, 5000)
                }
            }))
        })
    </script>

</x-guest-layout>


    <!-- 🧠 Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
