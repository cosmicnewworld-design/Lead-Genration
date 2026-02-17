# 🔐 GitHub Authentication Guide

**Issue**: Permission denied when pushing to GitHub

**Reason**: Git credentials not configured or insufficient permissions

---

## ✅ Solution 1: Personal Access Token (RECOMMENDED)

### Step 1: Create GitHub Personal Access Token

1. Open browser: https://github.com/settings/tokens
2. Click "**Generate new token**" → Choose "**classic**"
3. Name it: `lead-genration-push`
4. **Expiration**: 90 days (or Lifetime)
5. **Select scopes**:
   - ✅ `repo` (full control of private repositories)
   - ✅ `write:packages`
6. Click "**Generate token**"
7. **COPY the token** (you won't see it again!)
8. **Paste it somewhere safe** (notepad)

### Step 2: Use Token for Git Push

In PowerShell (in project folder):

```powershell
git push -u origin main
```

When prompted:
```
Username: your-github-username
Password: [PASTE YOUR TOKEN HERE - NOT your GitHub password]
```

That's it! ✅

---

## ⚡ Solution 2: SSH Key (Professional Setup)

### Step 1: Generate SSH Key

```powershell
# Generate new SSH key
ssh-keygen -t ed25519 -C "your-email@gmail.com"

# Just press Enter for all prompts (no passphrase needed)
# This creates: C:\Users\YourUsername\.ssh\id_ed25519
```

### Step 2: Add SSH Key to GitHub

```powershell
# Copy your public key
type "$env:USERPROFILE\.ssh\id_ed25519.pub"
# Copies output to clipboard
```

1. Go to: https://github.com/settings/keys
2. Click "**New SSH key**"
3. **Title**: `My Windows PC`
4. **Key type**: Authentication Key
5. **Paste your public key** (from above)
6. Click "**Add SSH key**"
7. Verify with GitHub password

### Step 3: Update Git Remote

```powershell
# Change remote from HTTPS to SSH
git remote remove origin
git remote add origin git@github.com:cosmicnewworld-design/Lead-Genration.git

# Try push again
git push -u origin main
```

---

## 🔒 Solution 3: Store Credentials Locally

Windows에서 자동 저장:

```powershell
# Enable credential storage
git config --global credential.helper wincred

# Next time you push:
git push -u origin main

# Enter credentials once, Windows will save them
```

---

## 🚀 After Authentication is Set Up

```powershell
# Push code
cd "C:\Users\SPL2\Desktop\lead software\Lead-Genration"
git push -u origin main

# Verify on GitHub
# Open: https://github.com/cosmicnewworld-design/Lead-Genration
# Your files should be there!
```

---

## ✅ Troubleshooting

### "401 Unauthorized"
- Token expired or incorrect
- Generate new token and try again

### "Permission denied"
- Wrong account or repository access
- Check if you're owner of the repo
- Or create a new deploy key

### "Could not read Username"
- Make sure you entered credentials correctly
- Clear stored credentials: `git credential reject`

### "fatal: bad config file"
- Check .git/config file
- Make sure remote URL is correct

---

## 🎯 Quick Steps to Get It Working

1. **Go to**: https://github.com/settings/tokens
2. **Generate new token** (classic)
3. **Copy token**
4. **In PowerShell**:
   ```powershell
   cd "C:\Users\SPL2\Desktop\lead software\Lead-Genration"
   git push -u origin main
   # Paste token when asked for password
   ```

That's it! 🚀

---

## After Push is Complete

```powershell
# Verify
git log --oneline -3

# View on GitHub
# https://github.com/cosmicnewworld-design/Lead-Genration
```

All your code will be on GitHub! ✅

---

**Which solution do you want to use?**

1. **Personal Access Token** (easiest, takes 2 min)
2. **SSH Key** (professional, setup once)
3. **Credential Helper** (quickest)

Let me know and I'll help complete the push! 🚀
