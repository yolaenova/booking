<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('reviews')->insert([
            'booking_id' => 1,
            'rating'     => 5,
            'comment'    => 'Pelayanan sangat bagus dan hasil makeup memuaskan.'
        ]);
    }
}