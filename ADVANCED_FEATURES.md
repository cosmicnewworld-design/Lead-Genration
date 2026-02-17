# Advanced SaaS Lead Generation Platform - Feature Documentation

## 🚀 Overview

This is a fully advanced, production-ready SaaS Lead Generation Platform built with Laravel 10, featuring multi-tenancy, subscription billing, advanced lead management, campaign automation, and comprehensive analytics.

## ✨ Key Features Implemented

### 1. **Multi-Tenant Architecture**
- Complete tenant isolation
- Subdomain-based tenant identification
- Separate databases per tenant (optional)
- Centralized tenant management

### 2. **Subscription & Billing System**
- ✅ Laravel Cashier integration with Stripe
- ✅ Multiple subscription tiers (Free, Starter, Pro, Enterprise)
- ✅ Usage-based limits (leads, campaigns, emails, team members)
- ✅ Secure checkout and payment processing
- ✅ Subscription management (upgrade, downgrade, cancel, resume)
- ✅ Stripe webhook handling for payment events
- ✅ Billing portal integration

### 3. **Advanced Lead Management**
- ✅ Comprehensive lead fields (name, email, phone, company, job title, website, notes)
- ✅ Lead scoring system with customizable rules
- ✅ Pipeline stages for lead tracking
- ✅ Lead tagging system
- ✅ Custom fields support (JSON)
- ✅ Lead assignment to team members
- ✅ Lead enrichment data storage
- ✅ Lead source tracking
- ✅ Qualification status tracking
- ✅ Advanced filtering and search

### 4. **Campaign Automation System**
- ✅ Multi-step email campaigns
- ✅ Campaign scheduling (days, hours, minutes delays)
- ✅ Email open/click/reply tracking
- ✅ A/B testing support
- ✅ Campaign status management (draft, active, paused, completed)
- ✅ Automatic follow-up sequences
- ✅ Stop on reply functionality
- ✅ Campaign analytics and performance metrics

### 5. **Email Integration**
- ✅ Gmail OAuth integration
- ✅ Outlook/Microsoft 365 OAuth integration
- ✅ Multiple email account support per tenant
- ✅ Token refresh handling
- ✅ Rate limiting and daily send limits
- ✅ Email sending via connected accounts

### 6. **Lead Enrichment**
- ✅ Email verification (MX record checking)
- ✅ Company data enrichment
- ✅ Website scraping for contact information
- ✅ LinkedIn profile finding (placeholder for API integration)
- ✅ Bulk enrichment support
- ✅ Caching for performance

### 7. **Analytics & Reporting**
- ✅ Dashboard analytics
- ✅ Lead analytics (by status, source, score)
- ✅ Campaign performance metrics
- ✅ Conversion tracking
- ✅ Lead score distribution
- ✅ Recent activity tracking
- ✅ Open rate, click rate, reply rate calculations

### 8. **API & Webhooks**
- ✅ RESTful API v1 endpoints
- ✅ Lead CRUD operations via API
- ✅ Webhook support for external integrations
- ✅ Event handling (lead.created, lead.updated, email.replied, etc.)
- ✅ Sanctum authentication for API

### 9. **Security & Error Handling**
- ✅ Comprehensive error handling middleware
- ✅ API error responses
- ✅ Validation error handling
- ✅ Model not found handling
- ✅ Logging for debugging
- ✅ CSRF protection
- ✅ Rate limiting support

## 📁 Database Structure

### Core Tables
- `tenants` - Multi-tenant organization data
- `users` - User accounts with Cashier integration
- `plans` - Subscription plans with features
- `leads` - Enhanced lead data with scoring and enrichment
- `campaigns` - Email campaign definitions
- `campaign_steps` - Multi-step campaign sequences
- `pipeline_stages` - Lead pipeline stages
- `tags` - Lead tagging system
- `connected_emails` - OAuth email account connections
- `scoring_rules` - Lead scoring rules

## 🔧 Setup Instructions

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Configuration

Copy `.env.example` to `.env` and configure:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lead_generation
DB_USERNAME=root
DB_PASSWORD=

# Stripe (for billing)
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
STRIPE_WEBHOOK_SECRET=your_webhook_secret

# Google OAuth (for Gmail integration)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Microsoft OAuth (for Outlook integration)
MICROSOFT_CLIENT_ID=your_microsoft_client_id
MICROSOFT_CLIENT_SECRET=your_microsoft_client_secret
MICROSOFT_REDIRECT_URI=http://localhost:8000/auth/microsoft/callback

# Queue (for background jobs)
QUEUE_CONNECTION=database
```

### 3. Run Migrations

```bash
php artisan migrate
php artisan cashier:install
```

### 4. Seed Database (Optional)

```bash
php artisan db:seed
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Start Development Servers

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Queue worker (for background jobs)
php artisan queue:work
```

## 📊 Usage Examples

### Creating a Lead via API

```bash
POST /api/v1/leads
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "company": "Acme Corp",
    "phone": "+1234567890",
    "source": "website"
}
```

### Creating a Campaign

1. Navigate to Campaigns section
2. Click "Create Campaign"
3. Add campaign details
4. Add campaign steps with delays
5. Assign leads to campaign
6. Start campaign

### Connecting Email Account

1. Navigate to Settings > Email Accounts
2. Click "Connect Gmail" or "Connect Outlook"
3. Complete OAuth flow
4. Email account is now available for campaigns

### Webhook Integration

```bash
POST /api/v1/webhooks/{tenant_id}
Content-Type: application/json

{
    "event": "lead.created",
    "data": {
        "name": "Jane Doe",
        "email": "jane@example.com",
        "company": "Tech Corp"
    }
}
```

## 🔐 Security Features

- CSRF protection on all forms
- API authentication via Sanctum
- Encrypted OAuth tokens
- Rate limiting support
- SQL injection protection (Eloquent ORM)
- XSS protection (Blade templating)
- Secure password hashing

## 🚀 Production Deployment

### Requirements
- PHP 8.1+
- MySQL 8.0+ or PostgreSQL 13+
- Redis (for caching and queues)
- Composer
- Node.js & NPM

### Deployment Steps

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Set up queue workers (Supervisor recommended)
7. Set up cron for scheduled tasks
8. Configure webhook endpoint in Stripe dashboard

## 📈 Performance Optimizations

- Database indexing on frequently queried fields
- Caching for enrichment data
- Queue-based background processing
- Eager loading for relationships
- API response pagination

## 🐛 Troubleshooting

### Common Issues

1. **Stripe webhook not working**
   - Verify webhook secret in `.env`
   - Check webhook endpoint URL in Stripe dashboard
   - Ensure CSRF exemption for webhook route

2. **Email sending fails**
   - Check OAuth token expiration
   - Verify email account is active
   - Check daily send limits

3. **Queue jobs not processing**
   - Ensure queue worker is running
   - Check queue connection in `.env`
   - Verify database queue table exists

## 📝 License

This project is proprietary software. All rights reserved.

## 🤝 Support

For support, please contact the development team or create an issue in the project repository.
