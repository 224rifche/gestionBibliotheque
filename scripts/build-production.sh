#!/bin/bash

# Script de build pour la production
# Ce script compile les assets et vérifie que tout est prêt pour le déploiement

echo "🚀 Building assets for production..."

# Installer les dépendances
echo "📦 Installing dependencies..."
npm ci --production=false

# Compiler les assets
echo "🔨 Building assets..."
npm run build

# Vérifier que les fichiers existent
if [ ! -f "public/build/manifest.json" ]; then
    echo "❌ ERROR: manifest.json not found!"
    exit 1
fi

if [ ! -d "public/build/assets" ]; then
    echo "❌ ERROR: assets directory not found!"
    exit 1
fi

echo "✅ Build completed successfully!"
echo "📁 Files generated in public/build/"
ls -lh public/build/assets/

echo ""
echo "✅ Ready for deployment!"
echo "💡 Make sure to set APP_ENV=production and APP_DEBUG=false in your .env"
