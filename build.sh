#!/usr/bin/env bash
set -e

echo "🚀 Début du build..."

# Installation des dépendances PHP
echo "📦 Installation de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# Installation des dépendances Node
echo "📦 Installation de NPM..."
npm ci

# Build des assets
echo "🔨 Build Vite..."
npm run build

# Vérification du build
if [ ! -d "public/build" ]; then
    echo "❌ Erreur: Le dossier public/build n'existe pas après le build"
    exit 1
fi

echo "✅ Vérification du manifest..."
if [ -f "public/build/manifest.json" ] || [ -f "public/build/.vite/manifest.json" ]; then
    echo "✅ Manifest trouvé"
else
    echo "❌ Erreur: Manifest non trouvé"
    exit 1
fi

# Cache Laravel
echo "⚙️ Configuration du cache..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build terminé avec succès!"
