# 🐳 Docker Setup Guide - Lead Generation SaaS

**Status**: Docker is easiest, but requires manual installer on Windows.

---

## ⚡ Quick Start (3 Steps)

### Step 1: Download & Install Docker Desktop

**Windows**: 
1. Download: https://docker.com/products/docker-desktop
2. Run installer (will require restart)
3. Restart your computer when prompted
4. Wait for Docker Desktop to start (may take 2-3 min after restart)

**Verify installation** (in PowerShell):
```powershell
docker --version
docker-compose --version
```

Should show versions like:
```
Docker version 24.0.6
Docker Compose version v2.26.1
```

---

### Step 2: Start Docker Containers

Once Docker is verified working:

```powershell
cd "c:\Users\SPL2\Desktop\lead software\Lead-Genration"
docker-compose up -d
```

Wait 30-60 seconds for all containers to start.

---

### Step 3: Verify Containers Are Running

```powershell
docker-compose ps
```

Should show:
```
NAME      IMAGE              STATUS
app       lead-saas:latest   Up 2 minutes
web       nginx:1.27-alpine  Up 2 minutes
db        postgres:16        Up 2 minutes
redis     redis:7-alpine     Up 2 minutes
```

---

## 🌐 Access the Application

Once running:

| Component | URL | Credentials |
|-----------|-----|-------------|
| **Web Application** | http://localhost:8080 | N/A |
| **Laravel API** | http://localhost:8080/api | Check `.env` |
| **Database** | localhost:5432 | lead_saas / lead_saas |
| **Redis** | localhost:6379 | None |

---

## 📋 Useful Docker Commands

### View Logs

```bash
# All containers
docker-compose logs -f

# Just Laravel app
docker-compose logs -f app

# Just Nginx web server
docker-compose logs -f web

# Just Database
docker-compose logs -f db
```

### Execute Commands in Container

```bash
# Run Laravel command (e.g., migrations)
docker-compose exec app php artisan migrate

# Run Laravel Tinker
docker-compose exec app php artisan tinker

# Access container shell (bash)
docker-compose exec app bash

# Run npm command
docker-compose exec app npm run build
```

### Manage Containers

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# Remove containers & volumes (CAREFUL - deletes data!)
docker-compose down -v

# View resource usage
docker stats

# View network info
docker network inspect lead-genration_default
```

---

## 🔧 Setup in Docker

### Automatic Setup (Run Once)

When Docker starts for first time, run inside the container:

```bash
docker-compose exec app bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed
exit
```

Or one-liner:
```bash
docker-compose exec app sh -c "php artisan key:generate && php artisan migrate --force && php artisan db:seed"
```

---

## 🔐 Firebase Setup in Docker

### Step 1: Get Firebase Credentials

1. Go to https://console.firebase.google.com
2. Create new project
3. Go to: Project Settings → Service Accounts
4. Click "Generate New Private Key"
5. Save JSON file

### Step 2: Add Credentials to Container Volume

Copy Firebase JSON to:
```
storage/firebase-key.json
```

The `storage/` folder is mounted in the container.

### Step 3: Update .env in Container

Edit `.env` (at project root):
```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_CREDENTIALS_PATH=/var/www/html/storage/firebase-key.json
APP_KEY=base64:xxxxx  # Will be auto-generated
```

### Step 4: Verify Firebase Connection

```bash
docker-compose exec app php artisan tinker
app('firebase')->getDatabase()->getRootReference()->getSnapshot()->val()
# Should return data or null (success)
exit
```

---

## 🚨 Troubleshooting

### "Docker daemon is not running"

**Windows:**
1. Open Docker Desktop app from Start menu
2. Wait for it to fully start (status: "Engine running")
3. Try again: `docker-compose ps`

### "Cannot connect to Docker daemon"

**Solution:**
```powershell
# Restart Docker service
Restart-Service -Name com.docker.service -Force
# Or manually restart Docker Desktop app
```

### "Ports already in use"

```bash
# Check what's using ports
netstat -ano | findstr :8080

# Change docker-compose.yml ports if needed:
# Change from 8080:80 to 8081:80 (then access via localhost:8081)
```

### "Permission denied" errors

**Solution**: Run PowerShell as Administrator

### "Disk space" issues

```bash
# Clean up unused Docker data
docker system prune -a

# Check disk usage
docker system df
```

### Container exits immediately

```bash
# View full logs
docker-compose logs app

# Common: missing extensions in Dockerfile
# Check Dockerfile for all required PHP extensions
```

---

## 📊 Understanding Docker Compose Setup

Your `docker-compose.yml` runs:

```yaml
Services:
  app:        Laravel application (PHP 8.3-FPM)
  web:        Nginx webserver (port 8080)
  db:         PostgreSQL database (port 5432)
  redis:      Redis cache (port 6379)
```

**Data flows:**
```
Browser → Nginx (port 8080) → PHP-FPM (app) → PostgreSQL (db)
                                            → Redis (cache)
```

---

## 🔄 Database Management in Docker

### Access PostgreSQL Directly

```bash
# Connect with psql
docker-compose exec db psql -U lead_saas -d lead_saas

# Run SQL command
docker-compose exec db psql -U lead_saas -d lead_saas -c "SELECT * FROM users LIMIT 5;"
```

### Backup Database

```bash
docker-compose exec db pg_dump -U lead_saas lead_saas > backup.sql
```

### Restore Database

```bash
docker-compose exec -T db psql -U lead_saas lead_saas < backup.sql
```

### Reset Database (Development Only)

```bash
docker-compose exec app php artisan migrate:refresh --seed
```

---

## 📁 File Mapping (Docker Volumes)

Your local files sync with container:

| Local Path | Container Path | Purpose |
|-----------|----------------|---------|
| `.` (root) | `/var/www/html` | App source code |
| `storage/` | `/var/www/html/storage` | Uploads, logs, cache |
| `database/` | `/var/www/html/database` | Migrations, seeds |

**Changes you make locally are visible in container instantly!**

---

## 🎯 Development Workflow

### Daily Workflow

```bash
# 1. Start Docker
docker-compose up -d

# 2. Check logs
docker-compose logs -f app

# 3. Make code changes (in your IDE, files auto-sync)

# 4. Run migrations if needed
docker-compose exec app php artisan migrate

# 5. Run tests
docker-compose exec app php artisan test

# 6. When done
docker-compose down
```

### First Time Setup

```bash
# 1. Start
docker-compose up -d

# 2. Generate key
docker-compose exec app php artisan key:generate

# 3. Run migrations
docker-compose exec app php artisan migrate --force

# 4. Seed demo data (optional)
docker-compose exec app php artisan db:seed

# 5. Access app
# Open http://localhost:8080
```

---

## 🚀 Deploy with Docker

### Production Deployment

The `Dockerfile` builds a production image with:
- PHP 8.3-FPM
- Node 20 (for frontend build)
- Composer dependencies compiled
- Frontend assets built
- No dev dependencies

```bash
# Build production image
docker build -t lead-saas:prod .

# Run in production
docker run -d \
  -p 8080:80 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e DB_HOST=your-db-host \
  lead-saas:prod
```

---

## 📞 Getting Help

If Docker fails:

1. **Check docker-compose version**: `docker-compose --version`
2. **View all logs**: `docker-compose logs`
3. **Rebuild images**: `docker-compose build --no-cache`
4. **Fresh start**: `docker-compose down -v && docker-compose up -d`
5. **Check Docker Desktop status**: Should show "Engine running"

---

## ✅ Verification Checklist

- [ ] Docker Desktop installed & running
- [ ] `docker --version` shows version
- [ ] `docker-compose ps` shows 4 containers
- [ ] All containers show "Up X minutes"
- [ ] `http://localhost:8080` loads
- [ ] Laravel logs show no errors
- [ ] Database has tables
- [ ] `.env` has APP_KEY

---

## 🎉 Success!

When `docker-compose ps` shows:

```
NAME      IMAGE              STATUS
app       lead-saas          Up 2 minutes (healthy)
web       nginx:1.27-alpine  Up 2 minutes
db        postgres:16-alpine Up 2 minutes
redis     redis:7-alpine     Up 2 minutes
```

**You're ready to develop!** 🚀

Access at: `http://localhost:8080`

---

**Next Steps:**
1. Download & install Docker Desktop
2. Restart computer
3. Run `docker-compose up -d`
4. Open http://localhost:8080
5. Check logs: `docker-compose logs -f app`

**Total time: ~15 minutes (mostly waiting for Docker)**
