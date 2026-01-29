<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jika kamu mau buat user dummy, bisa pakai ini:
        // User::factory(10)->create();

        // Ini user Superadmin yang akan dibuat otomatis
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('12345678'), // Ubah password ini di produksi
            'role' => 'Superadmin',
            'status' => true
        ]);
    }
}
