# 🌐 ngrok Setup Guide - Access Laravel from Other Devices

## 🎯 Problem

When you expose Laravel on port 8000 via ngrok, Vite assets (CSS/JS) don't load because they're served from a different port (5173) that isn't exposed.

## ✅ Solution Overview

You have **TWO options**:

### **Option 1: Use Built Assets (RECOMMENDED) ⭐**

-   Build Vite assets once
-   Only expose Laravel port (8000)
-   Best for: Sharing with others, testing on mobile

### **Option 2: Dual ngrok Tunnels (For Development)**

-   Expose both Laravel (8000) and Vite (5173)
-   Keep hot module replacement (HMR)
-   Best for: Active development with live reload

---

## 🚀 Option 1: Use Built Assets (SIMPLE & FAST)

### Step 1: Build Vite Assets

```bash
npm run build
```

### Step 2: Update .env

```bash
APP_URL=https://your-ngrok-url.ngrok-free.app
ASSET_URL=https://your-ngrok-url.ngrok-free.app
```

### Step 3: Start Laravel

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 4: Start ngrok

```bash
ngrok http 8000
```

### Step 5: Update APP_URL

Copy the ngrok URL and update your `.env`:

```bash
APP_URL=https://xxxx-xxxx-xxxx.ngrok-free.app
ASSET_URL=https://xxxx-xxxx-xxxx.ngrok-free.app
```

### Step 6: Clear Config Cache

```bash
php artisan config:clear
```

### ✅ Done!

Now open the ngrok URL on any device - UI will look perfect! 🎉

---

## 🔧 Option 2: Dual ngrok Tunnels (Advanced)

Use this if you need live reload during development.

### Step 1: Get ngrok Pro Account

Free ngrok allows only 1 tunnel. For 2 tunnels, you need:

-   ngrok Pro account, OR
-   Use ngrok alternative like localtunnel or serveo

### Step 2: Alternative - Use localtunnel (Free)

Install localtunnel:

```bash
npm install -g localtunnel
```

Start both tunnels:

```bash
# Terminal 1: Laravel
lt --port 8000 --subdomain my-laravel-app

# Terminal 2: Vite
lt --port 5173 --subdomain my-vite-assets
```

### Step 3: Update .env

```bash
APP_URL=https://my-laravel-app.loca.lt
VITE_DEV_SERVER_URL=https://my-vite-assets.loca.lt
```

### Step 4: Update vite.config.js

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            protocol: "wss",
            host: "my-vite-assets.loca.lt",
        },
    },
});
```

---

## 🎯 Recommended Workflow

### For **Sharing/Testing** (Option 1):

```bash
# 1. Build assets
npm run build

# 2. Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# 3. Start ngrok
ngrok http 8000

# 4. Update .env with ngrok URL
APP_URL=https://xxxx.ngrok-free.app
ASSET_URL=https://xxxx.ngrok-free.app

# 5. Clear cache
php artisan config:clear

# 6. Share the ngrok URL! ✅
```

### For **Active Development** (Option 2):

Use local network instead of ngrok:

```bash
# 1. Find your local IP
ipconfig getifaddr en0  # Mac
# or
hostname -I  # Linux

# 2. Start Vite
npm run dev -- --host

# 3. Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# 4. Access from other device on same network
http://192.168.x.x:8000  # Use your actual IP
```

---

## 🐛 Troubleshooting

### Issue 1: CSS/JS Not Loading

**Symptom:** Page loads but no styles, buttons don't work

**Solution:**

```bash
# Option A: Rebuild assets
npm run build
php artisan config:clear

# Option B: Check ASSET_URL
# Make sure ASSET_URL matches APP_URL in .env
```

### Issue 2: ngrok "ERR_NGROK_108"

**Symptom:** ngrok says too many connections

**Solution:**

```bash
# Free ngrok = 1 tunnel only
# Kill existing ngrok processes
pkill ngrok

# Or use localtunnel instead
npm install -g localtunnel
lt --port 8000
```

### Issue 3: Mixed Content Error

**Symptom:** Console shows "Mixed Content" errors

**Solution:**

```bash
# Make sure both APP_URL and ASSET_URL use HTTPS
APP_URL=https://xxxx.ngrok-free.app
ASSET_URL=https://xxxx.ngrok-free.app

# Not HTTP:
# APP_URL=http://xxxx.ngrok-free.app  ❌
```

### Issue 4: Vite HMR Not Working

**Symptom:** Changes don't reflect, need manual refresh

**Solution:**

```bash
# This is expected with built assets
# Either:
# A) Accept manual refresh after npm run build
# B) Use local network instead of ngrok for development
```

---

## 📱 Testing Checklist

After setup, verify:

-   [ ] Page loads completely
-   [ ] CSS styles applied correctly
-   [ ] JavaScript works (buttons clickable)
-   [ ] Images load
-   [ ] Tailwind classes working
-   [ ] No console errors
-   [ ] Mobile responsive design works

---

## 💡 Pro Tips

### Tip 1: Persistent ngrok URL

Sign up for ngrok account and use custom subdomain:

```bash
ngrok http 8000 --subdomain=my-app
# URL will always be: https://my-app.ngrok-free.app
```

### Tip 2: Auto-rebuild on Changes

Watch for changes and rebuild automatically:

```bash
npm run build -- --watch
```

### Tip 3: Use Environment Files

Create separate .env files:

```bash
# .env.local - For local development
# .env.ngrok - For ngrok testing

# Load specific env:
php artisan config:clear
```

### Tip 4: Local Network Alternative

Instead of ngrok, use local network:

```bash
# Find IP: ipconfig getifaddr en0
# Access: http://192.168.1.100:8000

# Benefits:
# ✅ Free
# ✅ Fast
# ✅ Hot reload works
# ✅ No internet needed

# Limitations:
# ❌ Same network only
# ❌ Can't share externally
```

---

## 🔐 Security Notes

### For ngrok:

-   ⚠️ Never expose production database
-   ⚠️ Disable debug mode for external sharing
-   ⚠️ Use ngrok auth for sensitive apps
-   ⚠️ Remember to stop tunnel when done

### Setup ngrok Authentication:

```bash
# Add password protection
ngrok http 8000 --basic-auth="username:password"
```

---

## 📊 Comparison Table

| Method                   | Pros                                        | Cons                                  | Best For           |
| ------------------------ | ------------------------------------------- | ------------------------------------- | ------------------ |
| **Built Assets + ngrok** | ✅ Simple<br>✅ Fast<br>✅ Works everywhere | ❌ Manual rebuild<br>❌ No hot reload | Sharing, Testing   |
| **Dual Tunnels**         | ✅ Hot reload<br>✅ Live updates            | ❌ Complex<br>❌ Requires paid ngrok  | Active development |
| **Local Network**        | ✅ Free<br>✅ Fast<br>✅ Hot reload         | ❌ Same network only                  | Team dev           |

---

## 🎯 Quick Commands Reference

```bash
# Build assets
npm run build

# Build and watch
npm run build -- --watch

# Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Start ngrok
ngrok http 8000

# Clear config
php artisan config:clear

# Kill ngrok
pkill ngrok

# Find local IP
ipconfig getifaddr en0  # Mac
ip addr show  # Linux
ipconfig  # Windows

# Start Vite with external access
npm run dev -- --host
```

---

## 📚 Additional Resources

-   [ngrok Documentation](https://ngrok.com/docs)
-   [Vite Server Options](https://vitejs.dev/config/server-options.html)
-   [Laravel Vite Plugin](https://laravel.com/docs/vite)
-   [localtunnel Alternative](https://github.com/localtunnel/localtunnel)

---

**Last Updated:** October 17, 2025  
**Status:** ✅ Tested & Working  
**Recommended:** Option 1 (Built Assets)
