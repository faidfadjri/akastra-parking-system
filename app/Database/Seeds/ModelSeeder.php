<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;

class ModelSeeder extends Seeder
{
    public function run()
    {
        $json_path = ROOTPATH . 'app/Database/models.json';
        if (is_file($json_path)) {
            $json = file_get_contents($json_path);

            // Decode the JSON file
            $json_data = json_decode($json, true);

            foreach ($json_data as $data) {
                $this->db->table('tb_model_kendaraan')->insert($data);
            }
        }
    }
}
