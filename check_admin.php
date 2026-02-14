<?php

// Vérifier l'admin existant
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;

$admin = User::where('login', 'ADMIN001')->first();

if ($admin) {
    echo "✅ Admin trouvé !\n";
    echo "Login: {$admin->login}\n";
    echo "Rôle: {$admin->role}\n";
    echo "Actif: " . ($admin->actif ? 'Oui' : 'Non') . "\n";
    echo "ID: {$admin->id}\n";
    echo "Créé le: {$admin->created_at}\n";
    
    // Test du mot de passe
    if (password_verify('Admin@2024!', $admin->password)) {
        echo "✅ Mot de passe 'Admin@2024!' est CORRECT\n";
    } else {
        echo "❌ Le mot de passe ne correspond pas\n";
    }
} else {
    echo "❌ Admin non trouvé\n";
}
