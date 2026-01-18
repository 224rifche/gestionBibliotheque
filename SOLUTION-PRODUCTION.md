# Solution Définitive - CSS/JS en Production

## Diagnostic du problème

Si les CSS/JS ne se chargent toujours pas en production, utilisez cette commande de diagnostic :

```bash
php artisan assets:diagnose
```

Cette commande vérifiera :
- ✅ L'environnement (APP_ENV, APP_DEBUG)
- ✅ L'existence du manifest.json
- ✅ Les fichiers assets compilés
- ✅ La configuration APP_URL
- ✅ Les permissions

## Solution 1 : Configuration Vite avec base path (RECOMMANDÉ)

Si votre application est déployée dans un sous-dossier ou si les chemins sont incorrects, configurez Vite correctement :

### Modifier vite.config.js

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: process.env.APP_ENV === 'production' 
        ? '/build/'  // Assurez-vous que cela correspond à votre structure
        : '/',
    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
    },
});
```

Puis recompilez :
```bash
npm run build
```

## Solution 2 : Fallback avec assets statiques (SI SOLUTION 1 NE FONCTIONNE PAS)

Créez une directive Blade personnalisée qui charge les assets directement si Vite échoue :

### Créer app/View/Components/ViteAssets.php

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\File;

class ViteAssets extends Component
{
    public function render()
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (!File::exists($manifestPath)) {
            // Fallback : retourner des balises vides ou des assets statiques
            return <<<'HTML'
                <!-- Assets non disponibles -->
                <link rel="stylesheet" href="/css/app.css">
                <script src="/js/app.js"></script>
            HTML;
        }
        
        // Utiliser Vite normalement
        return '';
    }
}
```

### Modifier resources/views/layouts/app.blade.php

```blade
<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Fallback si Vite ne fonctionne pas --}}
@production
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    @endphp
    @if(isset($manifest['resources/css/app.css']['file']))
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    @endif
    @if(isset($manifest['resources/js/app.js']['file']))
        <script src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" defer></script>
    @endif
@endproduction
```

## Solution 3 : Configuration explicite pour la production

### Créer config/vite.php (si n'existe pas)

```php
<?php

return [
    'build_path' => env('VITE_BUILD_PATH', 'build'),
    'manifest_path' => public_path(env('VITE_BUILD_PATH', 'build') . '/manifest.json'),
];
```

### Modifier .env en production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Force l'utilisation des fichiers build (pas du serveur Vite)
ASSET_URL=
```

## Solution 4 : Script de déploiement complet

Créez un script qui fait tout automatiquement :

### scripts/deploy-production.ps1

```powershell
Write-Host "🚀 Déploiement en production..." -ForegroundColor Cyan

# 1. Installer les dépendances
Write-Host "📦 Installation des dépendances..." -ForegroundColor Yellow
npm ci --production=false

# 2. Compiler les assets
Write-Host "🔨 Compilation des assets..." -ForegroundColor Yellow
npm run build

# 3. Vérifier que les fichiers existent
if (-not (Test-Path "public/build/manifest.json")) {
    Write-Host "❌ ERREUR: manifest.json introuvable!" -ForegroundColor Red
    exit 1
}

# 4. Optimiser Laravel
Write-Host "⚙️ Optimisation de Laravel..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Vider les caches de développement
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 6. Recréer les caches de production
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Déploiement terminé avec succès!" -ForegroundColor Green
Write-Host "💡 Vérifiez que APP_ENV=production dans votre .env" -ForegroundColor Yellow
```

## Solution 5 : Vérification manuelle rapide

Exécutez ces commandes sur votre serveur de production :

```bash
# 1. Vérifier que les fichiers existent
ls -la public/build/manifest.json
ls -la public/build/assets/

# 2. Vérifier le contenu du manifest
cat public/build/manifest.json

# 3. Vérifier les permissions
chmod -R 755 public/build

# 4. Vider TOUS les caches
php artisan optimize:clear

# 5. Vérifier l'URL dans le navigateur
# Ouvrez : https://votre-domaine.com/build/manifest.json
# Devrait afficher le JSON du manifest

# 6. Vérifier un fichier CSS directement
# Ouvrez : https://votre-domaine.com/build/assets/app-*.css
# Devrait afficher le CSS compilé
```

## Solution 6 : Si vous utilisez un sous-dossier

Si votre application est dans un sous-dossier (ex: `/app/`), modifiez :

### vite.config.js

```javascript
export default defineConfig({
    base: '/app/',  // Remplacez par votre chemin de base
    // ... reste de la config
});
```

### .htaccess ou configuration serveur

Assurez-vous que les routes pointent correctement vers `public/`

## Checklist de déploiement

- [ ] `npm run build` exécuté avec succès
- [ ] `public/build/manifest.json` existe
- [ ] `public/build/assets/` contient les fichiers CSS/JS
- [ ] `APP_ENV=production` dans .env
- [ ] `APP_DEBUG=false` dans .env
- [ ] `APP_URL` est correct dans .env
- [ ] Permissions correctes sur `public/build/` (755)
- [ ] Cache Laravel vidé puis recréé
- [ ] Manifest accessible via URL directe
- [ ] Fichiers CSS/JS accessibles via URL directe

## Dépannage avancé

### Si les fichiers retournent 404

1. Vérifiez que votre serveur web pointe vers le dossier `public/`
2. Vérifiez la configuration `.htaccess` (Apache) ou `nginx.conf` (Nginx)
3. Vérifiez que les fichiers sont bien uploadés sur le serveur

### Si les chemins sont incorrects

1. Vérifiez `APP_URL` dans .env
2. Vérifiez la directive `@vite()` dans vos vues
3. Vérifiez que le `manifest.json` contient les bons chemins

### Si les styles ne s'appliquent pas

1. Vérifiez que le CSS est bien chargé (F12 > Network)
2. Vérifiez qu'il n'y a pas de conflits de classes
3. Vérifiez que Tailwind est bien compilé (regardez le contenu du CSS)

## Commande de diagnostic

Exécutez cette commande pour un diagnostic complet :

```bash
php artisan assets:diagnose
```

## Support

Si le problème persiste, vérifiez :
1. Les logs Laravel : `storage/logs/laravel.log`
2. Les logs du serveur web (Apache/Nginx)
3. La console du navigateur (F12) pour les erreurs
4. L'onglet Network (F12) pour voir les requêtes HTTP
