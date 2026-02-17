# 🛡️ Security Incident Response Checklist

## ⚠️ Exposed Secret Detected
**Alert Date**: 2026-02-18  
**Detection Source**: GitGuardian  
**Exposed Secret Type**: GitHub Personal Access Token (PAT)  
**Commit**: fec0569

---

## ✅ Immediate Actions Taken

### 1. Secret Exposure
- [x] Token has been detected and flagged
- [x] Assume token is compromised
- [x] Token must be revoked immediately

### 2. Repository Security
- [x] Improved `.gitignore` with secret patterns
- [x] Added pre-commit hook to prevent future secret commits
- [x] Git history will be cleaned automatically

### 3. Documentation
- [x] Created this security response guide
- [x] Documented secure credential handling practices

---

## 🚨 CRITICAL: Actions You Must Take Now

### Step 1: Revoke Exposed Token (DO THIS FIRST!)
1. Go to: https://github.com/settings/tokens
2. Find the token you exposed (starts with `ghp_`)
3. Click **Delete** to revoke it immediately
4. ⚠️ This token is now **COMPROMISED** and must be considered fully exposed

### Step 2: Create New PAT
1. Go to: https://github.com/settings/tokens/new
2. Fill in details:
   - **Token name**: `Lead-Genration-Push-<date>`
   - **Expiration**: 90 days (or shorter)
   - **Select scopes**:
     - ✅ `repo` (full control of repositories)
     - ✅ `read:org` (read organization info)
3. Click **Generate token**
4. **COPY immediately** and store securely
5. Do NOT commit this token anywhere!

### Step 3: Update Local Credentials
In PowerShell:
```powershell
# Clear old credentials
git credential reject
git credential-manager-core erase

# Auth will now use your new token when you push
git push -u origin main
# When prompted for password, paste your NEW PAT
```

### Step 4: Enable 2FA on GitHub (Recommended)
1. Go to: https://github.com/settings/security
2. Enable **Two-Factor Authentication**
3. This prevents unauthorized access even if credentials leak

---

## 🔒 Going Forward: Best Practices

### 1. Never Commit Secrets
✅ **DO THIS:**
```bash
# Use .env files (gitignored)
DB_PASSWORD=actual_password
API_TOKEN=real_token
```

❌ **NEVER DO THIS:**
```bash
# Hardcoded in source files
const API_KEY = "ghp_...";
password: "mypassword123";
```

### 2. Use Environment Variables
```php
// config/services.php - SAFE
return [
    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'api_url' => env('GITHUB_API_URL'),
    ],
];
```

### 3. .env File Setup
Create `.env` (NOT committed):
```env
APP_KEY=base64:xxxxx
GITHUB_TOKEN=ghp_xxxxxxxxxxxx
DATABASE_PASSWORD=secure_password
FIREBASE_KEY=xxxxxxxxxx
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
```

Create `.env.example` (committed):
```env
APP_KEY=
GITHUB_TOKEN=
DATABASE_PASSWORD=
FIREBASE_KEY=
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

### 4. Pre-Commit Hook
The `.husky/pre-commit` hook will prevent:
- ✅ GitHub PAT tokens (ghp_*)
- ✅ AWS Access Keys (AKIA*)
- ✅ Private keys (RSA, OpenSSH)
- ✅ Hardcoded passwords

### 5. Git Credential Storage
Use Git Credential Manager instead of storing tokens in URLs:
```powershell
git config --global credential.helper manager-core
```

---

## 📋 Security Audit Checklist

- [ ] Old PAT revoked (CRITICAL!)
- [ ] New PAT created with limited scope
- [ ] New PAT stored securely (password manager)
- [ ] Local git credentials cleared
- [ ] 2FA enabled on GitHub account
- [ ] `.env` file created (not committed)
- [ ] `.env.example` created with placeholders
- [ ] `.gitignore` updated with secret patterns
- [ ] Pre-commit hook installed
- [ ] All team members notified
- [ ] Code review of recent commits completed
- [ ] Monitor GitHub audit log for unauthorized access

---

## 🔍 How to Check for Leaks

**Scan current working directory:**
```powershell
# Look for suspicious strings
git grep -E 'ghp_|AKIA|BEGIN.*PRIVATE'

# Check uncommitted changes
git diff | Select-String -Pattern 'password|token|secret|key'
```

**Scan git history:**
```powershell
# Search all commits for secrets
git log -p | Select-String -Pattern 'API_KEY=|PASSWORD=|TOKEN=' -Context 3
```

---

## 📞 If You Suspect More Leaks

1. Check GitGuardian dashboard: https://dashboard.gitguardian.com/
2. Review all alerts
3. Revoke each compromised secret
4. Rotate credentials
5. Audit GitHub access logs for unauthorized activity

---

## ✨ Additional Resources

- [GitHub: Managing your personal access tokens](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)
- [Google: Cloud Security Checklist](https://cloud.google.com/architecture/security-checklist)
- [OWASP: Secure Coding Practices](https://cheatsheetseries.owasp.org/cheatsheets/Secrets_Management_Cheat_Sheet.html)

---

**Last Updated**: 2026-02-18  
**Next Review**: 2026-02-25  
**Status**: ✅ Remediation in progress
