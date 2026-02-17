# Cloud Deploy (PHP install included via Docker)

Ye repo ab **Docker-ready** hai. Cloud par PHP/nginx/node install karne ki zarurat nahi—image ke andar sab aa jayega.

## Option A (recommended): Any cloud that supports Docker

### 1) APP_KEY set karo

Laravel ko `APP_KEY` chahiye. Local machine par aap yeh generate karke cloud env me paste kar sakte ho:

```bash
php artisan key:generate --show
```

Cloud provider me env var set karo:
- `APP_KEY=base64:....`

### 2) Environment variables (minimum)

- `APP_URL`
- `APP_KEY`
- `DB_CONNECTION` (`pgsql` ya `mysql`)
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `REDIS_HOST`, `REDIS_PORT` (optional but recommended)
- Stripe/OAuth keys (agar billing/email use karna hai)

### 3) Build + Run

If you are running on a VM (Ubuntu/Debian):

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force
```

App URL:
- `http://SERVER_IP:8080` (compose me web port 8080 hai)

## Option B: Traditional VPS (without Docker)

Ubuntu 22.04/24.04 par yeh packages install karo:
- Nginx
- PHP 8.2/8.3 + extensions: `mbstring, xml, curl, zip, bcmath, intl, gd, pgsql/mysql, redis`
- Composer
- Node 20 (for `npm run build`)
- Supervisor (queue workers)

Then:

```bash
composer install --no-dev
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Queue + Scheduler (production)

- Queue worker:
  - `php artisan queue:work --sleep=1 --tries=3`
  - best with Supervisor

- Scheduler:
  - cron: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

