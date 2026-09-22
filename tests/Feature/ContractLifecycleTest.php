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
}
