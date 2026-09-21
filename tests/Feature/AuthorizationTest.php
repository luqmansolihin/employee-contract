<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_employee(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = Employee::factory()->active()->create();

        $response = $this->actingAs($admin)->delete(route('employees.destroy', $employee));

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('employee_contracts', ['employee_id' => $employee->id]);
    }

    public function test_staff_cannot_delete_employee_and_receives_forbidden(): void
    {
        $staff = User::factory()->staff()->create();
        $employee = Employee::factory()->active()->create();

        $response = $this->actingAs($staff)->delete(route('employees.destroy', $employee));

        $response->assertForbidden();
        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    }

    public function test_delete_action_is_visible_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = Employee::factory()->active()->create();

        $responseIndex = $this->actingAs($admin)->get(route('employees.index'));
        $responseIndex->assertOk();
        $responseIndex->assertSee('action="' . route('employees.destroy', $employee) . '"', false);

        $responseShow = $this->actingAs($admin)->get(route('employees.show', $employee));
        $responseShow->assertOk();
        $responseShow->assertSee('action="' . route('employees.destroy', $employee) . '"', false);
    }

    public function test_delete_action_is_hidden_from_staff(): void
    {
        $staff = User::factory()->staff()->create();
        $employee = Employee::factory()->active()->create();

        $responseIndex = $this->actingAs($staff)->get(route('employees.index'));
        $responseIndex->assertOk();
        $responseIndex->assertDontSee('action="' . route('employees.destroy', $employee) . '"', false);

        $responseShow = $this->actingAs($staff)->get(route('employees.show', $employee));
        $responseShow->assertOk();
        $responseShow->assertDontSee('action="' . route('employees.destroy', $employee) . '"', false);
    }

    public function test_staff_can_view_create_edit_and_renew_contract(): void
    {
        $staff = User::factory()->staff()->create();
        $employee = Employee::factory()->active()->create();

        $this->actingAs($staff)->get(route('employees.index'))->assertOk();
        $this->actingAs($staff)->get(route('employees.create'))->assertOk();
        $this->actingAs($staff)->get(route('employees.show', $employee))->assertOk();
        $this->actingAs($staff)->get(route('employees.edit', $employee))->assertOk();
        $this->actingAs($staff)->get(route('employees.renew', $employee))->assertOk();
    }
}
