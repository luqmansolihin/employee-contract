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
        $response->assertDontSee('3. Remunerasi & Catatan');
        $response->assertDontSee('Gaji Pokok / Uang Saku');
        $response->assertDontSee('Tunjangan Lainnya');
    }

    public function test_can_create_contract_from_accepted_offering_letter(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'offer_date' => '2026-09-21',
            'contract_type' => 'MT',
            'position' => 'Management Trainee HR',
            'branch' => 'Surabaya',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 6000000,
            'status' => 'accepted',
        ]);

        $createResponse = $this->get(route('contracts.create', ['offering_letter_id' => $ol->id]));
        $createResponse->assertOk();
        $createResponse->assertSee('Merujuk ke Offering Letter #001/IX/2026/OL');
        $createResponse->assertSee('Merujuk ke Offering Letter');
        $createResponse->assertSee('001/IX/2026/OL');
        $createResponse->assertSee('Management Trainee HR');

        $payload = [
            'employee_id' => $this->employee->id,
            'offering_letter_id' => $ol->id,
            'contract_number' => '001/IX/2026/MT',
            'contract_type' => 'MT',
            'position' => 'Management Trainee HR',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'basic_salary' => 6000000,
        ];

        $storeResponse = $this->post(route('contracts.store'), $payload);

        $this->assertDatabaseHas('employee_contracts', [
            'employee_id' => $this->employee->id,
            'offering_letter_id' => $ol->id,
            'contract_number' => '001/IX/2026/MT',
            'contract_type' => 'MT',
            'status' => 'active',
        ]);

        $contract = EmployeeContract::where('contract_number', '001/IX/2026/MT')->first();
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
            'contract_type' => 'PKWT',
            'position' => 'HR Specialist',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'basic_salary' => 7000000,
            'status' => 'active',
        ]);

        $response = $this->get(route('contracts.print', $contract));

        $response->assertOk();
        $response->assertSee('SURAT PERJANJIAN KERJA WAKTU TERTENTU (PKWT)');
        $response->assertSee('Maya Indah');
        $response->assertSee('001/IX/2026/PKWT');
    }

    public function test_cannot_create_contract_from_non_accepted_offering_letter(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '002/IX/2026/OL',
            'offer_date' => '2026-09-21',
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
            'contract_type' => 'PKWT',
            'position' => 'HR Specialist',
            'branch' => 'Surabaya',
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
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
}
