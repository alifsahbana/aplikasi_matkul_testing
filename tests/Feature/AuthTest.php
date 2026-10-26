<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Portal Masuk Petugas');
    }

    public function test_admin_can_authenticate_and_redirect_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Penguji',
            'email' => 'admin.test@pamekasan-gis.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin.test@pamekasan-gis.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_pengunjung_can_authenticate_and_redirect_to_public_home(): void
    {
        $pengunjung = User::create([
            'name' => 'Pengunjung Penguji',
            'email' => 'pengunjung.test@pamekasan-gis.id',
            'password' => Hash::make('password123'),
            'role' => 'pengunjung',
        ]);

        $response = $this->post('/login', [
            'email' => 'pengunjung.test@pamekasan-gis.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'name' => 'User Penguji',
            'email' => 'user.test@pamekasan-gis.id',
            'password' => Hash::make('password123'),
            'role' => 'pengunjung',
        ]);

        $response = $this->post('/login', [
            'email' => 'user.test@pamekasan-gis.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $pengunjung = User::create([
            'name' => 'Pengunjung Biasa',
            'email' => 'biasa@pamekasan-gis.id',
            'password' => Hash::make('password123'),
            'role' => 'pengunjung',
        ]);

        $response = $this->actingAs($pengunjung)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $admin = User::create([
            'name' => 'Admin Sesi',
            'email' => 'logout.test@pamekasan-gis.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
