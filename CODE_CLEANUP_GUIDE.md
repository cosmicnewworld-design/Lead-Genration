# Code Cleanup & Optimization Guide

This guide documents unused/test code that can be safely removed or refactored.

## 1. Test & Demo Commands

### Files to Review/Remove

#### `app/Console/Commands/TestApolloService.php` ✅ CLEANED
- **Status**: ✅ Fixed (removed `print_r()`, replaced with JSON output)
- **Purpose**: Testing ApolloService integration
- **Action**: Keep if using Apollo.io API; remove if not needed
- **Command**: `php artisan apollo:test`

#### `app/Console/Commands/TestLeadAutomation.php`
- **Status**: 🔍 Review needed
- **Purpose**: Test lead automation workflows
- **Action**: Check if actively used; remove if demo-only

### Decision Matrix

| Command | Keep? | If Removing |
|---------|-------|------------|
| `TestApolloService` | ✅ If using Apollo.io | Delete file |
| `TestLeadAutomation` | 🔍 Review logs | Delete file |
| `ScoreLeads` | ✅ Keep (background job) | - |
| `SendFollowUps` | ✅ Keep (background job) | - |

**To remove a command:**
```bash
rm app/Console/Commands/TestApolloService.php
# Update Kernel.php if command was registered
```

## 2. Factories & Seeders

### Review These

**`database/factories/LeadFactory.php`**
- Purpose: Generate fake leads for testing
- Keep if: Running automated tests, need demo data
- Remove if: Production-only, no test suite

**`database/seeders/DemoTenantSeeder.php`**
- Purpose: Create demo tenant with sample data
- Keep if: Want demo data on fresh install
- Remove if: Production deployment, skip seeding

**`database/seeders/LeadSeeder.php`**
- Purpose: Seed demo leads into database
- Keep if: Testing lead features
- Remove if: Using real data only

### Recommended Action

```bash
# Keep essential seeders
Keep: database/seeders/RolesAndPermissionsSeeder.php
Keep: database/seeders/PlanSeeder.php

# Review & conditionally remove
Review: database/seeders/DemoTenantSeeder.php
Review: database/seeders/LeadSeeder.php
```

## 3. Services to Verify

### Check These Services for Unused Code

#### `app/Services/ApolloService.php`
If not using Apollo.io integration:
```bash
rm app/Services/ApolloService.php
# Also remove command: TestApolloService.php
```

#### `app/Services/ScraperService.php`
If not web scraping:
```bash
# Review usage first
grep -r "ScraperService" app/
```

#### `app/Services/AnalyticsService.php`
If not using custom analytics:
```bash
# Check if used in Controllers
grep -r "AnalyticsService" app/Http/Controllers
```

## 4. Migration Cleanup

### Pending Migrations to Review

All migrations appear current. Check:
```bash
php artisan migrate:status
```

If you see "Pending" migrations:
```bash
php artisan migrate --pending
```

## 5. Dependencies to Audit

### Large/Unused Packages (npm)

```bash
npm audit
npm obsolete
```

Common removals:
- Old/unused UI frameworks
- Duplicate dependencies
- Debug-only packages (remove from production)

### PHP Composer Audit

```bash
composer audit
composer outdated
```

## 6. Code Standards Fixes

### Run PHP Linter

```bash
# If Laravel Pint installed
php artisan pint

# Or manual check
php -l app/Console/Commands/*.php
```

### Fix Issues Found

1. **Unused imports** - Laravel IDE helpers will flag
2. **Deprecated functions** - Check with `grep`
3. **Code style** - Use Pint or PHP-CS-Fixer

## 7. Database Optimization

### Remove Unused Columns

If migrated to Firestore, some columns might be obsolete:

```php
// Example: Create migration to drop old columns
Schema::table('leads', function (Blueprint $table) {
    // Only if moving fully to Firestore:
    // $table->dropColumn(['enrichment_data', 'custom_fields']);
});
```

**⚠️ Caution**: Only drop columns after Firestore migration is verified!

## 8. Configuration Cleanup

### `.env.example` Verification

Ensure all ENV vars match project:

```bash
# Check for unused vars
grep -r "$_ENV\|getenv\|env(" app/ | grep -oP "env\('\K[^']*" | sort -u
```

Remove from `.env.example` if not used.

## 9. Recommended Cleanup Checklist

- [ ] Remove demo commands if not needed (`TestApolloService`, etc.)
- [ ] Review `DemoTenantSeeder` before production deploy
- [ ] Audit large npm packages (`npm audit`)
- [ ] Audit PHP packages (`composer audit`)
- [ ] Run code linter (`php artisan pint`)
- [ ] Test with cleaned code (`php artisan test`)
- [ ] Remove unused service files
- [ ] Optimize database migrations

## 10. Safety: Before & After Testing

### Create a Backup Branch
```bash
git checkout -b cleanup/remove-test-commands
# Make deletions
# Test thoroughly
# If all good: git push origin cleanup/remove-test-commands
# Create PR for review
```

### Verify No Broken Imports

```bash
# After deletions, run linter
php artisan pint

# Run tests
php artisan test

# Check for undefined references
grep -r "use.*TestApolloService" app/
```

## Migration to Firestore

When fully migrating to Firestore, also clean:

1. Remove PostgreSQL/MySQL specific code
2. Update migrations to Firestore schema
3. Remove SQL-specific queries
4. Update model relationships (if needed)

See `FIREBASE_SETUP.md` for complete migration guide.

---

**Last Updated**: 2025-02-17  
**Status**: Ready for cleanup  
**Next**: Execute removals after testing
