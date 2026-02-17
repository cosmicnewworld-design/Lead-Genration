#!/bin/bash
# Setup & Installation Script for Lead Generation SaaS
# Usage: bash setup.sh

set -e

echo "🚀 Lead Generation SaaS - Setup Script"
echo "======================================="

# Detect OS
if [[ "$OSTYPE" == "darwin"* ]]; then
    OS="macOS"
elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
    OS="Linux"
elif [[ "$OSTYPE" == "msys" ]] || [[ "$OSTYPE" == "win32" ]]; then
    OS="Windows (Git Bash)"
else
    OS="Unknown"
fi

echo "Detected OS: $OS"

# Check PHP
echo ""
echo "📦 Checking dependencies..."

if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP 8.2+ first."
    exit 1
fi
echo "✅ PHP: $(php -v | head -n 1)"

if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found. Please install Composer first."
    exit 1
fi
echo "✅ Composer: $(composer --version)"

if ! command -v node &> /dev/null; then
    echo "❌ Node.js not found. Please install Node.js first."
    exit 1
fi
echo "✅ Node.js: $(node -v)"

if ! command -v npm &> /dev/null; then
    echo "❌ npm not found. Please install npm first."
    exit 1
fi
echo "✅ npm: $(npm -v)"

# Create .env if not exists
echo ""
echo "🔧 Setting up configuration..."

if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo "✅ .env created. Update with your Firebase credentials."
else
    echo "✅ .env already exists."
fi

# Create SQLite database if not exists
if [ ! -d database ]; then
    mkdir -p database
fi

if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
    echo "✅ database/database.sqlite created."
fi

# Generate app key
echo ""
echo "🔑 Generating application key..."
php artisan key:generate

# Install PHP dependencies
echo ""
echo "📥 Installing PHP dependencies..."
composer install

# Install Firebase SDK
echo ""
echo "🔥 Installing Firebase SDK..."
composer require kreait/firebase-php

# Install Node dependencies
echo ""
echo "📥 Installing Node dependencies..."
npm ci

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Create storage/firebase-key.json placeholder
mkdir -p storage
if [ ! -f storage/firebase-key.json ]; then
    cat > storage/firebase-key.json << 'EOF'
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
EOF
    echo "ℹ️  Created placeholder storage/firebase-key.json"
    echo "⚠️  Update it with your actual Firebase credentials!"
fi

# Build frontend
echo ""
echo "🎨 Building frontend..."
npm run build

# Final summary
echo ""
echo "✅ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Update .env with your Firebase credentials"
echo "2. Update storage/firebase-key.json with your Firebase service account key"
echo "3. (Optional) Run seeders: php artisan migrate:refresh --seed"
echo "4. Start development server: php artisan serve"
echo "5. In another terminal, run: npm run dev"
echo ""
echo "Happy coding! 🚀"
