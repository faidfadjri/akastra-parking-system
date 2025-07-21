<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;

class CapacitySeeder extends Seeder
{
    public function run()
    {
        $jsonPath = ROOTPATH . 'app/Database/Models/capacity.json';

        if (!is_file($jsonPath)) {
            throw new Exception("JSON file not found at path: $jsonPath");
        }

        $json = file_get_contents($jsonPath);
        $rows = json_decode($json, true);

        if (!is_array($rows)) {
            throw new Exception("Invalid JSON structure. Expected an array of rows.");
        }

        foreach ($rows as $row) {
            $this->db->table('tb_capacity')->insert($row);
        }
    }
}
