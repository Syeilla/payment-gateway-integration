# 🚀 Quick Start - Access Your App from Other Devices

## ⚡ Super Quick Setup (5 Steps)

```bash
# Step 1: Build assets (one time)
npm run build

# Step 2: Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Step 3: Start ngrok (in another terminal)
ngrok http 8000

# Step 4: Copy ngrok URL and update .env
# Change this line in .env:
APP_URL=https://xxxx-xxxx-xxxx.ngrok-free.app
ASSET_URL=https://xxxx-xxxx-xxxx.ngrok-free.app

# Step 5: Clear config
php artisan config:clear

# ✅ Done! Open ngrok URL on any device
```

---

## 🎯 The Complete Workflow

### Terminal 1: Laravel Server

```bash
cd /Users/wisnu/Documents/wisungyo/QRIS\ Payment
php artisan serve --host=0.0.0.0 --port=8000
```

**Keep this running** ✓

### Terminal 2: ngrok

```bash
cd /Users/wisnu/Documents/wisungyo/QRIS\ Payment
./start-ngrok.sh
```

**Or manually:**

```bash
ngrok http 8000
```

### Terminal 3: Make Changes (optional)

```bash
# When you edit files, rebuild:
npm run build

# Clear cache:
php artisan config:clear

# Refresh browser on mobile device
```

---

## 📱 Current Setup Status

Based on your `.env`, you should use:

```
ngrok URL: https://0eb189dd7ae3.ngrok-free.app
```

Make sure your `.env` has:

```bash
APP_URL=https://0eb189dd7ae3.ngrok-free.app
ASSET_URL=https://0eb189dd7ae3.ngrok-free.app
```

---

## ⚠️ Important Notes

### ❌ Don't Use start-ngrok.sh (old dual tunnel script)

The old script tries to start 2 ngrok tunnels, which requires a paid plan.

### ✅ Use This Instead:

```bash
# Option 1: Simple ngrok command
ngrok http 8000

# Option 2: Use the updated start-ngrok.sh
./start-ngrok.sh
```

---

## 🐛 Troubleshooting

### Error: "ERR_NGROK_108"

**Cause:** Trying to run multiple ngrok tunnels (free tier = 1 tunnel only)

**Fix:**

```bash
# Kill all ngrok processes
pkill ngrok

# Start only ONE tunnel
ngrok http 8000
```

### Error: CSS/JS Not Loading

**Cause:** Assets not built or ASSET_URL not set

**Fix:**

```bash
# 1. Build assets
npm run build

# 2. Check .env has ASSET_URL
ASSET_URL=https://your-ngrok-url.ngrok-free.app

# 3. Clear cache
php artisan config:clear
```

### Error: Laravel Server Not Accessible

**Cause:** Server not listening on 0.0.0.0

**Fix:**

```bash
# Make sure to use --host=0.0.0.0
php artisan serve --host=0.0.0.0 --port=8000

# NOT just:
# php artisan serve  ❌
```

---

## 🎨 Visual Guide

```
┌─────────────────────────────────────────┐
│ Your Mac (Development Machine)          │
├─────────────────────────────────────────┤
│                                         │
│  Laravel Server (port 8000)             │
│  ↓                                      │
│  Built Vite Assets (public/build/)      │
│  ↓                                      │
│  ngrok Tunnel                           │
│  ↓                                      │
│  https://xxxx.ngrok-free.app            │
│                                         │
└──────────────────┬──────────────────────┘
                   │
                   │ Internet
                   │
        ┌──────────┴──────────┐
        │                     │
        ▼                     ▼
   📱 Phone             💻 Other Device

   Both see perfect UI! ✨
```

---

## 🔄 Development Workflow

### First Time Setup:

```bash
npm run build
php artisan serve --host=0.0.0.0 --port=8000
ngrok http 8000
# Update .env with ngrok URL
php artisan config:clear
```

### When You Make Changes:

```bash
# Edit your files
# Then rebuild:
npm run build
php artisan config:clear
# Refresh mobile browser
```

### Daily Usage:

```bash
# Terminal 1
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2
ngrok http 8000

# All set! ✅
```

---

## 💡 Pro Tips

### Tip 1: Use Local Network for Development

Instead of ngrok, use your local IP for faster development:

```bash
# Find your IP
ipconfig getifaddr en0
# Output: 192.168.1.100

# Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Start Vite with hot reload
npm run dev -- --host

# Access from phone (same WiFi):
http://192.168.1.100:8000

# Benefits:
✅ Free
✅ Hot reload works!
✅ Instant updates
✅ No rebuilding needed
```

### Tip 2: Persistent ngrok URL

Sign in to ngrok and use custom subdomain:

```bash
ngrok config add-authtoken YOUR_TOKEN
ngrok http 8000 --subdomain=my-payment-app

# URL will always be:
https://my-payment-app.ngrok-free.app
```

### Tip 3: Auto-rebuild on Save

Watch for file changes:

```bash
npm run build -- --watch
```

---

## 📊 Quick Reference

| Command                                        | Purpose            |
| ---------------------------------------------- | ------------------ |
| `npm run build`                                | Build Vite assets  |
| `php artisan serve --host=0.0.0.0 --port=8000` | Start Laravel      |
| `ngrok http 8000`                              | Expose Laravel     |
| `php artisan config:clear`                     | Clear config cache |
| `pkill ngrok`                                  | Stop all ngrok     |
| `ipconfig getifaddr en0`                       | Get local IP       |

---

## ✅ Checklist Before Sharing

-   [ ] Assets built (`npm run build`)
-   [ ] Laravel running on 0.0.0.0:8000
-   [ ] ngrok tunnel active
-   [ ] .env updated with ngrok URL
-   [ ] Config cleared
-   [ ] Tested on mobile device
-   [ ] UI looks perfect
-   [ ] All features working

---

## 🎯 What You Should See

### On Mobile Device:

-   ✅ Beautiful Tailwind UI
-   ✅ Proper colors and spacing
-   ✅ Working buttons
-   ✅ QR code displays
-   ✅ Payment status updates
-   ✅ Responsive design
-   ✅ No console errors

### On Browser Console:

-   ✅ No 404 errors for CSS/JS
-   ✅ No CORS errors
-   ✅ Assets load from ngrok URL

---

**Need Help?** Check `NGROK_SETUP_GUIDE.md` for detailed documentation.

**Last Updated:** October 17, 2025  
**Status:** ✅ Ready to Use
