<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\doctor;
use App\Models\employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-U07: Test dokter dapat dibuat dengan employee_id
     */
    public function test_doctor_can_be_created_with_employee_id()
    {
        $employee = employee::create([
            'name' => 'Dr. Ahmad',
            'email' => 'ahmad@hospital.com',
            'phone' => '081234567890',
            'salary' => 15000000,
            'address' => 'Jl. Sudirman No. 10',
            'qualification' => 'Sp.PD',
            'position' => 'Doctor',
            'status' => 'active',
        ]);

        $doctor = doctor::create([
            'employee_id' => $employee->id,
        ]);

        $this->assertNotNull($doctor->id);
        $this->assertEquals($employee->id, $doctor->employee_id);
    }

    /**
     * TC-U08: Test relasi dokter belongsTo employee
     */
    public function test_doctor_belongs_to_employee()
    {
        $employee = employee::create([
            'name' => 'Dr. Siti',
            'email' => 'siti@hospital.com',
            'phone' => '081234567891',
            'salary' => 12000000,
            'address' => 'Jl. Gatot Subroto No. 5',
            'qualification' => 'Sp.A',
            'position' => 'Doctor',
            'status' => 'active',
        ]);

        $doctor = doctor::create([
            'employee_id' => $employee->id,
        ]);

        $this->assertInstanceOf(employee::class, $doctor->employ);
        $this->assertEquals('Dr. Siti', $doctor->employ->name);
    }

    /**
     * TC-U09: Test fillable attributes dokter
     */
    public function test_doctor_has_correct_fillable()
    {
        $doctor = new doctor();
        $this->assertEquals(['employee_id'], $doctor->getFillable());
    }

    /**
     * TC-U10: Test dokter mendukung soft delete
     */
    public function test_doctor_supports_soft_delete()
    {
        $employee = employee::create([
            'name' => 'Dr. Budi',
            'email' => 'budi.doc@hospital.com',
            'phone' => '081234567892',
            'salary' => 14000000,
            'address' => 'Jl. Merdeka No. 8',
            'qualification' => 'Sp.B',
            'position' => 'Doctor',
            'status' => 'active',
        ]);

        $doctor = doctor::create([
            'employee_id' => $employee->id,
        ]);

        $doctorId = $doctor->id;
        $doctor->delete();

        $this->assertNull(doctor::find($doctorId));
        $this->assertNotNull(doctor::withTrashed()->find($doctorId));
    }
}
