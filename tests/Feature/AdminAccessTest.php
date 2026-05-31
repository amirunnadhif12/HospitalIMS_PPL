<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-I15: Test guest tidak bisa akses admin dashboard (redirect ke login)
     */
    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I16: Test guest tidak bisa akses halaman patients admin
     */
    public function test_guest_cannot_access_admin_patients()
    {
        $response = $this->get('/admin/patients');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I17: Test guest tidak bisa akses halaman rooms admin
     */
    public function test_guest_cannot_access_admin_rooms()
    {
        $response = $this->get('/admin/rooms');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I18: Test guest tidak bisa akses halaman beds admin
     */
    public function test_guest_cannot_access_admin_beds()
    {
        $response = $this->get('/admin/beds');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I19: Test guest tidak bisa akses halaman departments admin
     */
    public function test_guest_cannot_access_admin_departments()
    {
        $response = $this->get('/admin/departments');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I20: Test guest tidak bisa akses halaman appointment admin
     */
    public function test_guest_cannot_access_admin_appointment()
    {
        $response = $this->get('/admin/appointment');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I21: Test guest tidak bisa akses halaman employees admin
     */
    public function test_guest_cannot_access_admin_employees()
    {
        $response = $this->get('/admin/employees');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I22: Test guest tidak bisa akses halaman medicines admin
     */
    public function test_guest_cannot_access_admin_medicines()
    {
        $response = $this->get('/admin/medicinesStore');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * TC-I23: Test guest tidak bisa akses settings admin
     */
    public function test_guest_cannot_access_admin_settings()
    {
        $response = $this->get('/admin/settings');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
