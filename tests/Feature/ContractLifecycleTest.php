<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
        $this->actingAs($this->user);

        $this->employee = Employee::factory()->create([
            'name' => 'Maya Indah',
            'current_position' => 'HR Specialist',
            'current_branch' => 'Surabaya',
        ]);
    }

    public function test_can_render_contracts_index(): void
    {
        $response = $this->get(route('contracts.index'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('KONTRAK');
        $response->assertSee('PKWT');
        $response->assertSee('MT');
        $response->assertSee('MAGANG');
    }

    public function test_can_render_standalone_create_contract_page_with_searchable_combobox(): void
    {
        $response = $this->get(route('contracts.create'));

        $response->assertOk();
        $response->assertSee('Pilih Karyawan');
        $response->assertSee('employee-combobox-wrapper');
        $response->assertSee('selected_employee_card');
        $response->assertSee($this->employee->name);
        $response->assertSee('Nomor Kontrak Kerja');
        $response->assertSee('Kode');
        $response->assertSee('Tanggal Surat');
        $response->assertSee('Bidang');
        $response->assertSee('Nama Atasan');
        $response->assertSee('Jabatan Atasan');
        $response->assertSee('Alamat Kantor');
        $response->assertDontSee('3. Remunerasi & Catatan');
        $response->assertDontSee('Gaji Pokok / Uang Saku');
        $response->assertDontSee('Tunjangan Lainnya');
    }

    public function test_can_create_contract_from_accepted_offering_letter(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'kode' => 'HRD',
            'offer_date' => '2026-09-21',
            'contract_type' => 'MT',
            'position' => 'Management Trainee HR',
            'bidang' => 'Human Resources',
            'branch' => 'Surabaya',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'HR Director',
            'office_address' => 'Jl. Pemuda No. 45 Surabaya',
            'basic_salary' => 6000000,
            'status' => 'accepted',
        ]);

        $createResponse = $this->get(route('contracts.create', ['offering_letter_id' => $ol->id]));
        $createResponse->assertOk();
        $createResponse->assertSee('Merujuk ke Offering Letter #001/IX/2026/OL');
        $createResponse->assertSee('Merujuk ke Offering Letter');
        $createResponse->assertSee('001/IX/2026/OL');
        $createResponse->assertSee('Management Trainee HR');
        $createResponse->assertSee('Human Resources');
        $createResponse->assertSee('Hendra Wijaya, S.Psi.');
        $createResponse->assertSee('HR Director');
        $createResponse->assertSee('Jl. Pemuda No. 45 Surabaya');

        $payload = [
            'employee_id' => $this->employee->id,
            'offering_letter_id' => $ol->id,
            'contract_number' => '001/IX/2026/MT',
            'kode' => 'HRD',
            'contract_date' => '2026-09-24',
            'contract_type' => 'MT',
            'position' => 'Management Trainee HR',
            'bidang' => 'Human Resources',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'HR Director',
            'office_address' => 'Jl. Pemuda No. 45 Surabaya',
        ];

        $storeResponse = $this->post(route('contracts.store'), $payload);

        $this->assertDatabaseHas('employee_contracts', [
            'employee_id' => $this->employee->id,
            'offering_letter_id' => $ol->id,
            'contract_number' => '001/IX/2026/MT/HRD',
            'kode' => 'HRD',
            'contract_type' => 'MT',
            'bidang' => 'Human Resources',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'HR Director',
            'office_address' => 'Jl. Pemuda No. 45 Surabaya',
            'status' => 'active',
        ]);

        $contract = EmployeeContract::where('contract_number', '001/IX/2026/MT/HRD')->first();
        $this->assertNotNull($contract);
        $this->assertEquals('2026-09-24', $contract->contract_date->format('Y-m-d'));
        $storeResponse->assertRedirect(route('contracts.show', $contract));

        // Employee cached contract info should be synced
        $this->employee->refresh();
        $this->assertEquals('Management Trainee HR', $this->employee->current_position);
        $this->assertEquals('2027-09-30', $this->employee->current_contract_end_date->format('Y-m-d'));
    }

    public function test_can_render_print_contract_agreement(): void
    {
        $contract = EmployeeContract::create([
            'employee_id' => $this->employee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/IX/2026/PKWT',
            'kode' => 'HRD',
            'contract_date' => '2026-09-24',
            'contract_type' => 'PKWT',
            'position' => 'HR Specialist',
            'bidang' => 'Human Resources',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Sudirman Central Lt. 12, Jakarta',
            'basic_salary' => 7000000,
            'status' => 'active',
        ]);

        $response = $this->get(route('contracts.print', $contract));

        $response->assertOk();
        $response->assertSee('SURAT PERJANJIAN KERJA WAKTU TERTENTU (PKWT)');
        $response->assertSee('Maya Indah');
        $response->assertSee('001/IX/2026/PKWT');
        $response->assertSee('Human Resources');
        $response->assertSee('Hendra Wijaya, S.Psi.');
        $response->assertSee('Gedung Sudirman Central Lt. 12, Jakarta');
    }

    public function test_all_contract_fields_are_required_when_storing(): void
    {
        $response = $this->post(route('contracts.store'), []);

        $response->assertSessionHasErrors([
            'employee_id',
            'contract_number',
            'kode',
            'contract_date',
            'contract_type',
            'position',
            'bidang',
            'branch',
            'start_date',
            'end_date',
            'supervisor_name',
            'supervisor_position',
            'office_address',
        ]);
    }

    public function test_cannot_create_contract_from_non_accepted_offering_letter(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '002/IX/2026/OL',
            'kode' => 'HRD',
            'offer_date' => '2026-09-21',
            'bidang' => 'IT',
            'supervisor_name' => 'Budi',
            'supervisor_position' => 'Manager',
            'office_address' => 'Jakarta',
            'contract_type' => 'PKWT',
            'position' => 'HR Specialist',
            'branch' => 'Surabaya',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 6000000,
            'status' => 'draft',
        ]);

        // Attempting to open contract create with draft offering letter redirects with error
        $response = $this->get(route('contracts.create', ['offering_letter_id' => $ol->id]));
        $response->assertRedirect(route('offering-letters.show', $ol));
        $response->assertSessionHas('error', 'Kontrak kerja hanya dapat diterbitkan untuk Surat Penawaran yang berstatus Diterima (Accepted).');

        // Offering letter contract buttons should NOT be visible when status is draft
        $showResponse = $this->get(route('offering-letters.show', $ol));
        $showResponse->assertDontSee('Terbitkan Kontrak Kerja');

        $indexResponse = $this->get(route('offering-letters.index'));
        $indexResponse->assertDontSee('Buat Kontrak');

        $employeeResponse = $this->get(route('employees.show', $this->employee));
        $employeeResponse->assertDontSee(route('contracts.create', ['offering_letter_id' => $ol->id]));

        // Submitting store with draft offering letter fails validation
        $storeResponse = $this->post(route('contracts.store'), [
            'employee_id' => $this->employee->id,
            'offering_letter_id' => $ol->id,
            'contract_number' => '002/IX/2026/PKWT',
            'kode' => 'HRD',
            'contract_date' => '2026-09-24',
            'contract_type' => 'PKWT',
            'position' => 'HR Specialist',
            'bidang' => 'IT',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'supervisor_name' => 'Budi',
            'supervisor_position' => 'Manager',
            'office_address' => 'Jakarta',
            'basic_salary' => 6000000,
        ]);
        $storeResponse->assertSessionHasErrors('offering_letter_id');

        // When status is updated to accepted, the buttons should be visible
        $ol->update(['status' => 'accepted']);

        $showResponse = $this->get(route('offering-letters.show', $ol));
        $showResponse->assertSee('Terbitkan Kontrak Kerja');

        $indexResponse = $this->get(route('offering-letters.index'));
        $indexResponse->assertSee('Buat Kontrak');

        $employeeResponse = $this->get(route('employees.show', $this->employee));
        $employeeResponse->assertSee(route('contracts.create', ['offering_letter_id' => $ol->id]));
    }

    public function test_can_render_contract_show_page_with_details(): void
    {
        $contract = EmployeeContract::create([
            'employee_id' => $this->employee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/IX/2026/PKWT/HRD',
            'kode' => 'HRD',
            'contract_date' => '2026-09-24',
            'contract_type' => 'PKWT',
            'position' => 'Senior Developer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'supervisor_name' => 'Bambang Pamungkas',
            'supervisor_position' => 'CTO',
            'office_address' => 'Gedung Cyber 2 Lt. 8 Jakarta',
            'basic_salary' => 8000000,
            'status' => 'active',
        ]);

        $response = $this->get(route('contracts.show', $contract));

        $response->assertOk();
        $response->assertSee('001/IX/2026/PKWT/HRD');
        $response->assertSee('HRD');
        $response->assertSee('24 September 2026');
        $response->assertSee('Senior Developer');
        $response->assertSee('Teknologi Informasi');
        $response->assertSee('Surabaya');
        $response->assertSee('Bambang Pamungkas');
        $response->assertSee('CTO');
        $response->assertSee('Gedung Cyber 2 Lt. 8 Jakarta');
    }
}
