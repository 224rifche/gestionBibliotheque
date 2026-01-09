# Script pour restaurer le .gitignore après le déploiement
# Retire les fichiers build de Git et les remet dans .gitignore

Write-Host "🔄 Restoring .gitignore..." -ForegroundColor Cyan

# Restaurer le .gitignore original
if (Test-Path .gitignore.backup) {
    Move-Item -Force .gitignore.backup .gitignore
    Write-Host "✅ .gitignore restored" -ForegroundColor Green
} else {
    # Ajouter public/build au .gitignore si pas de backup
    Add-Content .gitignore "`n/public/build"
    Write-Host "✅ Added /public/build to .gitignore" -ForegroundColor Green
}

Write-Host ""
Write-Host "🗑️  Removing build files from Git tracking (but keeping local files)..." -ForegroundColor Yellow
git rm -r --cached public/build/ 2>$null
Write-Host "✅ Build files removed from Git tracking" -ForegroundColor Green

Write-Host ""
Write-Host "✅ Done! Build files are now ignored by Git again." -ForegroundColor Green
