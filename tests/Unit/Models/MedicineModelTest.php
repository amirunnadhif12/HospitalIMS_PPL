<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-U25: Test obat dapat dibuat dengan data valid
     */
    public function test_medicine_can_be_created()
    {
        $medicine = medicine::create([
            'price' => 50000,
            'quantity' => 100,
            'code' => 'MED-001',
        ]);

        $this->assertNotNull($medicine->id);
        $this->assertEquals(50000, $medicine->price);
        $this->assertEquals(100, $medicine->quantity);
        $this->assertEquals('MED-001', $medicine->code);
    }

    /**
     * TC-U26: Test fillable attributes obat
     */
    public function test_medicine_has_correct_fillable()
    {
        $medicine = new medicine();
        $expected = ['price', 'quantity', 'code'];

        $this->assertEquals($expected, $medicine->getFillable());
    }

    /**
     * TC-U27: Test obat mendukung soft delete
     */
    public function test_medicine_supports_soft_delete()
    {
        $medicine = medicine::create([
            'price' => 25000,
            'quantity' => 50,
            'code' => 'MED-002',
        ]);

        $medicineId = $medicine->id;
        $medicine->delete();

        $this->assertNull(medicine::find($medicineId));
        $this->assertNotNull(medicine::withTrashed()->find($medicineId));
    }

    /**
     * TC-U28: Test obat bisa memiliki harga 0
     */
    public function test_medicine_can_have_zero_price()
    {
        $medicine = medicine::create([
            'price' => 0,
            'quantity' => 10,
            'code' => 'MED-FREE',
        ]);

        $this->assertEquals(0, $medicine->price);
    }

    /**
     * TC-U29: Test obat bisa memiliki quantity besar
     */
    public function test_medicine_large_quantity()
    {
        $medicine = medicine::create([
            'price' => 10000,
            'quantity' => 999999,
            'code' => 'MED-BULK',
        ]);

        $this->assertEquals(999999, $medicine->quantity);
    }
}
