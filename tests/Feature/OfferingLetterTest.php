<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\OfferingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferingLetterTest extends TestCase
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
            'name' => 'Aditya Pratama',
            'current_position' => 'UI/UX Designer',
            'current_branch' => 'Jakarta',
        ]);
    }

    public function test_can_render_offering_letters_index(): void
    {
        $response = $this->get(route('offering-letters.index'));

        $response->assertOk();
        $response->assertSee('Surat Penawaran Kerja (Offering Letter)');
        $response->assertSee('Filter Status:');
    }

    public function test_can_render_create_offering_letter_page(): void
    {
        $response = $this->get(route('offering-letters.create', $this->employee));

        $response->assertOk();
        $response->assertSee('Terbitkan Surat Penawaran Kerja Resmi');
        $response->assertSee('Aditya Pratama');
        $response->assertSee('/OL');
    }

    public function test_can_store_offering_letter_with_valid_data(): void
    {
        $payload = [
            'letter_number' => '001/IX/2026/OL',
            'offer_date' => '2026-09-21',
            'contract_type' => 'PKWT',
            'position' => 'UI/UX Designer',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 8500000,
            'allowance' => 1500000,
            'valid_until' => '2026-09-28',
            'terms' => 'Ketentuan jam kerja 40 jam per minggu.',
        ];

        $response = $this->post(route('offering-letters.store', $this->employee), $payload);

        $this->assertDatabaseHas('offering_letters', [
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'contract_type' => 'PKWT',
            'basic_salary' => 8500000,
            'status' => 'draft',
        ]);

        $ol = OfferingLetter::where('letter_number', '001/IX/2026/OL')->first();
        $response->assertRedirect(route('offering-letters.show', $ol));
    }

    public function test_can_update_offering_letter_status(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'offer_date' => '2026-09-21',
            'contract_type' => 'PKWT',
            'position' => 'UI/UX Designer',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 8500000,
            'status' => 'draft',
        ]);

        $response = $this->patch(route('offering-letters.status', $ol), [
            'status' => 'accepted',
        ]);

        $response->assertRedirect();
        $ol->refresh();
        $this->assertEquals('accepted', $ol->status);
    }

    public function test_can_render_print_offering_letter_page(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'offer_date' => '2026-09-21',
            'contract_type' => 'PKWT',
            'position' => 'UI/UX Designer',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 8500000,
            'status' => 'accepted',
        ]);

        $response = $this->get(route('offering-letters.print', $ol));

        $response->assertOk();
        $response->assertSee('SURAT PENAWARAN KERJA (OFFERING LETTER)');
        $response->assertSee('Aditya Pratama');
        $response->assertSee('001/IX/2026/OL');
    }
}
