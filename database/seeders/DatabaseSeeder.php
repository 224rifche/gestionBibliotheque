<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'login' => 'ADMIN001',
            'password' => Hash::make('Admin@123'),
            'role' => 'Radmin',
            'actif' => true,
        ]);
        $this->call(UserSeeder::class);
    }
}
