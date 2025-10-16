<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-300">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment | Vending Machine</title>
  @vite('resources/css/app.css')
  {{-- AlpineJS untuk interaktivitas kecil --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    @keyframes pulse-dot {
      0%, 100% { opacity: 0.4; }
      50% { opacity: 1; }
    }
    .checking-indicator {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: currentColor;
      margin-left: 8px;
      animation: pulse-dot 1.5s ease-in-out infinite;
    }
  </style>
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
      @if(isset($error) && $error)
        <div class="mb-6 text-red-500 font-semibold">
          {{ $error }}
        </div>
      @endif
      <h2 class="text-2xl font-semibold text-white mb-6">
        Scan QRIS to Complete Your Payment
      </h2>

      {{-- Gambar QRIS --}}
      @if(isset($qris_url))
        <div class="flex justify-center">
          <img src="{{ $qris_url }}" alt="QRIS Code" class="w-80 object-contain rounded-lg">
        </div>

        <div class="mt-8 text-gray-300">
          <p>Payment Status:</p>
          <p id="payment-status" class="font-semibold text-yellow-400">
            Payment Pending
          </p>
        </div>

        <div class="mt-6">
          <button id="check-status-btn" 
             class="inline-block border-stone-50 border text-white px-6 py-3 rounded-lg font-medium transition hover:bg-gray-700">
            Check Payment Status
          </button>
        </div>

      @else
        <p class="text-gray-400">QRIS belum tersedia. Silakan coba lagi nanti.</p>
      @endif

      {{-- Payment Action Buttons --}}
        <div class="mt-8 space-y-4">
          {{-- Simulator Button --}}
          <div class="bg-gray-700/50 rounded-lg p-4 border-2 border-indigo-500/30">
            <div class="flex items-center mb-2">
              <span class="text-xs bg-indigo-600 text-white px-2 py-1 rounded">TESTING ONLY</span>
            </div>
            <a href="{{ $deeplink_url }}" 
                id="deeplink-btn"
                class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition shadow-lg hover:shadow-xl w-full sm:w-auto">
              📱 Pay with Mobile App
            </a>
            <p class="text-xs text-gray-400 mt-3">
              <strong>On Mobile:</strong> Click to automatically open GoPay app<br>
              <strong>Note:</strong> Requires GoPay app installed on your device
            </p>

            <div class="flex items-center my-4">
              <div class="flex-1 border-t border-gray-600"></div>
              <span class="px-4 text-gray-500 text-sm">OR</span>
              <div class="flex-1 border-t border-gray-600"></div>
            </div>

            <div class="mt-2 mb-4">
              <button id="copy-url-btn" class="mt-2 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
                📋 Copy QRIS URL
              </button>
              <p class="text-xs text-gray-400 mt-2">
                Copy the QR code URL to share or use elsewhere
              </p>
            </div>

            <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" 
               target="_blank"
               rel="noopener noreferrer"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium transition shadow-lg hover:shadow-xl w-full sm:w-auto">
              🧪 Simulate Payment
            </a>
            <p class="text-xs text-gray-400 mt-3">
              For development and testing purposes only.<br>
              Simulates a successful payment without real money transfer.
            </p>
          </div>
        </div>

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
    const orderId = "{{ $order_id }}";
    const statusText = document.getElementById('payment-status');
    let pollInterval = null;
    let isPaymentComplete = false;

    // Function to add checking indicator
    function showCheckingIndicator() {
      const indicator = document.querySelector('.checking-indicator');
      if (!indicator && !isPaymentComplete) {
        const dot = document.createElement('span');
        dot.className = 'checking-indicator';
        statusText.appendChild(dot);
      }
    }

    // Function to remove checking indicator
    function hideCheckingIndicator() {
      const indicator = document.querySelector('.checking-indicator');
      if (indicator) {
        indicator.remove();
      }
    }

    // Function to check payment status
    function checkPaymentStatus() {
      if (isPaymentComplete) return;
      
      // Show checking indicator
      showCheckingIndicator();
      
      // Call Laravel backend instead of Midtrans directly
      fetch(`/check-status/${orderId}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const status = data.transaction_status;
            
            if (status === 'settlement' || status === 'capture') {
              hideCheckingIndicator();
              statusText.textContent = 'Payment Success ✓';
              statusText.className = 'font-semibold text-green-400';
              isPaymentComplete = true;
              stopPolling();
              
              // Optional: Show success message or redirect
              setTimeout(() => {
                if (confirm('Payment successful! Would you like to make another purchase?')) {
                  window.location.href = '/';
                }
              }, 500);
              
            } else if (status === 'pending') {
              statusText.textContent = 'Payment Pending';
              statusText.className = 'font-semibold text-yellow-400';
              showCheckingIndicator();
            } else if (status === 'deny' || status === 'cancel' || status === 'expire') {
              hideCheckingIndicator();
              statusText.textContent = 'Payment Failed or Expired';
              statusText.className = 'font-semibold text-red-400';
              isPaymentComplete = true;
              stopPolling();
            } else {
              hideCheckingIndicator();
              statusText.textContent = `Status: ${status}`;
              statusText.className = 'font-semibold text-gray-400';
            }
          } else {
            hideCheckingIndicator();
            statusText.textContent = 'Error checking status';
            statusText.className = 'font-semibold text-red-400';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          hideCheckingIndicator();
          statusText.textContent = 'Error checking status';
          statusText.className = 'font-semibold text-red-400';
        });
    }

    // Function to start automatic polling
    function startPolling() {
      if (pollInterval) return; // Already polling
      
      // Check immediately
      checkPaymentStatus();
      
      // Then check every 10 seconds
      pollInterval = setInterval(checkPaymentStatus, 10000);
      
      // Stop polling after 10 minutes (600 seconds)
      setTimeout(() => {
        stopPolling();
        if (!isPaymentComplete) {
          hideCheckingIndicator();
          statusText.textContent = 'Payment Timeout - Please refresh to try again';
          statusText.className = 'font-semibold text-red-400';
        }
      }, 600000);
    }

    // Function to stop polling
    function stopPolling() {
      if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
      }
    }

    // Handle deeplink button click
    const deeplinkBtn = document.getElementById('deeplink-btn');
    if (deeplinkBtn) {
      deeplinkBtn.addEventListener('click', function(e) {
        // Show notification that user is being redirected
        statusText.textContent = 'Opening payment app...';
        statusText.className = 'font-semibold text-blue-400';
        
        // After clicking deeplink, check status more frequently (every 3 seconds)
        setTimeout(() => {
          if (!isPaymentComplete) {
            stopPolling();
            // Start faster polling after deeplink is clicked
            pollInterval = setInterval(checkPaymentStatus, 3000);
            
            statusText.textContent = 'Waiting for payment...';
            statusText.className = 'font-semibold text-yellow-400';
            showCheckingIndicator();
          }
        }, 1000);
      });
    }

    // Manual check button
    document.getElementById('check-status-btn').addEventListener('click', function() {
      if (!isPaymentComplete) {
        statusText.textContent = 'Checking...';
        statusText.className = 'font-semibold text-blue-400';
        checkPaymentStatus();
      }
    });

    // Start automatic polling when page loads
    window.addEventListener('load', function() {
      startPolling();
    });

    // Stop polling when user leaves the page
    window.addEventListener('beforeunload', function() {
      stopPolling();
    });
  </script>
  @endif

  {{-- Script for Copy QRIS URL Button --}}
  @if(isset($qris_url))
  <script>
    // Copy QRIS URL to clipboard
    document.getElementById('copy-url-btn').addEventListener('click', function() {
      const qrisUrl = "{{ $qris_url }}";
      const button = this;
      const originalText = button.textContent;
      
      // Use Clipboard API
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(qrisUrl)
          .then(() => {
            // Success feedback
            button.textContent = '✓ Copied!';
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            
            // Reset button after 2 seconds
            setTimeout(() => {
              button.textContent = originalText;
              button.classList.remove('bg-green-600', 'hover:bg-green-700');
              button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }, 2000);
          })
          .catch(err => {
            // Error feedback
            console.error('Failed to copy:', err);
            button.textContent = '✗ Failed';
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-red-600', 'hover:bg-red-700');
            
            // Reset button after 2 seconds
            setTimeout(() => {
              button.textContent = originalText;
              button.classList.remove('bg-red-600', 'hover:bg-red-700');
              button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }, 2000);
          });
      } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = qrisUrl;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
          const successful = document.execCommand('copy');
          if (successful) {
            button.textContent = '✓ Copied!';
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
          } else {
            button.textContent = '✗ Failed';
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-red-600', 'hover:bg-red-700');
          }
          
          // Reset button after 2 seconds
          setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700', 'bg-red-600', 'hover:bg-red-700');
            button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
          }, 2000);
        } catch (err) {
          console.error('Fallback: Failed to copy', err);
          button.textContent = '✗ Failed';
        }
        
        document.body.removeChild(textArea);
      }
    });
  </script>
  @endif

</body>
</html>
