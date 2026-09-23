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
            'gender' => 'Laki-laki',
            'current_position' => 'UI/UX Designer',
            'current_branch' => 'Jakarta',
        ]);
    }

    public function test_can_render_offering_letters_index(): void
    {
        $response = $this->get(route('offering-letters.index'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('OFFERING LETTER');
        $response->assertSee('Filter Status:');
        $response->assertSee('Buat Offering Letter');
        $response->assertSee(route('offering-letters.create'));
    }

    public function test_can_render_create_offering_letter_page_for_specific_employee(): void
    {
        $response = $this->get(route('employees.offering-letters.create', $this->employee));

        $response->assertOk();
        $response->assertDontSee('Terbitkan Surat Penawaran Kerja Resmi');
        $response->assertDontSee('Pilih karyawan terdaftar dan lengkapi detail penawaran kerja');
        $response->assertSee('Aditya Pratama');
        $response->assertSee('Jenis Kelamin');
        $response->assertSee('Laki-laki');
        $response->assertSee('Tanggal Surat');
        $response->assertSee('Tanggal Awal Kontrak');
        $response->assertSee('Tanggal Akhir Kontrak');
        $response->assertDontSee('Rencana Tipe Kontrak');
        $response->assertDontSee('Berlaku Hingga / Batas Respon');
        $response->assertDontSee('Gaji Pokok / Uang Saku');
        $response->assertDontSee('Syarat & Ketentuan Khusus');
    }

    public function test_can_render_standalone_create_offering_letter_page(): void
    {
        $response = $this->get(route('offering-letters.create'));

        $response->assertOk();
        $response->assertDontSee('Terbitkan Surat Penawaran Kerja Resmi');
        $response->assertDontSee('Pilih karyawan terdaftar dan lengkapi detail penawaran kerja');
        $response->assertSee('Pilih Karyawan Terdaftar');
        $response->assertSee($this->employee->name);
        $response->assertSee('employee-combobox-wrapper');
        $response->assertSee('selected_employee_card');
        $response->assertSee('Tanggal Surat');
        $response->assertSee('Kode');
        $response->assertSee('Bidang');
        $response->assertDontSee('id="bidang_suggestions"');
        $response->assertSee('Nama Atasan');
        $response->assertSee('Jabatan Atasan');
        $response->assertSee('Alamat Kantor');
        $response->assertSee('Tanggal Awal Kontrak');
        $response->assertSee('Tanggal Akhir Kontrak');
        $response->assertDontSee('Rencana Tipe Kontrak');
        $response->assertDontSee('Berlaku Hingga / Batas Respon');
        $response->assertDontSee('Gaji Pokok / Uang Saku');
        $response->assertDontSee('Syarat & Ketentuan Khusus');
    }

    public function test_can_store_offering_letter_with_employee_id(): void
    {
        $payload = [
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'kode' => 'HRD',
            'offer_date' => '2026-09-21',
            'position' => 'UI/UX Designer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat',
        ];

        $response = $this->post(route('offering-letters.store'), $payload);

        $this->assertDatabaseHas('offering_letters', [
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL',
            'kode' => 'HRD',
            'bidang' => 'Teknologi Informasi',
            'contract_type' => 'PKWT',
            'basic_salary' => 0,
            'status' => 'draft',
        ]);

        $ol = OfferingLetter::where('letter_number', '001/IX/2026/OL')->first();
        $response->assertRedirect(route('offering-letters.show', $ol));
    }

    public function test_employee_id_is_required_when_storing_offering_letter(): void
    {
        $payload = [
            'letter_number' => '002/IX/2026/OL',
            'kode' => 'HRD',
            'offer_date' => '2026-09-21',
            'position' => 'UI/UX Designer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat',
        ];

        $response = $this->post(route('offering-letters.store'), $payload);

        $response->assertSessionHasErrors('employee_id');
    }

    public function test_all_columns_in_offering_letter_are_required_when_storing(): void
    {
        $response = $this->post(route('offering-letters.store'), []);

        $response->assertSessionHasErrors([
            'employee_id',
            'letter_number',
            'kode',
            'offer_date',
            'position',
            'bidang',
            'branch',
            'proposed_start_date',
            'proposed_end_date',
            'supervisor_name',
            'supervisor_position',
            'office_address',
        ]);
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

    public function test_can_store_offering_letter_with_dynamic_kode_and_bidang(): void
    {
        $payload = [
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL/HRD',
            'kode' => 'HRD',
            'bidang' => 'Sumber Daya Manusia',
            'offer_date' => '2026-09-21',
            'position' => 'HR Specialist',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'supervisor_name' => 'Budi Santoso, M.M.',
            'supervisor_position' => 'General Manager HR',
            'office_address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
        ];

        $response = $this->post(route('offering-letters.store'), $payload);

        $this->assertDatabaseHas('offering_letters', [
            'employee_id' => $this->employee->id,
            'letter_number' => '001/IX/2026/OL/HRD',
            'kode' => 'HRD',
            'bidang' => 'Sumber Daya Manusia',
            'supervisor_name' => 'Budi Santoso, M.M.',
            'supervisor_position' => 'General Manager HR',
            'office_address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'status' => 'draft',
        ]);

        $ol = OfferingLetter::where('letter_number', '001/IX/2026/OL/HRD')->first();
        $response->assertRedirect(route('offering-letters.show', $ol));

        $showResponse = $this->get(route('offering-letters.show', $ol));
        $showResponse->assertSee('Sumber Daya Manusia');
        $showResponse->assertSee('HRD');
        $showResponse->assertSee('Budi Santoso, M.M.');
        $showResponse->assertSee('General Manager HR');
        $showResponse->assertSee('Jl. Sudirman No. 45, Jakarta Selatan');

        $printResponse = $this->get(route('offering-letters.print', $ol));
        $printResponse->assertSee('Sumber Daya Manusia');
        $printResponse->assertSee('Budi Santoso, M.M.');
        $printResponse->assertSee('General Manager HR');
        $printResponse->assertSee('Jl. Sudirman No. 45, Jakarta Selatan');
    }

    public function test_can_update_offering_letter_with_supervisor_and_office_data(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '005/IX/2026/OL',
            'offer_date' => '2026-09-21',
            'contract_type' => 'PKWT',
            'position' => 'UI/UX Designer',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'status' => 'draft',
        ]);

        $editResponse = $this->get(route('offering-letters.edit', $ol));
        $editResponse->assertOk();
        $editResponse->assertSee('Kode');
        $editResponse->assertSee('Bidang');
        $editResponse->assertDontSee('id="bidang_suggestions"');
        $editResponse->assertSee('Nama Atasan');
        $editResponse->assertSee('Jabatan Atasan');
        $editResponse->assertSee('Alamat Kantor');

        $updateResponse = $this->put(route('offering-letters.update', $ol), [
            'letter_number' => '005/IX/2026/OL/CKU',
            'kode' => 'CKU',
            'bidang' => 'Divisi Operasional',
            'offer_date' => '2026-09-22',
            'contract_type' => 'PKWT',
            'position' => 'Senior UI/UX Designer',
            'branch' => 'Jakarta Barat',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 9000000,
            'allowance' => 1000000,
            'valid_until' => '2026-09-29',
            'status' => 'draft',
            'terms' => 'Standar ketentuan perusahaan.',
            'notes' => 'Catatan revisi penawaran.',
            'supervisor_name' => 'Dr. Ir. Wahyu Hidayat',
            'supervisor_position' => 'Direktur Operasional',
            'office_address' => 'Wisma Asri Lt. 5, Jakarta Barat',
        ]);

        $updateResponse->assertRedirect(route('offering-letters.show', $ol));

        $this->assertDatabaseHas('offering_letters', [
            'id' => $ol->id,
            'letter_number' => '005/IX/2026/OL/CKU',
            'kode' => 'CKU',
            'bidang' => 'Divisi Operasional',
            'basic_salary' => 9000000,
            'allowance' => 1000000,
            'supervisor_name' => 'Dr. Ir. Wahyu Hidayat',
            'supervisor_position' => 'Direktur Operasional',
            'office_address' => 'Wisma Asri Lt. 5, Jakarta Barat',
        ]);
    }

    public function test_all_columns_in_offering_letter_are_required_when_updating(): void
    {
        $ol = OfferingLetter::create([
            'employee_id' => $this->employee->id,
            'letter_number' => '006/IX/2026/OL',
            'kode' => 'HRD',
            'offer_date' => '2026-09-21',
            'contract_type' => 'PKWT',
            'position' => 'UI/UX Designer',
            'bidang' => 'Teknologi Informasi',
            'branch' => 'Jakarta',
            'proposed_start_date' => '2026-10-01',
            'proposed_end_date' => '2027-09-30',
            'basic_salary' => 8500000,
            'allowance' => 500000,
            'valid_until' => '2026-09-28',
            'status' => 'draft',
            'terms' => 'Ketentuan standar',
            'notes' => 'Catatan',
            'supervisor_name' => 'Hendra Wijaya, S.Psi.',
            'supervisor_position' => 'Human Resources Manager',
            'office_address' => 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat',
        ]);

        $response = $this->put(route('offering-letters.update', $ol), []);

        $response->assertSessionHasErrors([
            'letter_number',
            'kode',
            'offer_date',
            'position',
            'bidang',
            'branch',
            'proposed_start_date',
            'proposed_end_date',
            'supervisor_name',
            'supervisor_position',
            'office_address',
        ]);
    }
}
