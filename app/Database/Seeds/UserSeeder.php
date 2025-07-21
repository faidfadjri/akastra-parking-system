<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'     => 'admin',
            'password'  => 'admin',
            'role'      => 'editor'
        ];
        $this->db->table('tb_user')->insert($data);
    }
}
