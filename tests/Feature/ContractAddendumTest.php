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
            'kode' => 'HRD',
            'contract_type' => 'PKWT',
            'contract_date' => '2025-01-01',
            'position' => 'Backend Developer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'basic_salary' => 8000000,
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Perkantoran Sudirman Central Lt. 12 Jakarta',
            'status' => 'active',
        ]);
    }

    public function test_can_render_addendums_index(): void
    {
        $response = $this->get(route('addendums.index'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('ADENDUM');
    }

    public function test_can_render_create_addendum_page(): void
    {
        $response = $this->get(route('addendums.create', $this->contract));

        $response->assertOk();
        $response->assertSee('Penerbitan Adendum I');
        $response->assertSee('untuk Kontrak');
        $response->assertSee('Bambang Sudiro');
        $response->assertSee('/A-PKWT');
        $response->assertSee('HRD');
        $response->assertSee('Teknologi Informasi');
        $response->assertSee('Hendra Wijaya, S.Psi.');
        $response->assertSee('Human Resources Manager');
        $response->assertSee('Gedung Perkantoran Sudirman Central Lt. 12 Jakarta');

        // Point 3 and Point 5 should not be in the form
        $response->assertDontSee('Penyesuaian Jabatan & Gaji');
        $response->assertDontSee('Alasan & Klausul Perubahan');
        $response->assertDontSee('name="new_position"', false);
        $response->assertDontSee('name="new_salary"', false);
        $response->assertDontSee('name="amendment_reason"', false);
        $response->assertDontSee('name="clause_changes"', false);
    }

    public function test_can_store_addendum_and_extend_contract_end_date(): void
    {
        $payload = [
            'addendum_number' => '001/IX/2026/A-PKWT',
            'kode' => 'HRD',
            'issue_date' => '2025-12-15',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Bandung',
            'effective_date' => '2026-01-01',
            'new_end_date' => '2026-12-31',
            'supervisor_name' => 'Budi Santoso',
            'supervisor_position' => 'General Manager',
            'office_address' => 'Jl. Asia Afrika No. 10 Bandung',
        ];

        $response = $this->post(route('addendums.store', $this->contract), $payload);

        $this->assertDatabaseHas('contract_addendums', [
            'employee_contract_id' => $this->contract->id,
            'addendum_number' => '001/IX/2026/A-PKWT/HRD',
            'kode' => 'HRD',
            'addendum_sequence' => 1,
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Bandung',
            'new_position' => 'Backend Developer',
            'new_salary' => 8000000,
            'amendment_reason' => 'Perpanjangan Masa Berlaku Perjanjian Kerja',
            'supervisor_name' => 'Budi Santoso',
            'supervisor_position' => 'General Manager',
            'office_address' => 'Jl. Asia Afrika No. 10 Bandung',
        ]);

        // Contract end date and branch should be updated to the addendum terms
        $this->contract->refresh();
        $this->assertEquals('2026-12-31', $this->contract->end_date->format('Y-m-d'));
        $this->assertEquals('Backend Developer', $this->contract->position);
        $this->assertEquals('Bandung', $this->contract->branch);
        $this->assertEquals('Budi Santoso', $this->contract->supervisor_name);
        $this->assertEquals(8000000, (float) $this->contract->basic_salary);

        // Employee cached data should also reflect the extended contract
        $this->employee->refresh();
        $this->assertEquals('2026-12-31', $this->employee->current_contract_end_date->format('Y-m-d'));
        $this->assertEquals('Backend Developer', $this->employee->current_position);
        $this->assertEquals('Bandung', $this->employee->current_branch);

        $response->assertRedirect(route('contracts.show', $this->contract));
    }

    public function test_can_render_show_addendum_page(): void
    {
        $addendum = ContractAddendum::create([
            'employee_id' => $this->employee->id,
            'employee_contract_id' => $this->contract->id,
            'addendum_number' => '001/IX/2026/A-PKWT/HRD',
            'kode' => 'HRD',
            'addendum_sequence' => 1,
            'issue_date' => '2025-12-15',
            'effective_date' => '2026-01-01',
            'previous_end_date' => '2025-12-31',
            'new_end_date' => '2026-12-31',
            'previous_position' => 'Backend Developer',
            'new_position' => 'Senior Backend Developer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'previous_salary' => 8000000,
            'new_salary' => 11000000,
            'amendment_reason' => 'Perpanjangan Masa Berlaku 1 Tahun',
            'clause_changes' => 'Pasal 1 diperpanjang.',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Perkantoran Sudirman Central Lt. 12 Jakarta',
            'status' => 'active',
        ]);

        $response = $this->get(route('addendums.show', $addendum));

        $response->assertOk();
        $response->assertSee('001/IX/2026/A-PKWT/HRD');
        $response->assertSee('HRD');
        $response->assertSee('Teknologi Informasi');
        $response->assertSee('Hendra Wijaya, S.Psi.');
        $response->assertSee('Gedung Perkantoran Sudirman Central Lt. 12 Jakarta');

        // Gaji pokok, pokok alasan perubahan, and klausul yang diubah should be removed from show page
        $response->assertDontSee('Gaji Pokok / Saku');
        $response->assertDontSee('Pokok Alasan Perubahan');
        $response->assertDontSee('Klausul / Pasal yang Diubah');
    }

    public function test_can_render_print_addendum_document(): void
    {
        $addendum = ContractAddendum::create([
            'employee_id' => $this->employee->id,
            'employee_contract_id' => $this->contract->id,
            'addendum_number' => '001/IX/2026/A-PKWT/HRD',
            'kode' => 'HRD',
            'addendum_sequence' => 1,
            'issue_date' => '2025-12-15',
            'effective_date' => '2026-01-01',
            'previous_end_date' => '2025-12-31',
            'new_end_date' => '2026-12-31',
            'previous_position' => 'Backend Developer',
            'new_position' => 'Senior Backend Developer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'previous_salary' => 8000000,
            'new_salary' => 11000000,
            'amendment_reason' => 'Perpanjangan Masa Berlaku 1 Tahun',
            'clause_changes' => 'Pasal 1 diperpanjang.',
            'supervisor_name' => 'Budi Santoso',
            'supervisor_position' => 'Director',
            'office_address' => 'Menara BCA Lt. 20 Jakarta',
            'status' => 'active',
        ]);

        $response = $this->get(route('addendums.print', $addendum));

        $response->assertOk();
        $response->assertSee('SURAT ADENDUM Adendum I');
        $response->assertSee('001/IX/2026/A-PKWT/HRD');
        $response->assertSee('Bambang Sudiro');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Director');
        $response->assertSee('Menara BCA Lt. 20 Jakarta');
    }
}
