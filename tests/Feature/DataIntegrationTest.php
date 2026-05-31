<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\patient;
use App\Models\doctor;
use App\Models\employee;
use App\Models\department;
use App\Models\block;
use App\Models\rooms;
use App\Models\beds;
use App\Models\appointment;
use App\Models\bill;
use App\Models\medicine;
use App\Models\payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-I24: Test integrasi alur lengkap: Block -> Department -> Room -> Bed -> Patient
     */
    public function test_full_hospital_hierarchy_integration()
    {
        // 1. Buat Block
        $block = block::create(['blockname' => 'Block Utama', 'blockcode' => 'BU-01']);
        $this->assertNotNull($block->id);

        // 2. Buat Department di Block
        $department = department::create([
            'name' => 'Penyakit Dalam',
            'description' => 'Internal Medicine',
            'block_id' => $block->id,
        ]);
        $this->assertEquals($block->id, $department->block_id);

        // 3. Buat Room di Department
        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'ward',
            'status' => 'available',
        ]);
        $this->assertEquals($department->id, $room->department_id);

        // 4. Buat Patient
        $patient = patient::create([
            'name' => 'Agus Setiawan',
            'email' => 'agus@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Integrasi No. 1',
            'gender' => 'Male',
            'age' => 40,
            'bloodgroup' => 'B+',
        ]);

        // 5. Assign Bed ke Patient
        $bed = beds::create([
            'room_id' => $room->id,
            'patient_id' => $patient->id,
            'status' => 'alloted',
            'alloted_time' => '2026-06-01 10:00:00',
        ]);

        // Verifikasi hierarki lengkap
        $this->assertEquals($block->id, $bed->room->department->block->id);
        $this->assertEquals('Agus Setiawan', $bed->patient->name);
        $this->assertEquals('Block Utama', $bed->room->department->block->blockname);
    }

    /**
     * TC-I25: Test integrasi alur appointment: Patient -> Doctor -> Appointment
     */
    public function test_appointment_workflow_integration()
    {
        // Buat Patient
        $patient = patient::create([
            'name' => 'Lisa Putri',
            'email' => 'lisa@example.com',
            'phone' => '081234567891',
            'address' => 'Jl. Appointment No. 2',
            'gender' => 'Female',
            'age' => 28,
            'bloodgroup' => 'O+',
        ]);

        // Buat Employee -> Doctor
        $employee = employee::create([
            'name' => 'Dr. Hendra',
            'email' => 'hendra@hospital.com',
            'phone' => '081234567892',
            'salary' => 20000000,
            'address' => 'Jl. Dokter No. 3',
            'qualification' => 'Sp.PD',
            'position' => 'Doctor',
            'status' => 'active',
        ]);

        $doctor = doctor::create([
            'employee_id' => $employee->id,
        ]);

        // Buat Appointment
        $appointment = appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'intime' => '2026-06-01 09:00:00',
            'outtime' => '2026-06-01 10:00:00',
        ]);

        // Verifikasi
        $this->assertEquals('Lisa Putri', $appointment->patient->name);
        $this->assertEquals('Dr. Hendra', $appointment->doctor->employ->name);
    }

    /**
     * TC-I26: Test integrasi billing: Patient -> Bill -> Payment
     */
    public function test_billing_workflow_integration()
    {
        $patient = patient::create([
            'name' => 'Dian Farida',
            'email' => 'dian@example.com',
            'phone' => '081234567893',
            'address' => 'Jl. Billing No. 4',
            'gender' => 'Female',
            'age' => 35,
        ]);

        $bill = bill::create([
            'patients_id' => $patient->id,
            'status' => 'unpaid',
        ]);

        $payment = payment::create([
            'patient_id' => $patient->id,
            'bill_id' => $bill->id,
            'amount' => 500000,
            'status' => 'completed',
            'mode' => 'cash',
        ]);

        // Verifikasi relasi payment
        $this->assertEquals($patient->id, $payment->patient_id);
        $this->assertEquals($bill->id, $payment->bill_id);
        $this->assertEquals(500000, $payment->amount);
    }

    /**
     * TC-I27: Test konsistensi data: multiple rooms dalam satu department
     */
    public function test_multiple_rooms_in_department_consistency()
    {
        $block = block::create(['blockname' => 'Block Multi', 'blockcode' => 'BM-01']);
        $department = department::create([
            'name' => 'Bedah',
            'description' => 'Surgery Department',
            'block_id' => $block->id,
        ]);

        // Buat 5 room
        for ($i = 1; $i <= 5; $i++) {
            rooms::create([
                'department_id' => $department->id,
                'type' => $i <= 2 ? 'private' : 'ward',
                'status' => 'available',
            ]);
        }

        // Verifikasi jumlah room
        $department->refresh();
        $this->assertCount(5, $department->rooms);

        // Verifikasi 2 private, 3 ward
        $privateCount = $department->rooms->where('type', 'private')->count();
        $wardCount = $department->rooms->where('type', 'ward')->count();
        $this->assertEquals(2, $privateCount);
        $this->assertEquals(3, $wardCount);
    }

    /**
     * TC-I28: Test konsistensi data setelah soft delete patient
     */
    public function test_data_consistency_after_patient_soft_delete()
    {
        $patient = patient::create([
            'name' => 'Deleted Patient',
            'email' => 'deleted@example.com',
            'phone' => '081234567894',
            'address' => 'Jl. Delete No. 5',
            'gender' => 'Male',
            'age' => 60,
        ]);

        $patientId = $patient->id;

        // Buat Bill terkait
        $bill = bill::create([
            'patients_id' => $patientId,
            'status' => 'unpaid',
        ]);

        // Soft delete patient
        $patient->delete();

        // Bill masih ada di database
        $this->assertNotNull(bill::find($bill->id));

        // Patient masih bisa diakses via withTrashed
        $trashedPatient = patient::withTrashed()->find($patientId);
        $this->assertNotNull($trashedPatient);
        $this->assertEquals('Deleted Patient', $trashedPatient->name);
    }

    /**
     * TC-I29: Test integrasi multi-department dalam satu block
     */
    public function test_multiple_departments_in_block()
    {
        $block = block::create(['blockname' => 'Block Besar', 'blockcode' => 'BB-01']);

        $deptNames = ['Kardiologi', 'Neurologi', 'Orthopedi', 'Urologi'];

        foreach ($deptNames as $name) {
            department::create([
                'name' => $name,
                'description' => "Departemen $name",
                'block_id' => $block->id,
            ]);
        }

        $block->refresh();
        $this->assertCount(4, $block->departments);

        $names = $block->departments->pluck('name')->toArray();
        $this->assertEquals($deptNames, $names);
    }

    /**
     * TC-I30: Test integrasi medicine CRUD operations
     */
    public function test_medicine_crud_integration()
    {
        // Create
        $medicine = medicine::create([
            'price' => 50000,
            'quantity' => 100,
            'code' => 'PARACETAMOL',
        ]);
        $this->assertDatabaseHas('medicines', ['code' => 'PARACETAMOL']);

        // Read
        $found = medicine::where('code', 'PARACETAMOL')->first();
        $this->assertEquals(50000, $found->price);

        // Update
        $found->price = 55000;
        $found->quantity = 90;
        $found->save();

        $updated = medicine::find($found->id);
        $this->assertEquals(55000, $updated->price);
        $this->assertEquals(90, $updated->quantity);

        // Delete (soft)
        $updated->delete();
        $this->assertNull(medicine::find($updated->id));
        $this->assertDatabaseHas('medicines', ['code' => 'PARACETAMOL']);
    }
}
