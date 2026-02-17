# Git Push Script for GitHub (PowerShell)
# Run this to push your project to GitHub

$ErrorActionPreference = "Continue"

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  Pushing to GitHub: Lead-Genration                ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Get to project directory
$projectDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $projectDir

Write-Host "📂 Project directory: $projectDir" -ForegroundColor Yellow
Write-Host ""

# Step 1: Configure git
Write-Host "⚙️  Step 1: Configuring git..." -ForegroundColor Cyan
git config --global user.name "Lead-Development-Team" 2>&1 | Out-Null
git config --global user.email "team@leadgeneration.com" 2>&1 | Out-Null
$gitUser = git config --global user.name
Write-Host "✅ Configured: $gitUser" -ForegroundColor Green
Write-Host ""

# Step 2: Check git status
Write-Host "📋 Step 2: Checking git status..." -ForegroundColor Cyan
git status --short | Select-Object -First 10
Write-Host ""

# Step 3: Stage all files
Write-Host "📦 Step 3: Staging all files..." -ForegroundColor Cyan
git add -A
$stagedCount = (git diff --cached --name-only | Measure-Object -Line).Lines
Write-Host "✅ Staged $stagedCount files" -ForegroundColor Green
Write-Host ""

# Step 4: Commit
Write-Host "💾 Step 4: Creating commit..." -ForegroundColor Cyan
$commitMsg = "feat: Firebase integration, code cleanup, documentation, npm setup"
git commit -m $commitMsg 2>&1 | Select-Object -First 3
Write-Host "✅ Commit message: $commitMsg" -ForegroundColor Green
Write-Host ""

# Step 5: Setup remote
Write-Host "🔗 Step 5: Configuring remote repository..." -ForegroundColor Cyan
$remoteUrl = "https://github.com/cosmicnewworld-design/Lead-Genration.git"

# Remove old remote if exists
$existingRemote = git remote | Where-Object { $_ -eq "origin" }
if ($existingRemote) {
    git remote remove origin
    Write-Host "   (Removed old remote)" -ForegroundColor Gray
}

git remote add origin $remoteUrl 2>&1 | Out-Null
Write-Host "✅ Remote: $remoteUrl" -ForegroundColor Green
Write-Host ""

# Step 6: Get branch name
Write-Host "📌 Step 6: Checking branch..." -ForegroundColor Cyan
$branch = git rev-parse --abbrev-ref HEAD 2>&1
Write-Host "✅ Branch: $branch" -ForegroundColor Green
Write-Host ""

# Step 7: Push to GitHub
Write-Host "🚀 Step 7: Pushing to GitHub..." -ForegroundColor Cyan
Write-Host "   Repository: $remoteUrl" -ForegroundColor Yellow
Write-Host "   Branch: $branch" -ForegroundColor Yellow
Write-Host ""

$pushResult = git push -u origin $branch 2>&1
$pushResult | ForEach-Object { Write-Host $_ }

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║  ✅ Push Complete!                                 ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

Write-Host "📊 Latest commits:" -ForegroundColor Cyan
git log --oneline | Select-Object -First 3
Write-Host ""

Write-Host "🌐 View on GitHub:" -ForegroundColor Green
Write-Host "   https://github.com/cosmicnewworld-design/Lead-Genration" -ForegroundColor Blue
Write-Host ""

Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "   1. Open GitHub link in browser"
Write-Host "   2. Verify files are there"
Write-Host "   3. Continue with XAMPP installation for local dev"
Write-Host ""
