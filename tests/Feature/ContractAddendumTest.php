<?php

namespace Tests\Feature;

use App\Models\ContractAddendum;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractAddendumTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Employee $employee;

    protected EmployeeContract $contract;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
        $this->actingAs($this->user);

        $this->employee = Employee::factory()->create([
            'name' => 'Bambang Sudiro',
            'current_position' => 'Backend Developer',
            'current_branch' => 'Jakarta',
            'first_join_date' => '2025-01-01',
            'current_contract_end_date' => '2025-12-31',
        ]);

        $this->contract = EmployeeContract::create([
            'employee_id' => $this->employee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/I/2025/PKWT',
            'contract_type' => 'PKWT',
            'position' => 'Backend Developer',
            'branch' => 'Jakarta',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'basic_salary' => 8000000,
            'status' => 'active',
        ]);
    }

    public function test_can_render_addendums_index(): void
    {
        $response = $this->get(route('addendums.index'));

        $response->assertOk();
        $response->assertSee('Adendum Perjanjian Kerja');
    }

    public function test_can_render_create_addendum_page(): void
    {
        $response = $this->get(route('addendums.create', $this->contract));

        $response->assertOk();
        $response->assertSee('Penerbitan Adendum I');
        $response->assertSee('untuk Kontrak');
        $response->assertSee('Bambang Sudiro');
        $response->assertSee('/A-PKWT');
    }

    public function test_can_store_addendum_and_extend_contract_end_date(): void
    {
        $payload = [
            'addendum_number' => '001/IX/2026/A-PKWT',
            'issue_date' => '2025-12-15',
            'effective_date' => '2026-01-01',
            'new_end_date' => '2026-12-31',
            'new_position' => 'Senior Backend Developer',
            'new_salary' => 11000000,
            'amendment_reason' => 'Perpanjangan Masa Berlaku 1 Tahun & Promosi Jabatan Senior',
            'clause_changes' => 'Pasal 1 masa kerja diperpanjang hingga 31 Desember 2026.',
        ];

        $response = $this->post(route('addendums.store', $this->contract), $payload);

        $this->assertDatabaseHas('contract_addendums', [
            'employee_contract_id' => $this->contract->id,
            'addendum_number' => '001/IX/2026/A-PKWT',
            'addendum_sequence' => 1,
            'new_position' => 'Senior Backend Developer',
            'new_salary' => 11000000,
        ]);

        // Contract end date and salary should be updated to the addendum terms
        $this->contract->refresh();
        $this->assertEquals('2026-12-31', $this->contract->end_date->format('Y-m-d'));
        $this->assertEquals('Senior Backend Developer', $this->contract->position);
        $this->assertEquals(11000000, (float) $this->contract->basic_salary);

        // Employee cached data should also reflect the extended contract
        $this->employee->refresh();
        $this->assertEquals('2026-12-31', $this->employee->current_contract_end_date->format('Y-m-d'));
        $this->assertEquals('Senior Backend Developer', $this->employee->current_position);

        $response->assertRedirect(route('contracts.show', $this->contract));
    }

    public function test_can_render_print_addendum_document(): void
    {
        $addendum = ContractAddendum::create([
            'employee_id' => $this->employee->id,
            'employee_contract_id' => $this->contract->id,
            'addendum_number' => '001/IX/2026/A-PKWT',
            'addendum_sequence' => 1,
            'issue_date' => '2025-12-15',
            'effective_date' => '2026-01-01',
            'previous_end_date' => '2025-12-31',
            'new_end_date' => '2026-12-31',
            'previous_position' => 'Backend Developer',
            'new_position' => 'Senior Backend Developer',
            'previous_salary' => 8000000,
            'new_salary' => 11000000,
            'amendment_reason' => 'Perpanjangan Masa Berlaku 1 Tahun',
            'clause_changes' => 'Pasal 1 diperpanjang.',
            'status' => 'active',
        ]);

        $response = $this->get(route('addendums.print', $addendum));

        $response->assertOk();
        $response->assertSee('SURAT ADENDUM Adendum I');
        $response->assertSee('001/IX/2026/A-PKWT');
        $response->assertSee('Bambang Sudiro');
    }
}
