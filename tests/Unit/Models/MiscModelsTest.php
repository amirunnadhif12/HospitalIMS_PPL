<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\bill;
use App\Models\patient;
use App\Models\payment;
use App\Models\block;
use App\Models\employee;
use App\Models\beds;
use App\Models\rooms;
use App\Models\department;
use App\Models\User;
use App\Models\Role;
use App\Models\subscriber;
use App\Models\contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiscModelsTest extends TestCase
{
    use RefreshDatabase;

    // ===================== BILL MODEL =====================

    /**
     * TC-U30: Test bill dapat dibuat
     */
    public function test_bill_can_be_created()
    {
        $patient = patient::create([
            'name' => 'Test Patient',
            'email' => 'bill.test@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'gender' => 'Male',
            'age' => 30,
        ]);

        $bill = bill::create([
            'patients_id' => $patient->id,
            'status' => 'unpaid',
        ]);

        $this->assertNotNull($bill->id);
    }

    /**
     * TC-U31: Test relasi bill belongsTo patient
     */
    public function test_bill_belongs_to_patient()
    {
        $patient = patient::create([
            'name' => 'Bill Patient',
            'email' => 'bill.patient@example.com',
            'phone' => '081234567891',
            'address' => 'Jl. Bill No. 1',
            'gender' => 'Female',
            'age' => 28,
        ]);

        $bill = bill::create([
            'patients_id' => $patient->id,
            'status' => 'paid',
        ]);

        // Relasi terdefinisi
        $this->assertNotNull($bill->patient());
    }

    // ===================== EMPLOYEE MODEL =====================

    /**
     * TC-U32: Test employee dapat dibuat dengan data lengkap
     */
    public function test_employee_can_be_created()
    {
        $employee = employee::create([
            'name' => 'Nurse Rina',
            'email' => 'rina@hospital.com',
            'phone' => '081234567892',
            'salary' => 8000000,
            'address' => 'Jl. Perawat No. 5',
            'qualification' => 'S1 Keperawatan',
            'position' => 'Nurse',
            'status' => 'active',
        ]);

        $this->assertNotNull($employee->id);
        $this->assertEquals('Nurse Rina', $employee->name);
        $this->assertEquals(8000000, $employee->salary);
    }

    /**
     * TC-U33: Test employee mendukung soft delete
     */
    public function test_employee_supports_soft_delete()
    {
        $employee = employee::create([
            'name' => 'Staff Budi',
            'email' => 'budi.staff@hospital.com',
            'phone' => '081234567893',
            'salary' => 5000000,
            'address' => 'Jl. Staff No. 3',
            'qualification' => 'D3',
            'position' => 'Staff',
            'status' => 'active',
        ]);

        $id = $employee->id;
        $employee->delete();

        $this->assertNull(employee::find($id));
        $this->assertNotNull(employee::withTrashed()->find($id));
    }

    // ===================== BED MODEL =====================

    /**
     * TC-U34: Test bed dapat dibuat dan terhubung ke room dan patient
     */
    public function test_bed_can_be_created_with_relations()
    {
        $block = block::create(['blockname' => 'Block X', 'blockcode' => 'BLK-X']);
        $department = department::create([
            'name' => 'ICU',
            'description' => 'Intensive Care',
            'block_id' => $block->id,
        ]);
        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'private',
            'status' => 'available',
        ]);
        $patient = patient::create([
            'name' => 'Bed Patient',
            'email' => 'bed.patient@example.com',
            'phone' => '081234567894',
            'address' => 'Jl. Bed No. 1',
            'gender' => 'Male',
            'age' => 55,
        ]);

        $bed = beds::create([
            'room_id' => $room->id,
            'patient_id' => $patient->id,
            'status' => 'alloted',
            'alloted_time' => '2026-06-01 08:00:00',
        ]);

        $this->assertNotNull($bed->id);
        $this->assertEquals('alloted', $bed->status);
        $this->assertInstanceOf(rooms::class, $bed->room);
        $this->assertInstanceOf(patient::class, $bed->patient);
    }

    // ===================== USER & ROLE MODEL =====================

    /**
     * TC-U35: Test user memiliki relasi belongsTo role
     */
    public function test_user_belongs_to_role()
    {
        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => bcrypt('password'),
            'role' => $role->id,
        ]);

        $this->assertNotNull($user->role());
    }

    /**
     * TC-U36: Test role hasMany users
     */
    public function test_role_has_many_users()
    {
        $role = Role::create(['name' => 'staff']);

        User::create([
            'name' => 'Staff A',
            'email' => 'staffa@hospital.com',
            'password' => bcrypt('password'),
            'role' => $role->id,
        ]);

        User::create([
            'name' => 'Staff B',
            'email' => 'staffb@hospital.com',
            'password' => bcrypt('password'),
            'role' => $role->id,
        ]);

        $this->assertCount(2, $role->users);
    }

    // ===================== SUBSCRIBER MODEL =====================

    /**
     * TC-U37: Test subscriber dapat dibuat
     */
    public function test_subscriber_can_be_created()
    {
        $subscriber = subscriber::create([
            'email' => 'subscriber@test.com',
        ]);

        $this->assertNotNull($subscriber->id);
        $this->assertEquals('subscriber@test.com', $subscriber->email);
    }

    // ===================== CONTACT MODEL =====================

    /**
     * TC-U38: Test contact dapat dibuat
     */
    public function test_contact_can_be_created()
    {
        $contact = contact::create([
            'name' => 'Pengunjung',
            'email' => 'pengunjung@test.com',
            'phone' => '081234567899',
            'subject' => 'Pertanyaan',
            'message' => 'Saya ingin bertanya tentang jadwal dokter.',
        ]);

        $this->assertNotNull($contact->id);
        $this->assertEquals('Pertanyaan', $contact->subject);
    }
}
