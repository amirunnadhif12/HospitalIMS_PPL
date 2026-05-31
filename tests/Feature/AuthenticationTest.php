<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-I08: Test user bisa register
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302); // Redirect setelah register
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /**
     * TC-I09: Test user bisa login dengan kredensial valid
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::create([
            'name' => 'Login User',
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    /**
     * TC-I10: Test user tidak bisa login dengan password salah
     */
    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::create([
            'name' => 'Wrong Pass User',
            'email' => 'wrongpass@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'wrongpass@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    /**
     * TC-I11: Test user tidak bisa login dengan email yang belum terdaftar
     */
    public function test_user_cannot_login_with_unregistered_email()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
    }

    /**
     * TC-I12: Test user bisa logout
     */
    public function test_user_can_logout()
    {
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $this->assertGuest();
    }

    /**
     * TC-I13: Test registrasi gagal jika email sudah digunakan
     */
    public function test_registration_fails_with_duplicate_email()
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-I14: Test registrasi gagal tanpa password confirmation
     */
    public function test_registration_fails_without_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'No Confirm User',
            'email' => 'noconfirm@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
