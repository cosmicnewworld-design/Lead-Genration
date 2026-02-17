#!/bin/bash
# Git Push Script for GitHub

cd "$(dirname "$0")"

echo "🚀 Pushing to GitHub Repository"
echo "=================================================================="
echo ""

# Configure git user
echo "⚙️  Configuring git user..."
git config --global user.name "Lead-Generation-Dev"
git config --global user.email "dev@leadgeneration.com"
echo "✅ Git user configured"
echo ""

# Add all files
echo "📦 Adding all files..."
git add -A
echo "✅ Files staged"
echo ""

# Show what will be committed
echo "📋 Files to commit:"
git diff --cached --name-only | head -20
echo ""

# Commit
echo "💾 Creating commit..."
git commit -m "feat: Firebase integration + cleanup + documentation + npm modules"
echo "✅ Commit created"
echo ""

# Check remote
echo "🔗 Checking remote configuration..."
git remote -v

# Remove old origin if it exists
git remote remove origin 2>/dev/null

# Add new remote
echo "Adding GitHub remote..."
git remote add origin "https://github.com/cosmicnewworld-design/Lead-Genration.git"
echo "✅ Remote configured: https://github.com/cosmicnewworld-design/Lead-Genration"
echo ""

# Get branch
BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "main")
echo "📌 Current branch: $BRANCH"
echo ""

# Push to GitHub
echo "🚀 Pushing to GitHub..."
echo "   Repository: https://github.com/cosmicnewworld-design/Lead-Genration"
echo ""

git push -u origin "$BRANCH" 2>&1

echo ""
echo "✅ Push completed!"
echo ""
echo "🌐 View repository:"
echo "   https://github.com/cosmicnewworld-design/Lead-Genration"
echo ""

# Show recent commits
echo "📊 Recent commits:"
git log --oneline -5
