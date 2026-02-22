<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_registration_is_pending_approval()
    {
        $response = $this->post(route('admin.register'), [
            'username' => 'testadmin',
            'email' => 'admin@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.test',
            'role' => 'Admin',
            'approved' => false,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_superadmin_can_approve_and_admin_can_login()
    {
        /** @var \App\Models\User $super */
        $super = User::factory()->create([
            'username' => 'super',
            'email' => 'super@example.test',
            'password' => Hash::make('password'),
            'role' => 'Superadmin',
            'status' => true,
            'approved' => true,
        ]);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'username' => 'adm',
            'email' => 'adm@example.test',
            'password' => Hash::make('secret'),
            'role' => 'Admin',
            'status' => true,
            'approved' => false,
        ]);

        // Approve as superadmin
        $this->actingAs($super);
        $response = $this->post(route('users.approve', $admin));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue((bool) $admin->approved);

        // Now attempt login as admin (credentials)
        $loginResponse = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'secret',
        ]);

        $loginResponse->assertRedirect('/dashboard');
    }

    public function test_admin_logout_sets_status_false_but_superadmin_remains_active()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'username' => 'adm2',
            'email' => 'adm2@example.test',
            'password' => Hash::make('secret'),
            'role' => 'Admin',
            'status' => true,
            'approved' => true,
        ]);

        $this->actingAs($admin);
        $this->post(route('logout'));
        $admin->refresh();
        $this->assertFalse((bool) $admin->status);

        /** @var \App\Models\User $super */
        $super = User::factory()->create([
            'username' => 'super2',
            'email' => 'super2@example.test',
            'password' => Hash::make('password'),
            'role' => 'Superadmin',
            'status' => true,
            'approved' => true,
        ]);

        $this->actingAs($super);
        $this->post(route('logout'));
        $super->refresh();
        $this->assertTrue((bool) $super->status);
    }
}
