#!/bin/bash

# 🌐 ngrok Setup for Laravel with Built Assets
# This script uses the RECOMMENDED approach: Single ngrok tunnel

echo "🚀 Starting ngrok for Laravel..."
echo ""
echo "⚠️  IMPORTANT: Make sure you've run 'npm run build' first!"
echo ""

# Check if assets are built
if [ ! -d "public/build" ]; then
    echo "❌ Assets not built! Running 'npm run build' first..."
    npm run build
    if [ $? -ne 0 ]; then
        echo "❌ Build failed! Please fix errors and try again."
        exit 1
    fi
    echo "✅ Assets built!"
    echo ""
fi

# Start ngrok for Laravel only (port 8000)
echo "🌐 Starting ngrok tunnel for port 8000..."
ngrok http 8000 --log=stdout

# When ngrok stops, show cleanup message
echo ""
echo "🛑 ngrok stopped."
echo ""
echo "Next steps:"
echo "1. Copy the ngrok URL from above"
echo "2. Update .env file:"
echo "   APP_URL=https://your-ngrok-url.ngrok-free.app"
echo "   ASSET_URL=https://your-ngrok-url.ngrok-free.app"
echo "3. Run: php artisan config:clear"
echo "4. Open the ngrok URL on any device!"
echo ""
