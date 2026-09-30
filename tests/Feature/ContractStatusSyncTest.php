<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\User;
use App\Services\ContractStatusService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractStatusSyncTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
    }

    public function test_past_active_contracts_are_updated_to_expired(): void
    {
        $employee = Employee::factory()->create([
            'current_contract_end_date' => Carbon::today()->subDays(10)->toDateString(),
        ]);

        $contract = $employee->contracts()->first();
        $contract->update([
            'end_date' => Carbon::today()->subDays(10)->toDateString(),
            'status' => 'active',
        ]);

        $results = ContractStatusService::syncStatuses();

        $this->assertGreaterThanOrEqual(1, $results['expired_count']);
        $contract->refresh();
        $this->assertEquals('expired', $contract->status);
    }

    public function test_superseded_active_contracts_are_updated_to_renewed(): void
    {
        $employee = Employee::factory()->create([
            'current_contract_end_date' => Carbon::today()->addYear()->toDateString(),
        ]);

        // Existing contract is sequence 1, force status active
        $c1 = $employee->contracts()->first();
        $c1->update([
            'status' => 'active',
            'end_date' => Carbon::today()->addMonths(6),
        ]);

        // Create sequence 2, also left as active
        $c2 = EmployeeContract::create([
            'employee_id' => $employee->id,
            'contract_sequence' => 2,
            'contract_number' => '002/PKWT/2026',
            'contract_type' => 'PKWT',
            'position' => 'Staff IT',
            'branch' => 'Jakarta',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addMonths(8)->toDateString(),
            'status' => 'active',
        ]);

        // Create sequence 3 (latest), ending in 1 year
        $c3 = EmployeeContract::create([
            'employee_id' => $employee->id,
            'contract_sequence' => 3,
            'contract_number' => '003/PKWT/2026',
            'contract_type' => 'PKWT',
            'position' => 'Senior IT',
            'branch' => 'Surabaya',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addYear()->toDateString(),
            'status' => 'active',
        ]);

        ContractStatusService::syncStatuses($employee);

        $c1->refresh();
        $c2->refresh();
        $c3->refresh();
        $employee->refresh();

        // Contract 1 and 2 should be marked as renewed because they are superseded
        $this->assertEquals('renewed', $c1->status);
        $this->assertEquals('renewed', $c2->status);

        // Contract 3 is the latest ongoing contract, so it remains active
        $this->assertEquals('active', $c3->status);

        // Employee cached data synced with latest contract
        $this->assertEquals($c3->end_date->toDateString(), $employee->current_contract_end_date->toDateString());
        $this->assertEquals('Senior IT', $employee->current_position);
        $this->assertEquals('Surabaya', $employee->current_branch);
    }

    public function test_dashboard_only_shows_latest_active_contract_and_ignores_superseded_contracts(): void
    {
        // Employee 1: has 3 contracts (User scenario)
        // Contract 1: renewed, ending next year
        // Contract 2: renewed, ending in 10 days (<= 30 days)
        // Contract 3: expired, ended last month
        $emp1 = Employee::factory()->create([
            'name' => 'Emp One',
            'current_contract_end_date' => Carbon::today()->subMonth()->toDateString(),
        ]);
        $emp1->contracts()->delete();

        EmployeeContract::create([
            'employee_id' => $emp1->id,
            'contract_sequence' => 1,
            'contract_number' => '001/PKWT/2026',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => Carbon::today()->subYear()->toDateString(),
            'end_date' => Carbon::today()->addYear()->toDateString(),
            'status' => 'renewed',
        ]);

        EmployeeContract::create([
            'employee_id' => $emp1->id,
            'contract_sequence' => 2,
            'contract_number' => '002/PKWT/2026',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => Carbon::today()->subMonths(6)->toDateString(),
            'end_date' => Carbon::today()->addDays(10)->toDateString(), // ending in 10 days, but superseded!
            'status' => 'renewed',
        ]);

        EmployeeContract::create([
            'employee_id' => $emp1->id,
            'contract_sequence' => 3,
            'contract_number' => '003/PKWT/2026',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => Carbon::today()->subMonths(3)->toDateString(),
            'end_date' => Carbon::today()->subMonth()->toDateString(), // expired
            'status' => 'expired',
        ]);

        // Employee 2: single contract, expiring in 15 days (should appear on dashboard!)
        $emp2 = Employee::factory()->create([
            'name' => 'Emp Two',
            'current_contract_end_date' => Carbon::today()->addDays(15)->toDateString(),
        ]);
        $emp2->contracts()->delete();

        $validExpiringContract = EmployeeContract::create([
            'employee_id' => $emp2->id,
            'contract_sequence' => 1,
            'contract_number' => 'EXP-VALID/PKWT/2026',
            'position' => 'Support Specialist',
            'branch' => 'Bandung',
            'start_date' => Carbon::today()->subMonths(11)->toDateString(),
            'end_date' => Carbon::today()->addDays(15)->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();

        // Check expiring contracts viewData
        $expiringContracts = $response->viewData('expiringContracts');
        $expiringContractsCount = $response->viewData('expiringContractsCount');

        $this->assertEquals(1, $expiringContractsCount);
        $this->assertCount(1, $expiringContracts);
        $this->assertEquals('EXP-VALID/PKWT/2026', $expiringContracts->first()->contract_number);

        // Should see Employee 2's expiring contract on the page
        $response->assertSee('Emp Two');
        $response->assertSee('EXP-VALID/PKWT/2026');
    }

    public function test_artisan_command_syncs_contract_statuses(): void
    {
        $employee = Employee::factory()->create([
            'current_contract_end_date' => Carbon::today()->subDays(5)->toDateString(),
        ]);

        $contract = $employee->contracts()->first();
        $contract->update([
            'end_date' => Carbon::today()->subDays(5)->toDateString(),
            'status' => 'active',
        ]);

        $this->artisan('contracts:sync-statuses')
            ->expectsOutputToContain('Contract statuses synchronization completed successfully')
            ->assertExitCode(0);

        $contract->refresh();
        $this->assertEquals('expired', $contract->status);
    }
}
