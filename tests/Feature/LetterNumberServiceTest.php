<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\OfferingLetter;
use App\Services\LetterNumberService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LetterNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_correct_format_with_roman_month_and_type_code(): void
    {
        $date = Carbon::create(2026, 9, 21);

        $olNumber = LetterNumberService::generateOfferingLetterNumber($date);
        $pkwtNumber = LetterNumberService::generateContractNumber('PKWT', $date);
        $mtNumber = LetterNumberService::generateContractNumber('MT', $date);
        $magangNumber = LetterNumberService::generateContractNumber('MAGANG', $date);
        $addendumNumber = LetterNumberService::generateAddendumNumber('PKWT', $date);

        $this->assertEquals('001/IX/2026/OL', $olNumber);
        $this->assertEquals('001/IX/2026/PKWT', $pkwtNumber);
        $this->assertEquals('001/IX/2026/MT', $mtNumber);
        $this->assertEquals('001/IX/2026/MAGANG', $magangNumber);
        $this->assertEquals('001/IX/2026/A-PKWT', $addendumNumber);
    }

    public function test_increments_sequence_separately_for_each_document_type(): void
    {
        $employee = Employee::create([
            'name' => 'John Doe',
            'ktp_number' => '3201123456780001',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1995-01-01',
            'address' => 'Jl. Sudirman No. 1',
            'first_join_date' => '2026-09-21',
            'current_position' => 'Staff',
            'current_branch' => 'Jakarta',
        ]);
        $date = Carbon::create(2026, 9, 21);

        // Create 1 offering letter in 2026
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

        // Next OL should be 002
        $nextOl = LetterNumberService::generateOfferingLetterNumber($date);
        $this->assertEquals('002/IX/2026/OL', $nextOl);

        // Contract counter should still be 001 (separate sequence)
        $nextContract = LetterNumberService::generateContractNumber('PKWT', $date);
        $this->assertEquals('001/IX/2026/PKWT', $nextContract);

        // Addendum counter should still be 001 (separate sequence)
        $nextAddendum = LetterNumberService::generateAddendumNumber('PKWT', $date);
        $this->assertEquals('001/IX/2026/A-PKWT', $nextAddendum);
    }

    public function test_sequence_resets_to_one_when_year_changes(): void
    {
        $employee = Employee::factory()->active()->create();
        $year2026 = Carbon::create(2026, 12, 31);
        $year2027 = Carbon::create(2027, 1, 1);

        // Create document in 2026
        OfferingLetter::create([
            'employee_id' => $employee->id,
            'letter_number' => '001/XII/2026/OL',
            'offer_date' => $year2026,
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'proposed_start_date' => $year2026,
            'proposed_end_date' => $year2026->copy()->addYear(),
            'basic_salary' => 5000000,
            'status' => 'draft',
        ]);

        // Create contract in 2026
        EmployeeContract::create([
            'employee_id' => $employee->id,
            'contract_sequence' => 1,
            'contract_number' => '001/XII/2026/PKWT',
            'contract_type' => 'PKWT',
            'position' => 'Staff',
            'branch' => 'Jakarta',
            'start_date' => $year2026,
            'end_date' => $year2026->copy()->addYear(),
            'status' => 'active',
        ]);

        // In 2027, sequence should reset back to 001!
        $ol2027 = LetterNumberService::generateOfferingLetterNumber($year2027);
        $this->assertEquals('001/I/2027/OL', $ol2027);

        $contract2027 = LetterNumberService::generateContractNumber('PKWT', $year2027);
        $this->assertEquals('001/I/2027/PKWT', $contract2027);
    }
}
