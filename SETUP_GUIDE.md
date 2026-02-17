# 🚀 Advanced SaaS Lead Generation Platform - Setup Guide

## Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Redis (optional, for caching and queues)

### Step 1: Clone and Install

```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install
```

### Step 2: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Environment Variables

Edit `.env` file with your configuration:

```env
APP_NAME="Lead Generation SaaS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lead_generation
DB_USERNAME=root
DB_PASSWORD=

# Stripe Configuration (for billing)
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Google OAuth (for Gmail integration)
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Microsoft OAuth (for Outlook integration)
MICROSOFT_CLIENT_ID=your_client_id
MICROSOFT_CLIENT_SECRET=your_client_secret
MICROSOFT_REDIRECT_URI=http://localhost:8000/auth/microsoft/callback

# Queue Configuration
QUEUE_CONNECTION=database
```

### Step 4: Database Setup

```bash
# Run migrations
php artisan migrate

# Install Cashier migrations
php artisan cashier:install

# Seed database (optional)
php artisan db:seed
```

### Step 5: Start Development Servers

```bash
# Terminal 1: Laravel development server
php artisan serve

# Terminal 2: Vite development server
npm run dev

# Terminal 3: Queue worker (for background jobs)
php artisan queue:work
```

## 🎯 Key Features Available

### ✅ Implemented Features

1. **Multi-Tenant System**
   - Complete tenant isolation
   - Subdomain-based routing
   - Centralized tenant management

2. **Subscription & Billing**
   - Stripe integration via Laravel Cashier
   - Multiple subscription tiers
   - Usage-based limits
   - Webhook handling

3. **Advanced Lead Management**
   - Comprehensive lead fields
   - Lead scoring system
   - Pipeline stages
   - Tagging system
   - Custom fields

4. **Campaign Automation**
   - Multi-step email campaigns
   - Scheduling and delays
   - Open/click/reply tracking
   - A/B testing support

5. **Email Integration**
   - Gmail OAuth
   - Outlook OAuth
   - Multiple email accounts
   - Rate limiting

6. **Lead Enrichment**
   - Email verification
   - Company data enrichment
   - Website scraping
   - Bulk enrichment

7. **Analytics & Reporting**
   - Dashboard analytics
   - Campaign performance
   - Lead metrics
   - Conversion tracking

8. **API & Webhooks**
   - RESTful API v1
   - Webhook support
   - Sanctum authentication

## 📝 Database Migrations

All migrations are included. Run them in order:

```bash
php artisan migrate
```

Key migrations:
- `2026_02_17_000001_enhance_leads_table.php` - Enhanced lead fields
- `2026_02_17_000002_create_plans_table.php` - Subscription plans
- `2026_02_17_000003_create_pipeline_stages_table.php` - Pipeline stages
- `2026_02_17_000004_create_connected_emails_table.php` - Email accounts
- `2026_02_17_000005_create_lead_tags_table.php` - Tagging system
- `2026_02_17_000006_enhance_campaigns_table.php` - Campaign enhancements
- `2026_02_17_000007_enhance_campaign_steps_table.php` - Campaign steps
- `2026_02_17_000008_enhance_campaign_leads_pivot_table.php` - Campaign tracking

## 🔧 Configuration Files

### Stripe Setup

1. Create Stripe account at https://stripe.com
2. Get API keys from dashboard
3. Set up webhook endpoint: `https://yourdomain.com/stripe/webhook`
4. Add webhook secret to `.env`

### Google OAuth Setup

1. Go to Google Cloud Console
2. Create OAuth 2.0 credentials
3. Add authorized redirect URI
4. Copy client ID and secret to `.env`

### Microsoft OAuth Setup

1. Go to Azure Portal
2. Register application
3. Add redirect URI
4. Copy client ID and secret to `.env`

## 🐛 Troubleshooting

### Migration Errors

If you encounter migration errors:

```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Queue Not Working

Ensure queue worker is running:

```bash
php artisan queue:work
```

For production, use Supervisor to keep it running.

### API Authentication Issues

Generate Sanctum token:

```bash
php artisan sanctum:install
php artisan migrate
```

## 📚 API Documentation

### Authentication

All API endpoints require Sanctum authentication:

```bash
POST /api/login
{
    "email": "user@example.com",
    "password": "password"
}

# Response includes token
{
    "token": "1|..."
}
```

### Example API Calls

```bash
# Get leads
GET /api/v1/leads
Authorization: Bearer {token}

# Create lead
POST /api/v1/leads
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "company": "Acme Corp"
}
```

## 🚀 Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set up queue workers (Supervisor)
6. Configure webhook endpoints
7. Set up SSL certificates
8. Configure database backups

## 📞 Support

For issues or questions, check:
- `ADVANCED_FEATURES.md` - Feature documentation
- `README.md` - General project info
- Laravel documentation: https://laravel.com/docs
