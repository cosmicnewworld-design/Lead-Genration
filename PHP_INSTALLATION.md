# 🐘 PHP Installation Guide - Detailed Steps (Hindi + English)

## Option 1: XAMPP (⭐ EASIEST - Recommended)

XAMPP एक **All-in-One installer** है जो PHP, Apache, MySQL सब एक साथ install करता है।

---

## Step-by-Step Installation (5 minutes)

### Step 1️⃣: Download XAMPP

1. Open browser और जाओ: **https://www.apachefriends.org**
2. Page पर "**Download**" button दिखेगा
3. **Windows** version download करो (XAMPP for Windows)
4. Latest version select करो (आमतौर पर 8.2 या ऊपर होगा)

```
Expected file: xampp-windows-x64-8.2.x-installer.exe (या similar)
Size: ~150-200 MB
```

---

### Step 2️⃣: Install XAMPP

1. Downloaded file को **double-click** करो
2. **"User Account Control"** window आएगी → **"Yes"** दबाओ
3. Installation window खुलेगी:
   - क्या install करना है choose करो (सब select रहने दो)
   - **Next** → **Next** दबाओ
4. Installation path:
   ```
   Default: C:\xampp
   (इसे as-is रहने दो)
   ```
5. **Install** button दबाओ
6. Wait करो (2-3 minutes लगेंगे)
7. **Finish** दबाओ

---

### Step 3️⃣: Verify Installation

1. Start menu में search करो: **"XAMPP Control Panel"**
2. Click करो XAMPP Control Panel को
3. Window खुलेगी जैसे:
   ```
   Apache:  [Start] [Stop] [Admin]
   MySQL:   [Start] [Stop] [Admin]
   ...
   ```

अगर इतना दिखता है तो ✅ **Installation successful!**

---

## Step 4️⃣: Copy Your Project

1. File Explorer खोलो
2. Navigate करो: **C:\xampp\htdocs**
3. अपने project को copy करो:
   ```
   C:\Users\SPL2\Desktop\lead software\Lead-Genration
   ```
   को
   ```
   C:\xampp\htdocs\Lead-Genration
   ```

**Command से करें** (PowerShell में):
```powershell
Copy-Item -Recurse "C:\Users\SPL2\Desktop\lead software\Lead-Genration" -Destination "C:\xampp\htdocs\Lead-Genration"
```

---

## Step 5️⃣: Start Services

1. **XAMPP Control Panel** खोलो
2. **Apache** के आगे click करो: **[Start]**
   - Text turn green होगा = running
3. **MySQL** के आगे click करो: **[Start]**
   - Text turn green होगा = running

**Wait 5 seconds** for full startup.

---

## Step 6️⃣: Run Composer Install

1. **PowerShell** खोलो (Admin mode में)
2. Navigate करो अपने project के लिए:
   ```powershell
   cd "C:\xampp\htdocs\Lead-Genration"
   ```
3. Check करो कि XAMPP PHP काम कर रहा है:
   ```powershell
   C:\xampp\php\php.exe -v
   ```
   (Should show PHP version)

4. Download करो Composer:
   ```powershell
   Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile "composer.phar"
   ```

5. Run करो Composer install:
   ```powershell
   C:\xampp\php\php.exe composer.phar install
   ```
   (यह 2-3 minutes लेगा)

---

## Step 7️⃣: Setup Laravel

In PowerShell (same location):

```powershell
# 1. Generate app key
C:\xampp\php\php.exe -S localhost:8000
# OR use artisan:
C:\xampp\php\php.exe artisan key:generate

# 2. Create database file
New-Item -Path "database\database.sqlite" -ItemType File

# 3. Run migrations
C:\xampp\php\php.exe artisan migrate --force

# 4. (Optional) Seed demo data
C:\xampp\php\php.exe artisan db:seed
```

---

## Step 8️⃣: Access Application

**Browser में open करो:**

```
http://localhost/Lead-Genration
```

✅ **आपका application चल रहा होगा!**

---

## Easier Way: Run Setup Script

अगर ऊपर confusing लगे तो, main PowerShell script बना दूंगा जो सब करेगी:

```powershell
# Just run this in project folder:
$phpExe = "C:\xampp\php\php.exe"
$composerUrl = "https://getcomposer.org/composer.phar"

# Download composer
Invoke-WebRequest -Uri $composerUrl -OutFile "composer.phar"

# Install PHP deps
& $phpExe composer.phar install

# Generate key
& $phpExe artisan key:generate

# Create database
New-Item -Path "database\database.sqlite" -ItemType File

# Migrate
& $phpExe artisan migrate --force

# Build frontend
npm run build

Write-Host "✅ Setup complete! Visit http://localhost/Lead-Genration" -ForegroundColor Green
```

---

---

## Option 2: Manual PHP Install (Advanced)

अगर XAMPP नहीं चाहते तो manual भी कर सकते हो:

### Step 1: Download PHP ZIP

1. Go to: **https://windows.php.net/download/**
2. Choose: **PHP 8.2 or 8.3**
3. Download: **"VC16 x64 Non Thread Safe"** version
4. Extract to: **C:\php** (यदि extract करना है manually)

### Step 2: Add to PATH

1. Right-click **This PC** → **Properties**
2. Click **Advanced system settings**
3. Click **Environment Variables**
4. Under "System variables", find **Path**, select it, click **Edit**
5. Click **New** and add: **C:\php**
6. Click **OK** three times
7. **Restart PowerShell**

### Step 3: Verify

```powershell
php -v
```

Should show: `PHP 8.2.x`

### Step 4: Download & Install Composer

```powershell
Invoke-WebRequest -Uri "https://getcomposer.org/installer" -OutFile "composer-setup.php"
php composer-setup.php
```

---

---

## Troubleshooting

### "XAMPP won't start"
```
• Make sure no other Apache is running
• Try running XAMPP Control Panel as Administrator
• Check if ports 80 and 3306 are free
```

### "Apache won't start (Port 80 in use)"
```
• Change Apache port in XAMPP config
• Or stop IIS if running
• Or change port to 8080
```

### "MySQL won't start"
```
• Click "MySQL" → "Config" → "my.ini"
• Find: max_allowed_packet = 16M
• Change to: max_allowed_packet = 256M
• Try starting again
```

### "Composer error"
```powershell
# If composer.phar doesn't work, use:
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
# Then: php composer.phar install
```

### "Permission denied"
```
• Run PowerShell as Administrator
• Or change folder permissions in File Explorer
```

---

---

## ✅ Verification Checklist

After setup, check:

```powershell
# Verify PHP
php -v
# Should show: PHP 8.x.x

# Verify Composer
php composer.phar --version
# Should show: Composer version

# Check project has vendor/
ls vendor/ | head -5
# Should list folders
```

---

## 🚀 Next Commands After Setup

Once XAMPP is running:

```bash
# Start development server
php artisan serve
# Access at: http://localhost:8000

# Build frontend
npm run build

# Run migrations
php artisan migrate --seed

# Access application
http://localhost/Lead-Genration
```

---

## 📋 Summary

| Step | Action | Time |
|------|--------|------|
| 1 | Download XAMPP | 5 min |
| 2 | Install XAMPP | 3 min |
| 3 | Copy project | 1 min |
| 4 | Start Apache + MySQL | 1 min |
| 5 | Run composer install | 3 min |
| 6 | Run migrations | 1 min |
| 7 | Access app | instant |
| **TOTAL** | | **~15-20 min** |

---

## 🎯 Final Status

Once complete:

```
✅ PHP installed (C:\xampp\php)
✅ Composer working
✅ Database created
✅ Migrations run
✅ Application accessible at http://localhost/Lead-Genration
```

---

## 💡 Recommendation

**XAMPP सबसे आसान है:**
- GUI से manage करना आसान
- सब कुछ एक जगह
- Windows के लिए perfect
- 5 minutes में तैयार

**Take 15 minutes और complete करो! 🚀**

---

**अब बताओ:**
1. XAMPP download करने में कोई issue?
2. Installation के दौरान कोई error?
3. Project copy करने में problem?

**Main तुरंत help करूंगा!** 💪
