<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-300">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{--  --}}
  @vite('resources/css/app.css')
  {{-- mengatur profile dropdown --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>Vending Machine</title>
</head>
<body class="h-full text-gray-200">

  <!-- Navbar -->
  <nav class="bg-gray-800/70 backdrop-blur-md border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Left side -->
        <div class="flex items-center space-x-4">
          <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Logo" class="size-8">
          <span class="font-semibold text-white text-lg">Vending Machine</span>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-6">
          <!-- Nav links -->
          <a href="/prepayment" class="text-gray-300 hover:text-white text-sm font-medium">Home</a>
          <a href="/prepayment" class="text-gray-300 hover:text-white text-sm font-medium">Snacks</a>
          <a href="/prepayment" class="text-gray-300 hover:text-white text-sm font-medium">Drinks</a>

          <!-- Profile dropdown -->
          <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <img src="https://t4.ftcdn.net/jpg/11/66/06/77/360_F_1166067709_2SooAuPWXp20XkGev7oOT7nuK1VThCsN.jpg" class="size-8 rounded-full" alt="Avatar">
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.outside="open = false" 
              x-transition
              class="absolute right-0 z-20 mt-2 w-48 rounded-md bg-gray-800 shadow-lg ring-1 ring-white/10">
              <a href="/prepayment" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">Your Profile</a>
              <a href="/prepayment" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">Settings</a>
              <a href="/prepayment" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">Sign Out</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="bg-gray-800 border-y border-gray-700">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-white">All Products</h1>
    </div>
  </header>

  <!-- Main content -->
  <main class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      
      <!-- Product Card -->
      @foreach ($products as $product)
        <div class="bg-gray-400 rounded-xl shadow p-4 hover:scale-110 transition-transform">
          <img src="{{ $product['url'] }}" 
          alt="Snack" class="w-full h-40 object-cover rounded-md">
          <h2 class="mt-3 text-base font-semibold text-white">{{ $product['name'] }}</h2>
            <a href="{{ route('payment', parameters: ['product_id' => $product['id'], 'price' => $product['price']]) }}">
              <button class="mt-3 w-full bg-indigo-600 hover:bg-indigo-900 text-white font-medium py-2 rounded-lg">
                Rp{{ number_format($product['price']) }}
              </button>
            </a>
        </div>
      @endforeach

    </div>
  </main>

  <footer class="mt-16 text-center text-gray-500 text-sm pb-6">
    &copy; {{ date('Y') }} Vending Machine | All rights reserved.
  </footer>

</body>
</html>
