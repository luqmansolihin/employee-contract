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
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'proposed_start_date' => $date,
            'proposed_end_date' => $date->copy()->addYear(),
            'basic_salary' => 5000000,
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
            'amendment_reason' => 'Perpanjangan Masa Berlaku',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('DASHBOARD');
        $response->assertSeeText('Alur Siklus Kepegawaian');
        $response->assertSee('Total Karyawan Terdaftar');
        $response->assertSee('Offering Letter');
        $response->assertSee('Kontrak Kerja Aktif');
        $response->assertSee('Total Adendum Diterbitkan');
        $response->assertSee('Peringatan: Kontrak Segera Berakhir');
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
