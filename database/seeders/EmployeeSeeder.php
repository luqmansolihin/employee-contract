<?php

namespace Database\Seeders;

use App\Models\ContractAddendum;
use App\Models\Employee;
use App\Models\OfferingLetter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds with rich, realistic, and valid HR data.
     */
    public function run(): void
    {
        $supervisorName = 'Hendra Wijaya, S.Psi.';
        $supervisorPosition = 'Human Resources Manager';
        $officeAddress = 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat';

        // 1. 8 active single-contract employees (Kontrak #1)
        $activeEmployees = Employee::factory()->count(8)->active()->create();

        // 2. 4 employees with contract extensions / history (PKWT #2 & PKWT #3)
        $renewedEmployees1 = Employee::factory()->count(2)->active()->withRenewals(1)->create();
        $renewedEmployees2 = Employee::factory()->count(2)->active()->withRenewals(2)->create();

        // 3. 5 contracts expiring soon (within 30 days)
        $expiringEmployees = Employee::factory()->count(5)->expiringSoon()->create();

        // 4. 3 expired contracts
        $expiredEmployees = Employee::factory()->count(3)->expired()->create();

        // 5. Seed realistic Offering Letters across various statuses
        $allEmployees = Employee::all();

        // Seed accepted offering letters linked to first contract for the first 5 employees
        foreach ($allEmployees->take(5) as $index => $emp) {
            $num = sprintf('%03d/OL/HRD/IX/2026', $index + 1);
            $contract = $emp->contracts()->first();

            $ol = OfferingLetter::create([
                'employee_id' => $emp->id,
                'letter_number' => $num,
                'kode' => 'HRD',
                'offer_date' => Carbon::parse($emp->first_join_date)->subDays(7),
                'position' => $emp->current_position ?? 'Staff Operasional',
                'bidang' => 'Operasional',
                'branch' => $emp->current_branch ?? 'Jakarta Pusat',
                'proposed_start_date' => $emp->first_join_date,
                'proposed_end_date' => $emp->current_contract_end_date ?? Carbon::parse($emp->first_join_date)->addYear(),
                'status' => 'accepted',
                'supervisor_name' => $supervisorName,
                'supervisor_position' => $supervisorPosition,
                'office_address' => $officeAddress,
            ]);

            if ($contract) {
                $contract->update(['offering_letter_id' => $ol->id]);
            }
        }

        // Seed sent, draft, and rejected offering letters for subsequent employees
        if ($allEmployees->count() >= 8) {
            // Sent
            OfferingLetter::create([
                'employee_id' => $allEmployees[5]->id,
                'letter_number' => '006/OL/IT/IX/2026',
                'kode' => 'IT',
                'offer_date' => Carbon::today()->subDays(2),
                'position' => 'Senior Backend Developer',
                'bidang' => 'Teknologi Informasi',
                'branch' => 'Jakarta Selatan',
                'proposed_start_date' => Carbon::today()->addDays(14),
                'proposed_end_date' => Carbon::today()->addDays(14)->addYear(),
                'status' => 'sent',
                'supervisor_name' => $supervisorName,
                'supervisor_position' => $supervisorPosition,
                'office_address' => $officeAddress,
            ]);

            // Draft
            OfferingLetter::create([
                'employee_id' => $allEmployees[6]->id,
                'letter_number' => '007/OL/FIN/IX/2026',
                'kode' => 'FIN',
                'offer_date' => Carbon::today(),
                'position' => 'Finance & Accounting Specialist',
                'bidang' => 'Keuangan',
                'branch' => 'Jakarta Pusat',
                'proposed_start_date' => Carbon::today()->addMonth(),
                'proposed_end_date' => Carbon::today()->addMonth()->addYear(),
                'status' => 'draft',
                'supervisor_name' => $supervisorName,
                'supervisor_position' => $supervisorPosition,
                'office_address' => $officeAddress,
            ]);

            // Rejected
            OfferingLetter::create([
                'employee_id' => $allEmployees[7]->id,
                'letter_number' => '008/OL/MKT/IX/2026',
                'kode' => 'MKT',
                'offer_date' => Carbon::today()->subWeeks(2),
                'position' => 'Digital Marketing Lead',
                'bidang' => 'Pemasaran',
                'branch' => 'Bandung',
                'proposed_start_date' => Carbon::today()->subWeek(),
                'proposed_end_date' => Carbon::today()->subWeek()->addYear(),
                'status' => 'rejected',
                'supervisor_name' => $supervisorName,
                'supervisor_position' => $supervisorPosition,
                'office_address' => $officeAddress,
            ]);
        }

        // 6. Seed valid Contract Addendums for renewed employees
        foreach ($renewedEmployees1 as $idx => $emp) {
            $latestContract = $emp->latestContract;
            if ($latestContract) {
                $effectiveDate = $latestContract->end_date->copy()->subMonths(3);
                $newEndDate = $latestContract->end_date->copy()->addMonths(6);

                ContractAddendum::create([
                    'employee_id' => $emp->id,
                    'employee_contract_id' => $latestContract->id,
                    'addendum_number' => sprintf('%03d/IX/2026/A-PKWT/HRD', $idx + 1),
                    'kode' => 'HRD',
                    'addendum_sequence' => 1,
                    'issue_date' => $effectiveDate->copy()->subDays(5),
                    'effective_date' => $effectiveDate,
                    'previous_end_date' => $latestContract->end_date,
                    'new_end_date' => $newEndDate,
                    'previous_position' => $latestContract->position,
                    'new_position' => $latestContract->position,
                    'bidang' => 'Operasional',
                    'branch' => $latestContract->branch,
                    'supervisor_name' => $supervisorName,
                    'supervisor_position' => $supervisorPosition,
                    'office_address' => $officeAddress,
                    'status' => 'active',
                ]);
            }
        }
    }
}
