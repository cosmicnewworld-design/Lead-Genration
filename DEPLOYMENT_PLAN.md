# Deployment Plan: Laravel + Google Cloud + Firebase + Firestore

## Overview
This plan covers:
1. Installing PHP on Windows (local development)
2. Setting up Firebase project
3. Configuring Firestore as database
4. Deploying Laravel app to Google Cloud Run

## Step 1: Install PHP on Windows

### Option A: Using PHP Installer (Recommended)
1. Download PHP from https://windows.php.net/download/
2. Choose PHP 8.3 (Thread Safe) for development
3. Extract to `C:\php`
4. Add to PATH: System Properties > Environment Variables > Path > Add `C:\php`
5. Verify: `php --version`

### Option B: Using XAMPP/WAMP
- Download XAMPP (includes PHP, Apache, MySQL)
- https://www.apachefriends.org/

### Option C: Using Chocolatey
```
powershell
choco install php -y
```

## Step 2: Install Composer

Download from https://getcomposer.org/download/
Or via Chocolatey:
```
powershell
choco install composer -y
```

## Step 3: Install Project Dependencies

```
bash
cd Lead-Genration
composer install
npm install
```

## Step 4: Firebase Setup

### Initialize Firebase
```
bash
cd Lead-Genration
firebase login
firebase init
```

Select:
- Firestore
- Hosting
- Functions (optional for backend)

### Configure firebase.json for Laravel
Update firebase.json to work with Laravel:

```
json
{
  "hosting": {
    "public": "public",
    "ignore": ["firebase.json", "**/.*", "**/node_modules/**"],
    "rewrites": [
      {
        "source": "**",
        "function": "laravel"
      }
    ],
    "functions": {
      "source": "functions"
    }
  },
  "firestore": {
    "rules": "firestore.rules",
    "indexes": "firestore.indexes.json"
  }
}
```

## Step 5: Configure Firestore in Laravel

### Install Firebase Admin SDK
```
bash
composer require kreait/laravel-firebase
```

### Publish config
```
bash
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider"
```

### Configure .env
```
env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_PRIVATE_KEY=your-private-key
FIREBASE_CLIENT_EMAIL=your-client-email
```

## Step 6: Deploy to Google Cloud Run

### Prerequisites
1. Install Google Cloud SDK
2. Enable Cloud Run API
3. Enable Firestore API

### Build and Deploy
```
bash
# Build Docker image
gcloud builds submit --tag gcr.io/PROJECT_ID/laravel-app

# Deploy to Cloud Run
gcloud run deploy laravel-app \
  --image gcr.io/PROJECT_ID/laravel-app \
  --platform managed \
  --region us-central1 \
  --allow-unauthenticated
```

## Step 7: Configure Environment Variables

Set these in Cloud Run:
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://your-app.run.app
- DB_CONNECTION=mysql (or use Firestore)
- FIREBASE_PROJECT_ID=your-project

## Notes

### For Firestore Integration
Since Laravel is designed for SQL databases, integrating Firestore requires:
1. Using Firebase Admin SDK directly for Firestore operations
2. Creating custom model classes for Firestore collections
3. Or using a hybrid approach: MySQL for main data, Firestore for specific features

### Recommended Architecture
- **Cloud Run**: Laravel application
- **Cloud SQL**: MySQL/PostgreSQL (primary database)
- **Firestore**: For real-time features, user profiles, or caching
- **Firebase Auth**: User authentication
- **Firebase Hosting**: Static assets CDN

## Current Status
- [ ] PHP installation on Windows
- [ ] Composer installation
- [ ] Project dependencies
- [ ] Firebase project setup
- [ ] Firestore configuration
- [ ] Google Cloud deployment
