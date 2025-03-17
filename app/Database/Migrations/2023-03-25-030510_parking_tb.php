<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

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
                'type'       => 'VARCHAR',
                'constraint' => '255',
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
                'type'          => 'ENUM("GR", "BP","AKM","none")',
                'default'       => 'none',
                'null'          => FALSE,
            ],
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'lokasi' => [
                'type'          => 'ENUM("DEPAN", "STALL_BP","STALL_GR","AKM")',
                'default'       => 'DEPAN',
                'null'          => FALSE,
            ],
            'jenis_parkir' => [
                'type'          => 'ENUM("Parkiran BP","Parkiran GR","Parkiran Bayangan GR","Parkiran Bayangan BP","Stall GR","Stall BP","Parkiran AKM","Parkiran Bayangan AKM")',
                'default'       => 'Parkiran BP',
                'null'          => FALSE,
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

    public function down(){
        $this->forge->dropTable('tb_parking');
    }
}
