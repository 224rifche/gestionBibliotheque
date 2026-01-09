<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'login' => 'ADMIN001',
            'password' => Hash::make('Admin@123'),
            'role' => 'Radmin',
            'actif' => true,
        ]);

        User::create([
            'login' => 'BIBLIO001',
            'password' => Hash::make('Biblio@123'),
            'role' => 'Rbibliothecaire',
            'actif' => true,
        ]);

        User::create([
            'login' => 'ETU001',
            'password' => Hash::make('Etudiant@123'),
            'role' => 'Rlecteur',
            'actif' => true,
        ]);
    }
}
