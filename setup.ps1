# Setup & Installation Script for Lead Generation SaaS (Windows PowerShell)
# Usage: powershell -ExecutionPolicy Bypass -File .\setup.ps1

Write-Host "🚀 Lead Generation SaaS - Windows Setup Script" -ForegroundColor Cyan
Write-Host "=============================================`n" -ForegroundColor Cyan

# Check PHP
Write-Host "📦 Checking dependencies..." -ForegroundColor Yellow

try {
    $phpVersion = & php -v 2>&1 | Select-Object -First 1
    Write-Host "✅ $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ PHP not found. Install PHP 8.2+ and add to PATH." -ForegroundColor Red
    exit 1
}

try {
    $composerVersion = & composer --version 2>&1
    Write-Host "✅ $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Composer not found. Download from https://getcomposer.org" -ForegroundColor Red
    exit 1
}

try {
    $nodeVersion = & node -v 2>&1
    Write-Host "✅ Node.js: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Node.js not found. Install from https://nodejs.org" -ForegroundColor Red
    exit 1
}

try {
    $npmVersion = & npm -v 2>&1
    Write-Host "✅ npm: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ npm not found." -ForegroundColor Red
    exit 1
}

# Create .env if not exists
Write-Host "`n🔧 Setting up configuration..." -ForegroundColor Yellow

if (-not (Test-Path ".env")) {
    Write-Host "Creating .env from .env.example..."
    Copy-Item ".env.example" ".env"
    Write-Host "✅ .env created. Update with your Firebase credentials." -ForegroundColor Green
} else {
    Write-Host "✅ .env already exists." -ForegroundColor Green
}

# Create database directory
if (-not (Test-Path "database")) {
    New-Item -ItemType Directory -Path "database" -Force | Out-Null
}

# Create SQLite database
if (-not (Test-Path "database/database.sqlite")) {
    Write-Host "Creating SQLite database..."
    New-Item -ItemType File -Path "database/database.sqlite" -Force | Out-Null
    Write-Host "✅ database/database.sqlite created." -ForegroundColor Green
}

# Generate app key
Write-Host "`n🔑 Generating application key..." -ForegroundColor Yellow
& php artisan key:generate

# Install PHP dependencies
Write-Host "`n📥 Installing PHP dependencies..." -ForegroundColor Yellow
& composer install
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Composer install failed." -ForegroundColor Red
    exit 1
}

# Install Firebase SDK
Write-Host "`n🔥 Installing Firebase SDK..." -ForegroundColor Yellow
& composer require kreait/firebase-php
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Firebase SDK install failed (may need internet connection)." -ForegroundColor Yellow
}

# Install Node dependencies
Write-Host "`n📥 Installing Node dependencies..." -ForegroundColor Yellow
& npm ci
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  npm install completed with warnings." -ForegroundColor Yellow
}

# Run migrations
Write-Host "`n🗄️  Running database migrations..." -ForegroundColor Yellow
& php artisan migrate --force

# Create storage directory and Firebase key placeholder
if (-not (Test-Path "storage")) {
    New-Item -ItemType Directory -Path "storage" -Force | Out-Null
}

if (-not (Test-Path "storage/firebase-key.json")) {
    $firebaseTemplate = @"
{
  "type": "service_account",
  "project_id": "your-project-id",
  "private_key_id": "your-key-id",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk@your-project-id.iam.gserviceaccount.com",
  "client_id": "your-client-id",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk%40your-project-id.iam.gserviceaccount.com"
}
"@
    $firebaseTemplate | Out-File -FilePath "storage/firebase-key.json" -Encoding UTF8
    Write-Host "ℹ️  Created placeholder storage/firebase-key.json" -ForegroundColor Cyan
    Write-Host "⚠️  Update it with your actual Firebase credentials!" -ForegroundColor Yellow
}

# Build frontend
Write-Host "`n🎨 Building frontend..." -ForegroundColor Yellow
& npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Frontend build completed with issues." -ForegroundColor Yellow
}

# Final summary
Write-Host "`n✅ Setup complete!`n" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Update .env with your Firebase credentials"
Write-Host "2. Update storage/firebase-key.json with your Firebase service account key"
Write-Host "3. (Optional) Run seeders: php artisan migrate:refresh --seed"
Write-Host "4. Start development server:"
Write-Host "   - Terminal 1: php artisan serve"
Write-Host "   - Terminal 2: npm run dev"
Write-Host ""
Write-Host "Happy coding! 🚀" -ForegroundColor Cyan
