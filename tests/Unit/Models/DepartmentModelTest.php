<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\department;
use App\Models\block;
use App\Models\rooms;
use App\Models\hod;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-U16: Test departemen dapat dibuat dengan data valid
     */
    public function test_department_can_be_created()
    {
        $block = block::create([
            'blockname' => 'Block A',
            'blockcode' => 'BLK-A',
        ]);

        $department = department::create([
            'name' => 'Kardiologi',
            'description' => 'Departemen Jantung dan Pembuluh Darah',
            'block_id' => $block->id,
        ]);

        $this->assertNotNull($department->id);
        $this->assertEquals('Kardiologi', $department->name);
        $this->assertEquals($block->id, $department->block_id);
    }

    /**
     * TC-U17: Test relasi department hasMany rooms
     */
    public function test_department_has_many_rooms()
    {
        $block = block::create([
            'blockname' => 'Block B',
            'blockcode' => 'BLK-B',
        ]);

        $department = department::create([
            'name' => 'Neurologi',
            'description' => 'Departemen Saraf',
            'block_id' => $block->id,
        ]);

        rooms::create([
            'department_id' => $department->id,
            'type' => 'ward',
            'status' => 'available',
        ]);

        rooms::create([
            'department_id' => $department->id,
            'type' => 'private',
            'status' => 'occupied',
        ]);

        $this->assertCount(2, $department->rooms);
    }

    /**
     * TC-U18: Test relasi department belongsTo block
     */
    public function test_department_belongs_to_block()
    {
        $block = block::create([
            'blockname' => 'Block C',
            'blockcode' => 'BLK-C',
        ]);

        $department = department::create([
            'name' => 'Orthopedi',
            'description' => 'Departemen Tulang',
            'block_id' => $block->id,
        ]);

        $this->assertInstanceOf(block::class, $department->block);
        $this->assertEquals('Block C', $department->block->blockname);
    }

    /**
     * TC-U19: Test fillable attributes department
     */
    public function test_department_has_correct_fillable()
    {
        $department = new department();
        $expected = ['name', 'description', 'photo_path', 'block_id', 'hod_id'];

        $this->assertEquals($expected, $department->getFillable());
    }
}
