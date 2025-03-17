<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
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
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ],
            'role' => [
                'type' => 'ENUM("editor","viewer")',
                'default' => 'editor',
                'null' => FALSE,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_user');
    }

    public function down()
    {
        $this->forge->dropTable('tb_user');
    }
}
