<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\appointment;
use App\Models\patient;
use App\Models\doctor;
use App\Models\employee;
use App\Models\patientCheckup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentModelTest extends TestCase
{
    use RefreshDatabase;

    private function createPatientAndDoctor()
    {
        $patient = patient::create([
            'name' => 'Pasien Test',
            'email' => 'pasien@test.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'gender' => 'Male',
            'age' => 30,
            'bloodgroup' => 'A+',
        ]);

        $employee = employee::create([
            'name' => 'Dr. Test',
            'email' => 'drtest@hospital.com',
            'phone' => '081234567891',
            'salary' => 15000000,
            'address' => 'Jl. Dokter No. 1',
            'qualification' => 'Sp.PD',
            'position' => 'Doctor',
            'status' => 'active',
        ]);

        $doctor = doctor::create([
            'employee_id' => $employee->id,
        ]);

        return [$patient, $doctor];
    }

    /**
     * TC-U11: Test appointment dapat dibuat dengan data valid
     */
    public function test_appointment_can_be_created()
    {
        [$patient, $doctor] = $this->createPatientAndDoctor();

        $appointment = appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'intime' => '2026-06-01 09:00:00',
            'outtime' => '2026-06-01 10:00:00',
        ]);

        $this->assertNotNull($appointment->id);
        $this->assertEquals($patient->id, $appointment->patient_id);
        $this->assertEquals($doctor->id, $appointment->doctor_id);
    }

    /**
     * TC-U12: Test relasi appointment belongsTo patient
     */
    public function test_appointment_belongs_to_patient()
    {
        [$patient, $doctor] = $this->createPatientAndDoctor();

        $appointment = appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'intime' => '2026-06-01 09:00:00',
            'outtime' => '2026-06-01 10:00:00',
        ]);

        $this->assertInstanceOf(patient::class, $appointment->patient);
        $this->assertEquals('Pasien Test', $appointment->patient->name);
    }

    /**
     * TC-U13: Test relasi appointment belongsTo doctor
     */
    public function test_appointment_belongs_to_doctor()
    {
        [$patient, $doctor] = $this->createPatientAndDoctor();

        $appointment = appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'intime' => '2026-06-01 09:00:00',
            'outtime' => '2026-06-01 10:00:00',
        ]);

        $this->assertInstanceOf(doctor::class, $appointment->doctor);
    }

    /**
     * TC-U14: Test relasi appointment hasMany checkups
     */
    public function test_appointment_has_many_checkups()
    {
        [$patient, $doctor] = $this->createPatientAndDoctor();

        $appointment = appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'intime' => '2026-06-01 09:00:00',
            'outtime' => '2026-06-01 10:00:00',
        ]);

        // Verifikasi relasi checkups terdefinisi dan mengembalikan collection
        $this->assertCount(0, $appointment->checkups);
    }

    /**
     * TC-U15: Test fillable attributes appointment
     */
    public function test_appointment_has_correct_fillable()
    {
        $appointment = new appointment();
        $expected = ['patient_id', 'doctor_id', 'intime', 'outtime'];

        $this->assertEquals($expected, $appointment->getFillable());
    }
}
