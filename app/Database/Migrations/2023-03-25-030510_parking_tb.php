<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use App\Config\Enum\ParkingLocation;
use App\Config\Enum\ParkingType;
use App\Config\Enum\ServiceCategory;

class ParkingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'grup' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
            ],
            'position' => [
                'type'          => 'INT',
                'constraint'    => '255'
            ],
            'model_code' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'others' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'license_plate' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'category' => [
                'type'          => 'ENUM(' . implode(',', array_map(fn($loc) => '"' . $loc . '"', ServiceCategory::all())) . ')',
                'default'       => 'none',
                'null'          => FALSE,
            ],
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'lokasi' => [
                'type'          => 'ENUM(' . implode(',', array_map(fn($loc) => '"' . $loc . '"', ParkingLocation::all())) . ')',
                'default'       => 'DEPAN',
                'null'          => FALSE,
            ],
            'jenis_parkir' => [
                'type'          => 'ENUM(' . implode(',', array_map(fn($loc) => '"' . $loc . '"', ParkingType::all())) . ')',
                'default'       => 'Parkiran BP',
                'null'          => FALSE,
            ],
            'technician'   => [
                'type'          => 'VARCHAR',
                'constraint'    => '250',
                'null'          => true
            ],
            'created_at' => [
                'type'          => 'DATE',
                'null'          => true
            ],
            'updated_at' => [
                'type'          => 'DATE',
                'null'          => true
            ],
            'user' => [
                'type'          => 'VARCHAR',
                'constraint'    => '250',
                'null'          => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_parking');
    }

    public function down()
    {
        $this->forge->dropTable('tb_parking');
    }
}
