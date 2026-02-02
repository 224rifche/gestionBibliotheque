#!/usr/bin/env bash
set -e

echo "🚀 Début du build..."

# Diagnostic des outils disponibles
echo "🔍 Diagnostic des outils..."
which node || echo "❌ Node.js non trouvé"
which npm || echo "❌ NPM non trouvé"
which php || echo "❌ PHP non trouvé"
which composer || echo "❌ Composer non trouvé"

echo "📦 Versions des outils :"
node --version 2>/dev/null || echo "Node: non installé"
npm --version 2>/dev/null || echo "NPM: non installé"
php --version || echo "PHP: non installé"
composer --version || echo "Composer: non installé"

# Installation des dépendances PHP
echo "📦 Installation de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# Installation des dépendances Node
echo "📦 Installation de NPM..."
# Essayer npm ci, sinon npm install
if npm ci 2>/dev/null; then
    echo "✅ npm ci réussi"
else
    echo "⚠️ npm ci échoué, tentative avec npm install..."
    npm install
fi

# Build des assets
echo "🔨 Build Vite..."
# Essayer différentes méthodes pour le build
if npm run build 2>/dev/null; then
    echo "✅ npm run build réussi"
elif npx vite build 2>/dev/null; then
    echo "✅ npx vite build réussi"
else
    echo "❌ Erreur: Impossible de builder les assets"
    echo "📋 Contenu de package.json:"
    cat package.json
    exit 1
fi

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
