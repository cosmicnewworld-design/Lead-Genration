# ⚡ Quick Production Setup Guide (Hindi + English)

**Status**: Project ✅ Ready | Backend setup 🔧 Manual needed

---

## Problem: PHP/Composer System Setup Issue

Local system installation fails. Solution: Use **Docker** (easiest) OR **manual WSL/Git Bash**.

---

## ✅ Solution 1: Docker (Recommended - 2 min setup)

### Install Docker Desktop
1. Download: https://www.docker.com/products/docker-desktop
2. Install & restart PC
3. Run from project root:

```bash
docker-compose up -d
```

**Done!** Server running on `http://localhost:8080`

---

## ✅ Solution 2: Manual Setup (5 min - Using Git Bash or WSL2)

### Step 1: Install PHP (Non-Admin Way)

**Option A: Portable PHP (Fastest)**
- Download: https://www.apachefriends.org/download.html (XAMPP)
- Or: Download standalone PHP ZIP
- Extract to folder (e.g., `D:\php-8.3`)
- Add full path to Windows PATH manually:
  - Right-click This PC → Properties → Advanced → Environment Variables
  - Add `D:\php-8.3` to PATH
  - Restart PowerShell

**Option B: Using Git Bash (if installed)**
```bash
# Install via scoop (package manager for Windows)
curl -usehead https://get.scoop.sh | pwsh
scoop install php
scoop install composer
```

### Step 2: Verify Installation

```bash
php -v
composer --version
```

### Step 3: Run Setup Script

```bash
cd "c:\Users\SPL2\Desktop\lead software\Lead-Genration"

# Windows PowerShell:
.\setup.ps1

# Or Git Bash:
bash setup.sh
```

### Step 4: Run Development Server

```bash
# Terminal 1: Frontend
npm run dev

# Terminal 2: Backend
php artisan serve
```

Visit: http://localhost:8000

---

## ✅ Solution 3: WSL2 + Linux (Most Stable)

### Enable WSL2
```powershell
wsl --install
wsl --set-default-version 2
```

### Inside WSL Terminal
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install tools
sudo apt install php php-cli php-mbstring php-zip composer nodejs npm -y

# Clone/navigate to project
cd /mnt/c/Users/SPL2/Desktop/lead\ software/Lead-Genration

# Run setup
bash setup.sh
```

---

## 🚀 Quick Deploy (All Paths)

After setup:

```bash
# 1. Configure Firebase
# Copy your Firebase service account JSON to storage/firebase-key.json

# 2. Run migrations
php artisan migrate --seed

# 3. Optionally migrate data to Firestore
php artisan leads:migrate-to-firestore --chunk=100

# 4. Generate frontend assets
npm run build

# 5. Start servers
npm run dev      # Terminal 1
php artisan serve  # Terminal 2

# 6. Visit
# Frontend: http://localhost:5173
# Backend: http://localhost:8000
```

---

## 📋 Troubleshooting

### "php: command not found"
```bash
# Add to PATH manually or use full path:
C:\php-8.3\php.exe -v
```

### "composer: command not found"
```bash
# Download standalone phar:
php C:\composer.phar install
```

### npm build fails
```bash
npm ci  # Reinstall
npm run build
```

### Port 8000 already in use
```bash
php artisan serve --port=8001
```

---

## 🐳 Docker Alternative (Easiest)

Just run:
```bash
docker-compose up -d
```

No PHP/Composer install needed!

Check logs:
```bash
docker-compose logs -f app
```

---

## 📚 Quick Reference

| Task | Command |
|------|---------|
| Install deps | `composer install` |
| Run migrations | `php artisan migrate` |
| View migrations status | `php artisan migrate:status` |
| Create migration | `php artisan make:migration create_table` |
| Seed database | `php artisan migrate:refresh --seed` |
| Clear cache | `php artisan cache:clear` |
| Build frontend | `npm run build` |
| Dev frontend | `npm run dev` |
| Run tests | `php artisan test` |
| Code style fix | `php artisan pint` |
| Migrate to Firebase | `php artisan leads:migrate-to-firestore` |

---

## 🔐 Firebase Setup (Required for Production)

1. Create project: https://console.firebase.google.com
2. Download Service Account JSON
3. Save to `storage/firebase-key.json`
4. Update `.env`:
```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_CREDENTIALS_PATH=storage/firebase-key.json
```

5. Register provider in `config/app.php`:
```php
'providers' => [
    App\Providers\FirebaseServiceProvider::class,  // Add this
],
```

6. Test Firebase:
```bash
php artisan tinker
# Type: app('firebase')->getDatabase()->getRootReference()->getSnapshot()->val()
# Should return data or null (success)
```

---

## ✅ Final Checklist

- [ ] PHP installed (`php -v` works)
- [ ] Composer installed (`composer --version` works)  
- [ ] npm working (`npm -v` works)
- [ ] `.env` exists (copy from `.env.example`)
- [ ] `storage/firebase-key.json` exists
- [ ] `php artisan key:generate` ran
- [ ] `php artisan migrate` ran
- [ ] `npm ci` ran
- [ ] `npm run build` successful
- [ ] `php artisan serve` starts without errors
- [ ] Browser shows app on http://localhost:8000

---

## 📞 If Everything Fails: Docker Fallback

```bash
docker-compose up
```

This should work 99% of the time with no system PHP needed!

---

**Still stuck? Let me know which solution you want to try and I'll help debug!** 🚀
