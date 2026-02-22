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
        // Buat superadmin hanya jika belum ada (hindari duplicate saat seed ulang)
        User::firstOrCreate([
            'username' => 'superadmin',
        ], [
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'), // hash password here to keep consistent with controllers
            'role' => 'Superadmin',
            'status' => true,
            'approved' => true // Superadmin tidak perlu approval
        ]);

        // Seed Jenis Barang - 2 pilihan utama
        JenisBarang::create(['name' => 'Modal']);
        JenisBarang::create(['name' => 'Barang Habis Pakai']);
    }
}
