<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Wedding Makeup',
                'description' => 'Makeup wedding premium',
                'price' => 1500000,
                'duration' => 180,
                'photo' => 'wedding.jpg'
            ],
            [
                'name' => 'Graduation Makeup',
                'description' => 'Makeup wisuda',
                'price' => 500000,
                'duration' => 90,
                'photo' => 'graduation.jpg'
            ]
        ];

        $this->db->table('services')->insertBatch($data);
    }
}