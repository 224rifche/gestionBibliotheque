# Script pour inclure les fichiers build dans Git pour le déploiement
# Utilisez ce script uniquement avant de pousser vers la production

Write-Host "🔨 Building assets for production..." -ForegroundColor Cyan
npm run build

Write-Host ""
Write-Host "📝 Temporarily removing public/build from .gitignore..." -ForegroundColor Yellow

# Sauvegarder le .gitignore original
Copy-Item .gitignore .gitignore.backup

# Retirer public/build du .gitignore temporairement
(Get-Content .gitignore) | Where-Object { $_ -notmatch '^/public/build$' -and $_ -notmatch '^public/build$' } | Set-Content .gitignore.temp
Move-Item -Force .gitignore.temp .gitignore

Write-Host "✅ Added public/build files to Git..." -ForegroundColor Green
git add public/build/
git status public/build

Write-Host ""
Write-Host "📝 Committing build files..." -ForegroundColor Yellow
Write-Host "💡 Commit message: 'Add production build assets'" -ForegroundColor Cyan

Write-Host ""
Write-Host "⚠️  IMPORTANT:" -ForegroundColor Yellow
Write-Host "   After deploying, restore .gitignore with:" -ForegroundColor White
Write-Host "   git checkout .gitignore" -ForegroundColor Gray
Write-Host ""
Write-Host "   Or manually add '/public/build' back to .gitignore" -ForegroundColor Gray
