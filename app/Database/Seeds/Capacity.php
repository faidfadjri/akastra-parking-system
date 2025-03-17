<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Capacity extends Seeder
{
    public function run()
    {
        $datas = [
            [
                'lokasi'  => 'Parkiran GR',
                'capacity' => 18,
                'category' => 'GR'
            ],
            [
                'lokasi'  => 'Parkiran BP',
                'capacity' => 27,
                'category' => 'BP'
            ],
            [
                'lokasi'  => 'Parkiran Bayangan GR',
                'capacity' => 32,
                'category' => 'GR'
            ],
            [
                'lokasi'  => 'Parkiran Bayangan GR',
                'capacity' => 55,
                'category' => 'GR'
            ],
            [
                'lokasi'  => 'Stall GR',
                'capacity' => 16,
                'category' => 'GR'
            ],
            [
                'lokasi'  => 'Stall GR',
                'capacity' => 20,
                'category' => 'GR'
            ],
            [
                'lokasi'  => 'Parkiran AKM',
                'capacity' => 8,
                'category' => 'AKM'
            ],
            [
                'lokasi'  => 'Parkiran Bayangan AKM',
                'capacity' => 2,
                'category' => 'AKM'
            ],
        ];

        foreach($datas as $data){
            $this->db->table('tb_capacity')->insert($data);
        }
    }
}
