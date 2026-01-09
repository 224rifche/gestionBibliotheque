<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-admin-user {login?}', function (?string $login = null) {
    $login = $login ?: $this->ask('Login');

    if (! is_string($login) || $login === '') {
        $this->error('Login invalide.');
        return Command::FAILURE;
    }

    if (! preg_match('/^[A-Za-z0-9]+$/', $login)) {
        $this->error('Login invalide (caractères autorisés: A-Z a-z 0-9).');
        return Command::FAILURE;
    }

    if (User::query()->where('login', $login)->exists()) {
        $this->error('Ce login existe déjà.');
        return Command::FAILURE;
    }

    $password = $this->secret('Mot de passe');
    $passwordConfirm = $this->secret('Confirmer le mot de passe');

    if (! is_string($password) || $password === '') {
        $this->error('Mot de passe invalide.');
        return Command::FAILURE;
    }

    if ($password !== $passwordConfirm) {
        $this->error('Les mots de passe ne correspondent pas.');
        return Command::FAILURE;
    }

    User::create([
        'login' => $login,
        'password' => Hash::make($password),
        'role' => 'Radmin',
        'actif' => true,
    ]);

    $this->info('Admin créé avec succès.');
    $this->line('Login: '.$login);
    $this->line('Role: Radmin');

    return Command::SUCCESS;
})->purpose('Créer un superuser/admin (Radmin)');
