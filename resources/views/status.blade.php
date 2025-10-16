<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Pembayaran | Vending Machine</title>
  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full text-gray-200">

    <!-- Navbar -->
    <nav class="bg-gray-800/70 backdrop-blur-md border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-4">
            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Logo" class="size-8">
            <span class="font-semibold text-white text-lg">Vending Machine</span>
            </div>
        </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-gray-800 border-y border-gray-700">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-white">Payment Status</h1>
        <p class="text-gray-400 mt-2">Check the current status of your payment below</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8">

        <div class="bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
        @if (session('status') == 'success')
            <div class="flex flex-col items-center space-y-4">
            <div class="bg-green-500/20 p-4 rounded-full">
                <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-green-400">Payment Successful!</h2>
            <p class="text-gray-300">Your transaction was completed successfully.  
                Please check your email for the receipt.
            </p>
            </div>

        @elseif (session('status') == 'pending')
            <div class="flex flex-col items-center space-y-4">
            <div class="bg-yellow-500/20 p-4 rounded-full">
                <svg class="w-16 h-16 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 1A9 9 0 1112 3a9 9 0 019 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-yellow-400">Payment Pending</h2>
            <p class="text-gray-300">Your payment is being processed.  
                Please wait a few moments and refresh this page.
            </p>
            </div>

        @elseif (session('status') == 'failed')
            <div class="flex flex-col items-center space-y-4">
            <div class="bg-red-500/20 p-4 rounded-full">
                <svg class="w-16 h-16 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-red-400">Payment Failed</h2>
            <p class="text-gray-300">Unfortunately, your transaction could not be completed.  
                Please try again or contact support.
            </p>
            </div>

        @else
            <div class="text-gray-400">
            <p>No payment data available. Please make a payment first.</p>
            </div>
        @endif

        <div class="mt-8">
            <a href="/prepayment" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
            Back to Products
            </a>
        </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 text-center text-gray-500 text-sm pb-6">
        &copy; {{ date('Y') }} Vending Machine | All rights reserved.
    </footer>

</body>
</html>
