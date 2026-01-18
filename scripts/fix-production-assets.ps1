# Script de correction automatique des assets en production
# Ce script diagnostique et corrige les problèmes avec les assets

Write-Host "🔧 Correction des assets pour la production..." -ForegroundColor Cyan
Write-Host ""

# 1. Compiler les assets
Write-Host "1️⃣ Compilation des assets..." -ForegroundColor Yellow
npm run build

if (-not $?) {
    Write-Host "❌ Erreur lors de la compilation" -ForegroundColor Red
    exit 1
}

# 2. Vérifier que manifest.json existe
Write-Host ""
Write-Host "2️⃣ Vérification du manifest.json..." -ForegroundColor Yellow
if (-not (Test-Path "public/build/manifest.json")) {
    Write-Host "❌ manifest.json introuvable!" -ForegroundColor Red
    exit 1
}
Write-Host "✅ manifest.json trouvé" -ForegroundColor Green

# 3. Vérifier le contenu du manifest
Write-Host ""
Write-Host "3️⃣ Vérification du contenu..." -ForegroundColor Yellow
$manifest = Get-Content "public/build/manifest.json" | ConvertFrom-Json
$cssFile = $manifest.'resources/css/app.css'.file
$jsFile = $manifest.'resources/js/app.js'.file

if (-not $cssFile) {
    Write-Host "❌ Fichier CSS non trouvé dans le manifest" -ForegroundColor Red
    exit 1
}

if (-not $jsFile) {
    Write-Host "❌ Fichier JS non trouvé dans le manifest" -ForegroundColor Red
    exit 1
}

Write-Host "✅ CSS: $cssFile" -ForegroundColor Green
Write-Host "✅ JS: $jsFile" -ForegroundColor Green

# 4. Vérifier que les fichiers existent
Write-Host ""
Write-Host "4️⃣ Vérification des fichiers..." -ForegroundColor Yellow
if (-not (Test-Path "public/build/$cssFile")) {
    Write-Host "❌ Fichier CSS introuvable: $cssFile" -ForegroundColor Red
    exit 1
}

if (-not (Test-Path "public/build/$jsFile")) {
    Write-Host "❌ Fichier JS introuvable: $jsFile" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Tous les fichiers existent" -ForegroundColor Green

# 5. Vider les caches Laravel
Write-Host ""
Write-Host "5️⃣ Nettoyage des caches Laravel..." -ForegroundColor Yellow
php artisan optimize:clear

# 6. Recréer les caches pour la production
Write-Host ""
Write-Host "6️⃣ Optimisation pour la production..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host ""
Write-Host "✅ Correction terminée avec succès!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Vérifications à faire:" -ForegroundColor Cyan
Write-Host "   - APP_ENV=production dans .env" -ForegroundColor White
Write-Host "   - APP_DEBUG=false dans .env" -ForegroundColor White
Write-Host "   - APP_URL correct dans .env" -ForegroundColor White
Write-Host "   - Permissions: chmod -R 755 public/build (sur Linux)" -ForegroundColor White
Write-Host ""
Write-Host "💡 Pour diagnostiquer: php artisan assets:diagnose" -ForegroundColor Yellow
