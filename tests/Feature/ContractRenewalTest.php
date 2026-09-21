<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractRenewalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_contract_renewal_page(): void
    {
        $employee = Employee::factory()->active()->create();

        $response = $this->get(route('employees.renew', $employee));

        $response->assertOk();
        $response->assertSee('Perpanjangan Kontrak Kerja');
        $response->assertSee('Detail Kontrak Perpanjangan (#2)');
    }

    public function test_can_store_contract_renewal_without_losing_old_history(): void
    {
        $employee = Employee::factory()->active()->create([
            'name' => 'Budi Sudarsono',
            'current_position' => 'Junior Staff',
            'current_branch' => 'Jakarta Pusat',
            'first_join_date' => '2025-01-01',
            'current_contract_end_date' => '2025-12-31',
        ]);

        // Verify initial contract exists (sequence = 1)
        $this->assertEquals(1, $employee->contracts()->count());
        $initialContract = $employee->contracts()->first();
        $this->assertEquals(1, $initialContract->contract_sequence);
        $this->assertEquals('active', $initialContract->status);

        // Renew contract (sequence = 2)
        $payload = [
            'contract_number' => '002/PKWT-EXT/I/2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'position' => 'Senior Staff', // Promoted!
            'branch' => 'Surabaya', // Transferred!
            'notes' => 'Perpanjangan tahun ke-2 dengan promosi jabatan dan mutasi cabang.',
        ];

        $response = $this->post(route('employees.renew.store', $employee), $payload);

        $response->assertRedirect(route('employees.show', $employee));

        // Refresh employee and check contracts count
        $employee->refresh();
        $this->assertEquals(2, $employee->contracts()->count());

        // Check that initial contract is still preserved in history and marked as renewed
        $initialContract->refresh();
        $this->assertEquals('renewed', $initialContract->status);
        $this->assertEquals('Junior Staff', $initialContract->position);

        // Check new contract details
        $newContract = $employee->latestContract;
        $this->assertEquals(2, $newContract->contract_sequence);
        $this->assertEquals('active', $newContract->status);
        $this->assertEquals('Senior Staff', $newContract->position);
        $this->assertEquals('Surabaya', $newContract->branch);
        $this->assertEquals('2026-12-31', $newContract->end_date->format('Y-m-d'));

        // Check employee cached summary columns
        $this->assertEquals('Senior Staff', $employee->current_position);
        $this->assertEquals('Surabaya', $employee->current_branch);
        $this->assertEquals('2026-12-31', $employee->current_contract_end_date->format('Y-m-d'));

        // Check employee show page shows both contracts in timeline
        $showResponse = $this->get(route('employees.show', $employee));
        $showResponse->assertOk();
        $showResponse->assertSee('Kontrak #1 (Awal)');
        $showResponse->assertSee('Kontrak #2 (Perpanjangan 1)');
        $showResponse->assertSee('Junior Staff');
        $showResponse->assertSee('Senior Staff');
        $showResponse->assertSee('Perpanjangan tahun ke-2 dengan promosi jabatan');
    }

    public function test_validates_renewal_end_date_must_be_after_start_date(): void
    {
        $employee = Employee::factory()->active()->create();

        $payload = [
            'start_date' => '2026-06-01',
            'end_date' => '2026-05-01', // Invalid: before start date
            'position' => 'Staff',
            'branch' => 'Bandung',
        ];

        $response = $this->post(route('employees.renew.store', $employee), $payload);

        $response->assertSessionHasErrors('end_date');
        $this->assertEquals(1, $employee->contracts()->count());
    }

    public function test_can_chain_multiple_contract_renewals(): void
    {
        // Employee with 2 renewals already (contracts #1, #2, #3)
        $employee = Employee::factory()->active()->withRenewals(2)->create();

        $this->assertEquals(3, $employee->contracts()->count());

        // Renew again to contract #4
        $payload = [
            'contract_number' => '004/PKWT-EXT/2027',
            'start_date' => Carbon::parse($employee->current_contract_end_date)->addDay()->toDateString(),
            'end_date' => Carbon::parse($employee->current_contract_end_date)->addDay()->addYear()->toDateString(),
            'position' => 'Department Lead',
            'branch' => $employee->current_branch,
            'notes' => 'Perpanjangan kontrak ke-3 menjadi Department Lead.',
        ];

        $response = $this->post(route('employees.renew.store', $employee), $payload);

        $response->assertRedirect(route('employees.show', $employee));
        $employee->refresh();

        $this->assertEquals(4, $employee->contracts()->count());
        $this->assertEquals('Department Lead', $employee->current_position);
    }
}
