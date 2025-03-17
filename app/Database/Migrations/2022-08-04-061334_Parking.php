<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Parking extends Migration
{
    public function up()
    {
        # Buat table Model Kendaraan
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'model' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'model_code' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_model_kendaraan');


        # Buat table tb_grup_parking
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_grup_parking');


        # Buat table tb_parking
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
            'license_plate' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'status' => array(
                'type' => "ENUM('','Menunggu Perbaikan','Menunggu Sparepart','Proses Pengerjaan', 'Menunggu Pengambilan', 'Menunggu Driver')",
                'default' => '',
                'null' => true,
            ),
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_parking');
    }

    public function down()
    {
        $this->forge->dropTable('tb_grup_parking');
        $this->forge->dropTable('tb_model_kendaraan');
    }
}
