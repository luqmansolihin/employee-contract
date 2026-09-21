<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 8 active single-contract employees (Kontrak #1)
        Employee::factory()->count(8)->active()->create();

        // 4 employees with contract extensions / history (PKWT #2 & PKWT #3)
        Employee::factory()->count(2)->active()->withRenewals(1)->create();
        Employee::factory()->count(2)->active()->withRenewals(2)->create();

        // 5 contracts expiring soon (within 30 days)
        Employee::factory()->count(5)->expiringSoon()->create();

        // 3 expired contracts
        Employee::factory()->count(3)->expired()->create();
    }
}
