<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-I01: Test halaman utama bisa diakses
     */
    public function test_homepage_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * TC-I02: Test halaman about bisa diakses
     */
    public function test_about_page_is_accessible()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /**
     * TC-I03: Test halaman contact bisa diakses
     */
    public function test_contact_page_is_accessible()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * TC-I04: Test halaman doctors bisa diakses
     */
    public function test_doctors_page_is_accessible()
    {
        $response = $this->get('/docters');
        $response->assertStatus(200);
    }

    /**
     * TC-I05: Test halaman services bisa diakses
     */
    public function test_services_page_is_accessible()
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
    }

    /**
     * TC-I06: Test halaman login bisa diakses
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * TC-I07: Test halaman register bisa diakses
     */
    public function test_register_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }
}
