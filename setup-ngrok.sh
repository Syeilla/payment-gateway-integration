#!/bin/bash

# 🌐 Quick ngrok Setup Script
# This script prepares your Laravel app for ngrok access

echo "🚀 Setting up Laravel for ngrok access..."
echo ""

# Step 1: Build Vite assets
echo "📦 Building Vite assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ Build failed! Please check for errors."
    exit 1
fi
echo "✅ Assets built successfully!"
echo ""

# Step 2: Clear config cache
echo "🧹 Clearing config cache..."
php artisan config:clear
echo "✅ Config cleared!"
echo ""

# Step 3: Instructions
echo "✨ Setup complete! Next steps:"
echo ""
echo "1️⃣  Make sure your .env has:"
echo "   APP_URL=https://your-ngrok-url.ngrok-free.app"
echo "   ASSET_URL=https://your-ngrok-url.ngrok-free.app"
echo ""
echo "2️⃣  Start Laravel server (in another terminal):"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "3️⃣  Start ngrok (in another terminal):"
echo "   ngrok http 8000"
echo ""
echo "4️⃣  Copy the ngrok URL and update .env, then run:"
echo "   php artisan config:clear"
echo ""
echo "5️⃣  Open the ngrok URL on any device! 🎉"
echo ""
