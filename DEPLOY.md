# Guide de déploiement en production

## Problème : CSS et JS ne se chargent pas en production

### Solution : Compiler les assets avant le déploiement

## Étapes de déploiement

### 1. Compiler les assets pour la production

```bash
npm install
npm run build
```

Cette commande génère les fichiers optimisés dans `public/build/`

### 2. Vérifier les fichiers générés

Les fichiers suivants doivent exister :
- `public/build/manifest.json`
- `public/build/assets/app-*.css`
- `public/build/assets/app-*.js`

### 3. Configuration de l'environnement (.env en production)

Assurez-vous que votre `.env` en production contient :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Pas besoin de VITE_* en production, Laravel utilise les fichiers build
```

### 4. Déploiement

#### Option A : Inclure les fichiers build dans Git (Non recommandé pour gros projets)

Modifiez `.gitignore` temporairement pour inclure les fichiers build :

```bash
# Retirer cette ligne du .gitignore :
# /public/build

# Puis commiter les fichiers build
git add public/build
git commit -m "Add production build assets"
git push
```

#### Option B : Compiler sur le serveur (Recommandé)

Sur votre serveur de production :

```bash
# Installer les dépendances
npm ci --production=false

# Compiler les assets
npm run build

# Les fichiers seront générés dans public/build/
```

#### Option C : Utiliser un pipeline CI/CD (Meilleure pratique)

Exemple avec GitHub Actions :

```yaml
- name: Install dependencies
  run: npm ci

- name: Build assets
  run: npm run build

- name: Deploy
  # ... votre processus de déploiement
```

### 5. Permissions et vérifications

Assurez-vous que le serveur web peut lire les fichiers :

```bash
chmod -R 755 public/build
```

### 6. Vérifier que tout fonctionne

En production, vérifiez que :
- `APP_ENV=production` est défini
- `APP_DEBUG=false` est défini
- Les fichiers `public/build/manifest.json` existent
- Les fichiers CSS et JS sont accessibles via l'URL

## Dépannage

### Si les assets ne se chargent toujours pas :

1. **Vider le cache Laravel** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. **Vérifier les permissions** :
   ```bash
   chmod -R 755 public
   chmod -R 755 storage
   ```

3. **Vérifier que manifest.json existe** :
   ```bash
   ls -la public/build/manifest.json
   ```

4. **Vérifier l'URL dans le navigateur** :
   Ouvrez les outils de développement (F12) et vérifiez les erreurs dans la console.
   Les fichiers CSS et JS doivent être chargés depuis `/build/assets/...`

5. **En cas d'erreur 404** :
   Vérifiez que le serveur web pointe bien vers le dossier `public/`

## Notes importantes

- ⚠️ Ne jamais mettre `node_modules` en production, ils sont très volumineux
- ✅ Toujours compiler les assets avant le déploiement
- ✅ En production, Laravel n'utilise pas le serveur Vite, mais les fichiers statiques compilés
- ✅ Le `manifest.json` est essentiel pour que Laravel trouve les bons fichiers
