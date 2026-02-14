<?php

// Script PHP pour créer un admin directement
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Créer l'admin
$admin = User::create([
    'login' => 'ADMIN001',
    'password' => Hash::make('Admin@2024!'),
    'role' => 'Radmin',
    'actif' => true,
]);

echo "✅ Admin créé avec succès !\n";
echo "Login: ADMIN001\n";
echo "Mot de passe: Admin@2024!\n";
echo "ID: {$admin->id}\n";
