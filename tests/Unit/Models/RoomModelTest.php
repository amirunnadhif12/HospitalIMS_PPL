<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\rooms;
use App\Models\beds;
use App\Models\department;
use App\Models\block;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-U20: Test ruangan dapat dibuat dengan data valid
     */
    public function test_room_can_be_created()
    {
        $block = block::create(['blockname' => 'Block A', 'blockcode' => 'BLK-A']);
        $department = department::create([
            'name' => 'Umum',
            'description' => 'Departemen Umum',
            'block_id' => $block->id,
        ]);

        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'ward',
            'status' => 'available',
        ]);

        $this->assertNotNull($room->id);
        $this->assertEquals('ward', $room->type);
        $this->assertEquals('available', $room->status);
    }

    /**
     * TC-U21: Test relasi room belongsTo department
     */
    public function test_room_belongs_to_department()
    {
        $block = block::create(['blockname' => 'Block D', 'blockcode' => 'BLK-D']);
        $department = department::create([
            'name' => 'Bedah',
            'description' => 'Departemen Bedah',
            'block_id' => $block->id,
        ]);

        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'private',
            'status' => 'available',
        ]);

        $this->assertInstanceOf(department::class, $room->department);
        $this->assertEquals('Bedah', $room->department->name);
    }

    /**
     * TC-U22: Test relasi room hasMany beds
     */
    public function test_room_has_many_beds()
    {
        $block = block::create(['blockname' => 'Block E', 'blockcode' => 'BLK-E']);
        $department = department::create([
            'name' => 'ICU',
            'description' => 'Intensive Care Unit',
            'block_id' => $block->id,
        ]);

        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'private',
            'status' => 'available',
        ]);

        $this->assertCount(0, $room->beds);
    }

    /**
     * TC-U23: Test room mendukung soft delete
     */
    public function test_room_supports_soft_delete()
    {
        $block = block::create(['blockname' => 'Block F', 'blockcode' => 'BLK-F']);
        $department = department::create([
            'name' => 'Anak',
            'description' => 'Departemen Anak',
            'block_id' => $block->id,
        ]);

        $room = rooms::create([
            'department_id' => $department->id,
            'type' => 'general',
            'status' => 'available',
        ]);

        $roomId = $room->id;
        $room->delete();

        $this->assertNull(rooms::find($roomId));
        $this->assertNotNull(rooms::withTrashed()->find($roomId));
    }

    /**
     * TC-U24: Test validasi tipe ruangan
     */
    public function test_room_type_values()
    {
        $block = block::create(['blockname' => 'Block G', 'blockcode' => 'BLK-G']);
        $department = department::create([
            'name' => 'Mata',
            'description' => 'Departemen Mata',
            'block_id' => $block->id,
        ]);

        $validTypes = ['ward', 'private', 'semi-private', 'general'];

        foreach ($validTypes as $type) {
            $room = rooms::create([
                'department_id' => $department->id,
                'type' => $type,
                'status' => 'available',
            ]);
            $this->assertEquals($type, $room->type);
        }
    }
}
