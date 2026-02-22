<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\JenisBarang;
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
        // Pastikan superadmin selalu memiliki password yang diketahui saat seeding
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@example.com',
                'password' => Hash::make('12345678'), // reset/hardcode password for local dev seeder
                'role' => 'Superadmin',
                'status' => true,
                'approved' => true, // Superadmin tidak perlu approval
            ]
        );

        // Seed Jenis Barang - 2 pilihan utama
        JenisBarang::create(['name' => 'Modal']);
        JenisBarang::create(['name' => 'Barang Habis Pakai']);
    }
}
