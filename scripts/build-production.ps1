# Script de build pour la production (PowerShell)
# Ce script compile les assets et vérifie que tout est prêt pour le déploiement

Write-Host "🚀 Building assets for production..." -ForegroundColor Cyan

# Installer les dépendances
Write-Host "📦 Installing dependencies..." -ForegroundColor Yellow
npm ci --production=false

# Compiler les assets
Write-Host "🔨 Building assets..." -ForegroundColor Yellow
npm run build

# Vérifier que les fichiers existent
if (-not (Test-Path "public/build/manifest.json")) {
    Write-Host "❌ ERROR: manifest.json not found!" -ForegroundColor Red
    exit 1
}

if (-not (Test-Path "public/build/assets")) {
    Write-Host "❌ ERROR: assets directory not found!" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Build completed successfully!" -ForegroundColor Green
Write-Host "📁 Files generated in public/build/" -ForegroundColor Cyan
Get-ChildItem "public/build/assets" | Format-Table Name, Length

Write-Host ""
Write-Host "✅ Ready for deployment!" -ForegroundColor Green
Write-Host "💡 Make sure to set APP_ENV=production and APP_DEBUG=false in your .env" -ForegroundColor Yellow
