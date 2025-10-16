<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-300">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment | Vending Machine</title>
  @vite('resources/css/app.css')
  {{-- AlpineJS untuk interaktivitas kecil --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full text-gray-200">

  <!-- Navbar -->
  <nav class="bg-gray-800/70 backdrop-blur-md border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center space-x-4">
          <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
               alt="Logo" class="size-8">
          <span class="font-semibold text-white text-lg">Vending Machine</span>
        </div>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <header class="bg-gray-800 border-y border-gray-700">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center">
      <h1 class="text-3xl font-bold text-white">MAKE A PAYMENT</h1>
      <p class="text-gray-400 mt-2">Please make your payment by scanning the QRIS code below</p>
    </div>
  </header>

  <!-- Main content -->
  <main class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
      <h2 class="text-2xl font-semibold text-white mb-6">
        Scan QRIS to Complete Your Payment
      </h2>

      {{-- Gambar QRIS --}}
      @if(isset($qris_url))
        <div class="flex justify-center">
          <img src="{{ $qris_url }}" alt="QRIS Code" class="w-56 h-56 object-contain rounded-lg border border-gray-600">
        </div>

        <div class="mt-6">
          <a href="{{ $simulator_url ?? '#' }}" 
             target="_blank"
             class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
            Klik untuk Simulator Pembayaran
          </a>
        </div>

        <div class="mt-6 text-gray-300">
          <p>Status Pembayaran:</p>
          <p id="payment-status" class="font-semibold text-yellow-400">
            Payment Pending
          </p>
        </div>
      @else
        <p class="text-gray-400">QRIS belum tersedia. Silakan coba lagi nanti.</p>
      @endif

      <div class="mt-8 text-gray-400 text-sm">
        Enjoy your shopping at Vending Machine!
      </div>
    </div>
  </main>

  <footer class="mt-16 text-center text-gray-500 text-sm pb-6">
    &copy; {{ date('Y') }} Vending Machine | All rights reserved.
  </footer>

  {{-- Script untuk auto-check status pembayaran --}}
  @if(isset($order_id))
  <script>
    setInterval(() => {
      fetch(`/check-status/{{ $order_id }}`)
        .then(res => res.json())
        .then(data => {
          const statusText = document.getElementById('payment-status');
          if (data.status === 'settlement') {
            statusText.textContent = 'Payment Success';
            statusText.className = 'font-semibold text-green-400';
          } else if (data.status === 'pending') {
            statusText.textContent = 'Payment Pending';
            statusText.className = 'font-semibold text-yellow-400';
          } else {
            statusText.textContent = 'Payment Failed or Expired';
            statusText.className = 'font-semibold text-red-400';
          }
        });
    }, 5000);
  </script>
  @endif

</body>
</html>
