<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin Account
        User::factory()->admin()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        // 2. Staff HRD Account
        User::factory()->staff()->create([
            'name' => 'Staff HRD',
            'email' => 'hr@example.com',
        ]);

        $this->call([
            EmployeeSeeder::class,
        ]);
    }
}
