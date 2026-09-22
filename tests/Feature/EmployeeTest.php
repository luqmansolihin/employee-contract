<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_employees_index_page(): void
    {
        Employee::factory()->count(3)->active()->create();

        $response = $this->get(route('employees.index'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('EMPLOYEE');
        $response->assertSee('Tambah Karyawan');
    }

    public function test_employees_index_url_renders_employees(): void
    {
        $response = $this->get('/employees');

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('EMPLOYEE');
    }

    public function test_contract_employees_url_redirects_to_employees(): void
    {
        $response = $this->get('/contract-employees');

        $response->assertRedirect('/employees');
    }

    public function test_can_filter_employees_by_status(): void
    {
        $active = Employee::factory()->active()->create(['name' => 'Active Worker']);
        $expiring = Employee::factory()->expiringSoon()->create(['name' => 'Expiring Worker']);
        $expired = Employee::factory()->expired()->create(['name' => 'Expired Worker']);

        $responseActive = $this->get(route('employees.index', ['status' => 'active']));
        $responseActive->assertOk();
        $responseActive->assertSee('Active Worker');
        $responseActive->assertDontSee('Expired Worker');

        $responseExpired = $this->get(route('employees.index', ['status' => 'expired']));
        $responseExpired->assertOk();
        $responseExpired->assertSee('Expired Worker');
        $responseExpired->assertDontSee('Active Worker');
    }

    public function test_can_search_employees(): void
    {
        Employee::factory()->create([
            'name' => 'Luqman Solihin',
            'current_position' => 'Senior Backend Developer',
            'current_branch' => 'Bandung',
            'ktp_number' => '3273010101900001',
        ]);

        Employee::factory()->create([
            'name' => 'Siti Nurhaliza',
            'current_position' => 'Staff Finance',
            'current_branch' => 'Jakarta',
            'ktp_number' => '3171010101900002',
        ]);

        $response = $this->get(route('employees.index', ['search' => 'Luqman']));
        $response->assertOk();
        $response->assertSee('Luqman Solihin');
        $response->assertDontSee('Siti Nurhaliza');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get(route('employees.create'));

        $response->assertOk();
        $response->assertSee('SI-KONTRAK /');
        $response->assertSee('TAMBAH EMPLOYEE');
        $response->assertDontSee('Pendaftaran Karyawan Kontrak Baru');
        $response->assertDontSee('Isi seluruh informasi pribadi dan detail kontrak kerja awal karyawan');
        $response->assertSee('Nomor KTP / NIK');
    }

    public function test_can_store_new_employee_with_personal_data_only(): void
    {
        $payload = [
            'name' => 'Ahmad Fauzi',
            'ktp_number' => '3171012304950005',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1995-04-23',
            'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
            'email' => 'ahmad.fauzi@example.com',
            'phone' => '081234567890',
        ];

        $response = $this->post(route('employees.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'name' => 'Ahmad Fauzi',
            'ktp_number' => '3171012304950005',
            'current_position' => null,
            'current_branch' => null,
            'first_join_date' => null,
            'current_contract_end_date' => null,
        ]);

        // Verify no contract was created automatically
        $employee = Employee::where('ktp_number', '3171012304950005')->first();
        $this->assertNotNull($employee);
        $this->assertEquals(0, $employee->contracts()->count());
        $this->assertEquals('uncontracted', $employee->status);
        $this->assertEquals('Belum Ada Kontrak', $employee->status_label);
    }

    public function test_validates_ktp_must_be_16_digits(): void
    {
        $payload = [
            'name' => 'Ahmad Fauzi',
            'ktp_number' => '12345',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1995-04-23',
            'address' => 'Jl. Sudirman No. 45',
        ];

        $response = $this->post(route('employees.store'), $payload);

        $response->assertSessionHasErrors('ktp_number');
        $this->assertDatabaseMissing('employees', [
            'name' => 'Ahmad Fauzi',
        ]);
    }

    public function test_validates_ktp_uniqueness(): void
    {
        Employee::factory()->create([
            'ktp_number' => '3171012304950005',
        ]);

        $payload = [
            'name' => 'Budi Baru',
            'ktp_number' => '3171012304950005',
            'gender' => 'Laki-laki',
            'birth_place' => 'Surabaya',
            'birth_date' => '1996-01-01',
            'address' => 'Jl. Pemuda No. 10',
        ];

        $response = $this->post(route('employees.store'), $payload);

        $response->assertSessionHasErrors('ktp_number');
    }

    public function test_validates_birth_date_must_be_before_today(): void
    {
        $payload = [
            'name' => 'Dewi Sartika',
            'ktp_number' => '3273012304950008',
            'gender' => 'Perempuan',
            'birth_place' => 'Bandung',
            'birth_date' => Carbon::tomorrow()->toDateString(),
            'address' => 'Jl. Dago No. 12',
        ];

        $response = $this->post(route('employees.store'), $payload);

        $response->assertSessionHasErrors('birth_date');
    }

    public function test_can_show_employee_detail_with_contract_timeline(): void
    {
        $employee = Employee::factory()->create([
            'name' => 'Rina Wijaya',
        ]);

        $response = $this->get(route('employees.show', $employee));

        $response->assertOk();
        $response->assertSee('Rina Wijaya');
        $response->assertSee('DETAIL EMPLOYEE');
        $response->assertSee('Biodata Pribadi');
        $response->assertSee('Riwayat Seluruh Kontrak Kerja');
        $response->assertSee($employee->ktp_number);
        $response->assertDontSee('Status Tahapan Alur Kerja Karyawan');
        $response->assertDontSee('Siklus Hidup: Input Data');
        $response->assertDontSee('+ Offering Letter');
        $response->assertDontSee('+ Kontrak Baru');
        $response->assertDontSee('+ Buat Adendum');
    }

    public function test_can_render_edit_page(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.edit', $employee));

        $response->assertOk();
        $response->assertSee('Ubah Biodata Pribadi Karyawan');
        $response->assertSee($employee->name);
    }

    public function test_can_update_employee_biodata(): void
    {
        $employee = Employee::factory()->create([
            'name' => 'Nama Lama',
        ]);

        $payload = [
            'name' => 'Nama Baru Diperbarui',
            'ktp_number' => $employee->ktp_number,
            'gender' => $employee->gender,
            'birth_place' => $employee->birth_place,
            'birth_date' => $employee->birth_date->format('Y-m-d'),
            'address' => 'Alamat Baru No. 99',
        ];

        $response = $this->put(route('employees.update', $employee), $payload);

        $response->assertRedirect(route('employees.show', $employee));
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Nama Baru Diperbarui',
            'address' => 'Alamat Baru No. 99',
        ]);
    }

    public function test_can_delete_employee_and_its_contracts(): void
    {
        $employee = Employee::factory()->create([
            'name' => 'Karyawan Dihapus',
        ]);

        $response = $this->delete(route('employees.destroy', $employee));

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
        $this->assertDatabaseMissing('employee_contracts', [
            'employee_id' => $employee->id,
        ]);
    }
}
