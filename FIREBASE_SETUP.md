# Firebase & Firestore Integration Guide

This guide helps you set up Firebase and Firestore for the Lead Generation SaaS platform.

## Step 1: Install PHP & Composer

### Windows (Manual)
1. Download PHP 8.3 from https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to Windows PATH
4. Download Composer installer from https://getcomposer.org and run it

### Linux/macOS
```bash
# Install PHP
brew install php php-composer  # macOS
# OR
sudo apt-get install php composer  # Ubuntu/Debian
```

## Step 2: Install Firebase SDK for PHP

Run in project root:

```bash
composer require kreait/firebase-php
```

This installs the official Firebase Admin SDK for PHP.

## Step 3: Set Up Firebase Project

1. Go to https://console.firebase.google.com
2. Create a new project
3. Enable Firestore Database
4. Download the Service Account JSON key:
   - Go to Project Settings → Service Accounts
   - Click "Generate New Private Key"
   - Save as `storage/firebase-key.json` (or environment path)

## Step 4: Configure Environment Variables

Update `.env`:

```env
# Firebase Configuration
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
FIREBASE_CREDENTIALS_PATH=storage/firebase-key.json

# Disable PostgreSQL (optional, for Firestore-only setup)
# DB_CONNECTION=firebase

# Or keep PostgreSQL for hybrid setup:
DB_CONNECTION=pgsql
```

## Step 5: Create Firebase Service Provider

Create `app/Providers/FirebaseServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase', function () {
            $factory = (new Factory)->withServiceAccount(
                env('FIREBASE_CREDENTIALS_PATH')
            );
            
            return $factory->create();
        });

        $this->app->alias('firebase', \Kreait\Firebase\Contract\Database::class);
    }

    public function boot()
    {
        //
    }
}
```

Register in `config/app.php` providers array:

```php
'providers' => [
    // ...
    App\Providers\FirebaseServiceProvider::class,
],
```

## Step 6: Create Firestore Model Trait

Create `app/Traits/FirestoreModel.php`:

```php
<?php

namespace App\Traits;

trait FirestoreModel
{
    protected $firestore = null;
    protected $collection = null;

    public function __construct()
    {
        parent::__construct();
        $this->firestore = app('firebase')->firestore();
    }

    public function saveToFirestore($data)
    {
        $docRef = $this->firestore
            ->collection($this->collection)
            ->document($this->id);
        
        $docRef->set($data + ['updated_at' => now()]);
        return $this;
    }

    public function getFromFirestore()
    {
        return $this->firestore
            ->collection($this->collection)
            ->document($this->id)
            ->snapshot()
            ->data();
    }
}
```

## Step 7: Update Models to Use Firestore (Optional)

If using Firestore alongside PostgreSQL:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FirestoreModel;

class Lead extends Model
{
    use FirestoreModel;

    protected $collection = 'leads';

    // Example: Sync to Firestore on save
    protected static function booted()
    {
        static::saved(function ($lead) {
            $lead->saveToFirestore($lead->toArray());
        });
    }
}
```

## Step 8: Install Node & Build Frontend

```bash
npm ci
npm run dev  # or npm run build for production
```

## Step 9: Run with Docker (Recommended)

Build and start:

```bash
docker-compose up -d
```

Or manually run:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Visit: http://localhost:8000

## Step 10: Migration Scripts

If migrating from PostgreSQL to Firestore, create a command:

```bash
php artisan make:command MigrateLeadsToFirestore
```

See `app/Console/Commands/MigrateLeadsToFirestore.php` for example.

## Firestore Limitations & Workarounds

| Feature | PostgreSQL | Firestore | Solution |
|---------|-----------|-----------|----------|
| Complex Queries | ✅ Full SQL | ⚠️ Limited | Use Cloud Functions for aggregations |
| Transactions | ✅ ACID | ✅ ACID | Native support |
| Indexes | Auto | Manual | Create in Firebase Console |
| Real-time Sync | Polling | ✅ Native | Use Firestore listeners |

## Troubleshooting

### "Service Account JSON not found"
Ensure `storage/firebase-key.json` exists and path is correct in `.env`

### "Firestore: Permission denied"
- Check Firestore security rules in Firebase Console
- Ensure service account has sufficient permissions

### "Cannot locate Kreait package"
```bash
composer install
composer dump-autoload
```

## References

- [Kreait Firebase PHP SDK](https://github.com/kreait/firebase-php)
- [Firebase Admin SDK](https://firebase.google.com/docs/admin/setup)
- [Firestore Documentation](https://firebase.google.com/docs/firestore)
- [Laravel Configuration](https://laravel.com/docs/11/configuration)

---

**Next Steps:**
1. Install PHP & Composer
2. Run `composer require kreait/firebase-php`
3. Set up Firebase project & download credentials
4. Configure `.env` with Firebase credentials
5. Deploy!
