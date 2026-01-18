# Solution Finale - CSS/JS en Production ✅

## Problème résolu

Le manifest.json est maintenant généré au bon endroit grâce à la configuration Vite corrigée.

## Solution en 3 étapes

### 1️⃣ Compiler les assets (À FAIRE EN LOCAL OU SUR LE SERVEUR)

```bash
npm run build
```

Cela génère maintenant correctement :
- ✅ `public/build/manifest.json`
- ✅ `public/build/assets/app-*.css`
- ✅ `public/build/assets/app-*.js`

### 2️⃣ Inclure les fichiers build dans le déploiement

#### Option A : Compiler sur le serveur (RECOMMANDÉ)

Sur votre serveur de production :

```bash
# Installer les dépendances
npm ci --production=false

# Compiler les assets
npm run build

# Vérifier
ls -la public/build/manifest.json
ls -la public/build/assets/
```

#### Option B : Commiter les fichiers build (Simple mais moins propre)

1. **Temporairement**, modifiez `.gitignore` :
   - Commentez la ligne `/public/build`

2. Ajoutez les fichiers :
   ```bash
   git add public/build
   git commit -m "Add production build assets"
   git push
   ```

3. **IMPORTANT** : Remettez `/public/build` dans `.gitignore` après !

### 3️⃣ Configuration .env en production

Assurez-vous que votre `.env` contient :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

**Pas besoin de variables VITE_* en production !**

## Commandes de vérification

### Diagnostic automatique

```bash
php artisan assets:diagnose
```

Cette commande vérifie :
- ✅ L'environnement (APP_ENV, APP_DEBUG)
- ✅ Le manifest.json
- ✅ Les fichiers compilés
- ✅ La configuration

### Vérification manuelle

```bash
# 1. Vérifier les fichiers
ls -la public/build/manifest.json
ls -la public/build/assets/

# 2. Vider le cache
php artisan optimize:clear

# 3. Recréer les caches de production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Vérifier les permissions (Linux)
chmod -R 755 public/build
```

## Script de déploiement automatique

Utilisez le script PowerShell :

```powershell
.\scripts\fix-production-assets.ps1
```

Ou le script bash :

```bash
bash scripts/build-production.sh
```

Ces scripts :
1. Compilent les assets
2. Vérifient que tout est correct
3. Vident et recréent les caches Laravel

## Pour Railway, Heroku, etc.

### Ajouter dans package.json :

```json
{
  "scripts": {
    "postinstall": "npm run build"
  }
}
```

Cela compilera automatiquement les assets lors du déploiement.

### Ou créer un fichier railway.json :

```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "npm install && npm run build && composer install --no-dev --optimize-autoloader"
  }
}
```

## Checklist de déploiement

- [ ] `npm run build` exécuté avec succès
- [ ] `public/build/manifest.json` existe
- [ ] `public/build/assets/` contient les fichiers
- [ ] Les fichiers sont commités (option B) ou compilés sur le serveur (option A)
- [ ] `.env` configuré avec `APP_ENV=production`
- [ ] Cache vidé puis recréé
- [ ] Permissions correctes (755 sur Linux)
- [ ] Test dans le navigateur : les CSS/JS se chargent

## Dépannage rapide

### Si ça ne fonctionne toujours pas :

1. **Vérifier que manifest.json est accessible** :
   Ouvrez dans le navigateur : `https://votre-domaine.com/build/manifest.json`
   Devrait afficher le JSON

2. **Vérifier un fichier CSS directement** :
   Ouvrez : `https://votre-domaine.com/build/assets/app-*.css`
   Devrait afficher le CSS compilé

3. **Vérifier les erreurs dans la console (F12)** :
   Onglet Network : regardez les requêtes 404

4. **Vérifier que le serveur web pointe vers `public/`** :
   C'est crucial ! Le dossier racine doit être `public/`

5. **Vider TOUT le cache** :
   ```bash
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Solution d'urgence (SI RIEN NE FONCTIONNE)

Si absolument rien ne fonctionne, vous pouvez temporairement utiliser des assets CDN :

```blade
<!-- Dans resources/views/layouts/app.blade.php -->
@if(app()->environment('production'))
    <!-- Fallback CDN si Vite ne fonctionne pas -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3/dist/tailwind.min.css" rel="stylesheet">
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
```

**Mais ce n'est qu'une solution temporaire !** La vraie solution est de compiler correctement les assets.

## Résumé

✅ **Configuration Vite corrigée** : Le manifest.json est maintenant généré au bon endroit

✅ **Commande de diagnostic créée** : `php artisan assets:diagnose`

✅ **Scripts de déploiement créés** : Automatisation complète

✅ **Documentation complète** : Toutes les solutions documentées

**La meilleure solution est :**
1. Compiler avec `npm run build` (en local ou sur le serveur)
2. Inclure les fichiers `public/build/` dans le déploiement
3. Configurer `.env` correctement
4. Vider et recréer les caches
