<?php

namespace Tests\Feature;

use App\Models\ContractAddendum;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard_with_kpi_metrics(): void
    {
        $user = User::factory()->admin()->create();

        // Seed some sample data
        $employee = Employee::factory()->active()->create();
        $date = Carbon::create(2026, 9, 21);

        OfferingLetter::create([
            'employee_id' => $employee->id,
            'letter_number' => '001/IX/2026/OL',
            'offer_date' => $date,
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'proposed_start_date' => $date,
            'proposed_end_date' => $date->copy()->addYear(),
            'status' => 'draft',
        ]);

        $contract = EmployeeContract::create([
            'employee_id' => $employee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/IX/2026/PKWT',
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => $date,
            'end_date' => $date->copy()->addDays(20), // expiring soon (< 30 days)
            'status' => 'active',
        ]);

        ContractAddendum::create([
            'employee_id' => $employee->id,
            'employee_contract_id' => $contract->id,
            'addendum_sequence' => 1,
            'addendum_number' => '001/IX/2026/A-PKWT',
            'issue_date' => $date,
            'effective_date' => $date,
            'previous_end_date' => $contract->end_date,
            'new_end_date' => $date->copy()->addYear(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('DASHBOARD');
        $response->assertDontSee('Alur Siklus Kepegawaian');
        $response->assertDontSee('Input Karyawan');
        $response->assertDontSee('Terbitkan Kontrak');
        $response->assertSee('Total Karyawan Terdaftar');
        $response->assertSee('Offering Letter');
        $response->assertSee('Kontrak Kerja Aktif');
        $response->assertSee('Total Adendum Diterbitkan');
        $response->assertSee('Peringatan: Kontrak Segera Berakhir');
        $response->assertSee('Peringatan: Kontrak Telah Berakhir (Expired)');
    }

    public function test_authenticated_user_can_view_expired_contracts_warning_on_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        // 1. Employee with expired contract (latest contract is expired)
        $expiredEmployee = Employee::factory()->create(['name' => 'Budi Expired']);
        EmployeeContract::create([
            'employee_id' => $expiredEmployee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/EXP/2026',
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => now()->subMonths(6),
            'end_date' => now()->subDays(5),
            'status' => 'expired',
        ]);

        // 2. Employee with renewed contract (old contract expired/superseded, but current is active)
        $renewedEmployee = Employee::factory()->create(['name' => 'Siti Active']);
        EmployeeContract::create([
            'employee_id' => $renewedEmployee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/OLD/2025',
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => now()->subYears(2),
            'end_date' => now()->subYear(),
            'status' => 'renewed',
        ]);
        EmployeeContract::create([
            'employee_id' => $renewedEmployee->id,
            'contract_sequence' => 2,
            'contract_number' => '002/CURR/2026',
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(5),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('expiredContractsCount', 1);
        $expiredContracts = $response->viewData('expiredContracts');
        $this->assertCount(1, $expiredContracts);
        $this->assertEquals($expiredEmployee->id, $expiredContracts->first()->employee_id);
        $response->assertSee('Budi Expired');
        $response->assertSee('Lewat 5 Hari');
    }

    public function test_root_url_renders_dashboard(): void
    {
        $user = User::factory()->staff()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('DASHBOARD');
    }
}
