# Instructions de déploiement - CSS/JS en production

## Problème résolu ✅

Les CSS et JS ne se chargeaient pas en production car les fichiers compilés (`public/build/`) n'étaient pas déployés.

## Solution : Compiler les assets avant le déploiement

### Option 1 : Compiler sur le serveur de production (RECOMMANDÉ)

Sur votre serveur de production, exécutez :

```bash
# Installer les dépendances Node.js
npm install

# Compiler les assets pour la production
npm run build

# Vérifier que les fichiers sont créés
ls -la public/build/
```

### Option 2 : Compiler en local et inclure dans Git (pour déploiement simple)

#### Étape 1 : Compiler les assets

```powershell
# Dans PowerShell
npm run build

# Ou utiliser le script
.\scripts\build-production.ps1
```

#### Étape 2 : Inclure les fichiers build dans Git (temporairement)

1. Modifiez temporairement `.gitignore` :
   - Commentez ou supprimez la ligne `/public/build`

2. Ajoutez les fichiers build :
   ```powershell
   git add public/build
   git commit -m "Add production build assets"
   git push
   ```

3. **IMPORTANT** : Remettez `/public/build` dans `.gitignore` après le commit !

#### Étape 3 : Déployer

Vos fichiers build seront maintenant dans le dépôt et seront déployés automatiquement.

### Option 3 : Pipeline CI/CD (MEILLEURE PRATIQUE)

Si vous utilisez GitHub Actions, Azure DevOps, ou un autre service CI/CD :

```yaml
# Exemple GitHub Actions
- name: Install dependencies
  run: npm ci

- name: Build assets
  run: npm run build

- name: Deploy
  # ... votre processus de déploiement
```

## Configuration en production

Assurez-vous que votre `.env` en production contient :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

**Note importante** : En production, vous n'avez PAS besoin des variables `VITE_*` car Laravel utilise les fichiers statiques compilés dans `public/build/`.

## Vérification après déploiement

1. Vérifiez que les fichiers existent :
   ```bash
   ls -la public/build/manifest.json
   ls -la public/build/assets/
   ```

2. Videz le cache Laravel :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

3. Vérifiez dans le navigateur (F12 > Network) :
   - Les fichiers CSS doivent être chargés depuis `/build/assets/app-*.css`
   - Les fichiers JS doivent être chargés depuis `/build/assets/app-*.js`

## Dépannage

### Si les assets ne se chargent toujours pas :

1. **Vérifier les permissions** :
   ```bash
   chmod -R 755 public/build
   ```

2. **Vérifier que manifest.json existe** :
   ```bash
   cat public/build/manifest.json
   ```

3. **Vérifier l'APP_ENV** :
   ```bash
   php artisan tinker
   # Puis tapez : config('app.env')
   # Doit retourner 'production'
   ```

4. **Vérifier les logs** :
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Commandes utiles

```bash
# Build pour la production
npm run build

# Build avec vérifications (script PowerShell)
.\scripts\build-production.ps1

# Voir les fichiers générés
ls -lh public/build/assets/

# Vider tous les caches
php artisan optimize:clear
```

## Pour Railway, Heroku, ou autres PaaS

Si vous déployez sur Railway, Heroku, ou un autre service, ajoutez dans votre fichier de configuration :

**railway.json** (pour Railway) :
```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "npm install && npm run build && composer install --no-dev --optimize-autoloader"
  }
}
```

Ou créez un fichier **Procfile** pour Heroku :
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

Et ajoutez dans **package.json** un script postinstall :
```json
{
  "scripts": {
    "postinstall": "npm run build"
  }
}
```

## Résumé rapide

✅ **Pour déployer maintenant :**
1. `npm run build`
2. Commiter `public/build/` (en retirant temporairement de .gitignore)
3. Pousser vers votre dépôt
4. Déployer
5. Remettre `/public/build` dans .gitignore

✅ **Pour l'avenir :**
- Configurer un CI/CD qui compile automatiquement
- Ou compiler directement sur le serveur de production
