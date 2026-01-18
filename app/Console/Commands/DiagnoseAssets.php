<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DiagnoseAssets extends Command
{
    protected $signature = 'assets:diagnose';
    protected $description = 'Diagnostique les problèmes avec les assets Vite en production';

    public function handle()
    {
        $this->info('🔍 Diagnostic des assets Vite...');
        $this->newLine();

        $issues = [];
        $warnings = [];

        // 1. Vérifier l'environnement
        $this->info('1. Vérification de l\'environnement...');
        $env = config('app.env');
        $debug = config('app.debug');
        
        if ($env !== 'production') {
            $warnings[] = "APP_ENV est défini à '{$env}' au lieu de 'production'";
            $this->warn("   ⚠️  APP_ENV = {$env}");
        } else {
            $this->info("   ✅ APP_ENV = production");
        }

        if ($debug === true && $env === 'production') {
            $issues[] = "APP_DEBUG est true en production (sécurité)";
            $this->error("   ❌ APP_DEBUG = true (devrait être false)");
        } else {
            $this->info("   ✅ APP_DEBUG = " . ($debug ? 'true' : 'false'));
        }

        // 2. Vérifier le manifest.json
        $this->newLine();
        $this->info('2. Vérification du manifest.json...');
        $manifestPath = public_path('build/manifest.json');
        $viteManifestPath = public_path('build/.vite/manifest.json');
        
        // Vérifier d'abord dans .vite/manifest.json (nouveau format)
        if (File::exists($viteManifestPath)) {
            $manifestPath = $viteManifestPath;
            $this->info("   ✅ manifest.json trouvé dans .vite/");
        } elseif (!File::exists($manifestPath)) {
            $issues[] = "manifest.json n'existe pas dans public/build/";
            $this->error("   ❌ manifest.json introuvable à : {$manifestPath}");
            $this->error("   ❌ Vérifiez aussi : {$viteManifestPath}");
        } else {
            $this->info("   ✅ manifest.json existe");
            
            $manifest = json_decode(File::get($manifestPath), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $issues[] = "manifest.json est corrompu ou invalide";
                $this->error("   ❌ manifest.json invalide : " . json_last_error_msg());
            } else {
                $this->info("   ✅ manifest.json valide");
                
                // Vérifier les fichiers référencés
                foreach ($manifest as $entry) {
                    if (isset($entry['file'])) {
                        $filePath = public_path('build/' . $entry['file']);
                        if (!File::exists($filePath)) {
                            $issues[] = "Fichier référencé manquant : {$entry['file']}";
                            $this->error("   ❌ Fichier manquant : {$entry['file']}");
                        } else {
                            $this->info("   ✅ Fichier existe : {$entry['file']}");
                        }
                    }
                }
            }
        }

        // 3. Vérifier le dossier build
        $this->newLine();
        $this->info('3. Vérification du dossier build...');
        $buildPath = public_path('build');
        
        if (!File::isDirectory($buildPath)) {
            $issues[] = "Le dossier public/build/ n'existe pas";
            $this->error("   ❌ Dossier build introuvable");
        } else {
            $this->info("   ✅ Dossier build existe");
            
            $assetsPath = public_path('build/assets');
            if (!File::isDirectory($assetsPath)) {
                $issues[] = "Le dossier public/build/assets/ n'existe pas";
                $this->error("   ❌ Dossier assets introuvable");
            } else {
                $files = File::files($assetsPath);
                $fileCount = is_array($files) ? count($files) : $files->count();
                $this->info("   ✅ Dossier assets existe ({$fileCount} fichiers)");
            }
        }

        // 4. Vérifier APP_URL
        $this->newLine();
        $this->info('4. Vérification de APP_URL...');
        $appUrl = config('app.url');
        
        if (empty($appUrl) || $appUrl === 'http://localhost') {
            $warnings[] = "APP_URL n'est pas configuré correctement";
            $this->warn("   ⚠️  APP_URL = {$appUrl}");
        } else {
            $this->info("   ✅ APP_URL = {$appUrl}");
        }

        // 5. Vérifier les permissions (si possible)
        if (File::exists($manifestPath)) {
            $this->newLine();
            $this->info('5. Vérification des permissions...');
            $perms = substr(sprintf('%o', fileperms($manifestPath)), -4);
            $this->info("   ℹ️  Permissions manifest.json : {$perms}");
        }

        // Résumé
        $this->newLine();
        $this->info('📊 Résumé du diagnostic :');
        $this->newLine();

        if (empty($issues) && empty($warnings)) {
            $this->info('✅ Tout semble correct !');
            return 0;
        }

        if (!empty($warnings)) {
            foreach ($warnings as $warning) {
                $this->warn("⚠️  {$warning}");
            }
        }

        if (!empty($issues)) {
            $this->error('❌ Problèmes détectés :');
            foreach ($issues as $issue) {
                $this->error("   - {$issue}");
            }
            
            $this->newLine();
            $this->info('💡 Solutions suggérées :');
            $this->info('   1. Exécutez : npm run build');
            $this->info('   2. Vérifiez que public/build/ est accessible');
            $this->info('   3. Videz le cache : php artisan optimize:clear');
            $this->info('   4. Vérifiez votre .env en production');
            
            return 1;
        }

        return 0;
    }
}
