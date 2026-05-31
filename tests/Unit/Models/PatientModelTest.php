<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-U01: Test pasien dapat dibuat dengan data lengkap yang valid
     */
    public function test_patient_can_be_created_with_valid_data()
    {
        $patient = patient::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'gender' => 'Male',
            'age' => 30,
            'bloodgroup' => 'A+',
        ]);

        $this->assertNotNull($patient->id);
        $this->assertEquals('Budi Santoso', $patient->name);
        $this->assertEquals('budi@example.com', $patient->email);
        $this->assertEquals('081234567890', $patient->phone);
        $this->assertEquals('Jl. Merdeka No. 10, Jakarta', $patient->address);
        $this->assertEquals('Male', $patient->gender);
        $this->assertEquals(30, $patient->age);
        $this->assertEquals('A+', $patient->bloodgroup);
    }

    /**
     * TC-U02: Test fillable attributes pada model Patient
     */
    public function test_patient_has_correct_fillable_attributes()
    {
        $patient = new patient();
        $expectedFillable = [
            'name', 'email', 'phone', 'address', 'gender', 'age', 'bloodgroup', 'photo_path',
        ];

        $this->assertEquals($expectedFillable, $patient->getFillable());
    }

    /**
     * TC-U03: Test pasien mendukung soft delete
     */
    public function test_patient_supports_soft_delete()
    {
        $patient = patient::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'phone' => '081234567891',
            'address' => 'Jl. Sudirman No. 5',
            'gender' => 'Female',
            'age' => 25,
            'bloodgroup' => 'B+',
        ]);

        $patientId = $patient->id;
        $patient->delete();

        // Pasien tidak ditemukan di query biasa
        $this->assertNull(patient::find($patientId));
        // Tapi masih ada di database (soft deleted)
        $this->assertNotNull(patient::withTrashed()->find($patientId));
    }

    /**
     * TC-U04: Test pasien bisa dibuat tanpa photo_path (nullable)
     */
    public function test_patient_can_be_created_without_photo()
    {
        $patient = patient::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@example.com',
            'phone' => '081234567892',
            'address' => 'Jl. Gatot Subroto No. 15',
            'gender' => 'Male',
            'age' => 45,
            'bloodgroup' => 'O+',
        ]);

        $this->assertNotNull($patient->id);
        $this->assertNull($patient->photo_path);
    }

    /**
     * TC-U05: Test pasien bisa dibuat dengan photo_path
     */
    public function test_patient_can_be_created_with_photo_path()
    {
        $patient = patient::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'phone' => '081234567893',
            'address' => 'Jl. Ahmad Yani No. 20',
            'gender' => 'Female',
            'age' => 35,
            'bloodgroup' => 'AB+',
            'photo_path' => 'patients/photo123.jpg',
        ]);

        $this->assertEquals('patients/photo123.jpg', $patient->photo_path);
    }

    /**
     * TC-U06: Test pasien bisa di-restore setelah soft delete
     */
    public function test_patient_can_be_restored_after_soft_delete()
    {
        $patient = patient::create([
            'name' => 'Rudi Hermawan',
            'email' => 'rudi@example.com',
            'phone' => '081234567894',
            'address' => 'Jl. Diponegoro No. 25',
            'gender' => 'Male',
            'age' => 50,
            'bloodgroup' => 'A-',
        ]);

        $patientId = $patient->id;
        $patient->delete();

        // Restore pasien
        patient::withTrashed()->find($patientId)->restore();

        $this->assertNotNull(patient::find($patientId));
    }
}
