<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_user_management(): void
    {
        $user = User::factory()->create();

        $this->get(route('users.index'))->assertRedirect(route('login'));
        $this->get(route('users.create'))->assertRedirect(route('login'));
        $this->post(route('users.store'), [])->assertRedirect(route('login'));
        $this->get(route('users.edit', $user))->assertRedirect(route('login'));
        $this->put(route('users.update', $user), [])->assertRedirect(route('login'));
        $this->delete(route('users.destroy', $user))->assertRedirect(route('login'));
    }

    public function test_staff_cannot_access_user_management_and_receives_forbidden(): void
    {
        $staff = User::factory()->staff()->create();
        $targetUser = User::factory()->create();

        $this->actingAs($staff)->get(route('users.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('users.create'))->assertForbidden();
        $this->actingAs($staff)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertForbidden();
        $this->actingAs($staff)->get(route('users.edit', $targetUser))->assertForbidden();
        $this->actingAs($staff)->put(route('users.update', $targetUser), [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'role' => 'staff',
        ])->assertForbidden();
        $this->actingAs($staff)->delete(route('users.destroy', $targetUser))->assertForbidden();
    }

    public function test_admin_can_view_users_index(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Main Admin']);
        $staff = User::factory()->staff()->create(['name' => 'Staff Member']);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Main Admin');
        $response->assertSee('Staff Member');
        $response->assertSee('Super Admin');
        $response->assertSee('Staff HRD');
    }

    public function test_admin_can_render_create_user_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.create'));

        $response->assertOk();
        $response->assertSee('Form Pendaftaran Pengguna');
        $response->assertSee('Peran Akses (Role)');
    }

    public function test_admin_can_create_new_staff_user(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'name' => 'Citra Lestari',
            'email' => 'citra@example.com',
            'role' => 'staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $payload);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Citra Lestari',
            'email' => 'citra@example.com',
            'role' => 'staff',
        ]);

        $createdUser = User::where('email', 'citra@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check('password123', $createdUser->password));
    }

    public function test_admin_can_create_new_admin_user(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'name' => 'Second Admin',
            'email' => 'secondadmin@example.com',
            'role' => 'admin',
            'password' => 'adminpass123',
            'password_confirmation' => 'adminpass123',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $payload);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Second Admin',
            'email' => 'secondadmin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_render_edit_user_page(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->staff()->create(['name' => 'Target Staff']);

        $response = $this->actingAs($admin)->get(route('users.edit', $targetUser));

        $response->assertOk();
        $response->assertSee('Edit Akun Pengguna');
        $response->assertSee('Target Staff');
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->staff()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'password' => Hash::make('original_secret'),
        ]);

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'staff',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $targetUser), $payload);

        $response->assertRedirect(route('users.index'));
        $targetUser->refresh();
        $this->assertEquals('Updated Name', $targetUser->name);
        $this->assertEquals('updated@example.com', $targetUser->email);
        $this->assertTrue(Hash::check('original_secret', $targetUser->password));
    }

    public function test_admin_can_update_user_password(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->staff()->create([
            'password' => Hash::make('old_password'),
        ]);

        $payload = [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'role' => 'staff',
            'password' => 'brand_new_secret',
            'password_confirmation' => 'brand_new_secret',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $targetUser), $payload);

        $response->assertRedirect(route('users.index'));
        $targetUser->refresh();
        $this->assertTrue(Hash::check('brand_new_secret', $targetUser->password));
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->staff()->create(['name' => 'To Be Deleted']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $targetUser));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        // Attempt to delete self
        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_demote_themselves_if_only_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'staff', // Demote to staff
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $admin), $payload);

        $response->assertSessionHas('error');
        $admin->refresh();
        $this->assertEquals('admin', $admin->role);
    }
}
