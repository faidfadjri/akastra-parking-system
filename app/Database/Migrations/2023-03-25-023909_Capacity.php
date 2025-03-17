<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Capacity extends Migration
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
            'lokasi' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'capacity' => [
                'type'           => 'INT',
                'constraint'     => 50,
            ],
            'category' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_capacity');
    }

    public function down()
    {
        $this->forge->dropTable('tb_capacity');
    }
}
