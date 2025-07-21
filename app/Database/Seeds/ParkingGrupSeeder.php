<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ParkingGrupSeeder extends Seeder
{
    public function run()
    {
        $json_path = ROOTPATH . 'app/Database/Models/parking-group.json';
        if (is_file($json_path)) {
            $json = file_get_contents($json_path);

            $json_data = json_decode($json, true);

            foreach ($json_data as $data) {
                $this->db->table('tb_grup_parking')->insert($data);
            }
        }
    }
}
