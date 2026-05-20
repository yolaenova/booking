<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'staff_id' => 2,
                'date' => '2026-05-21',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'capacity' => 3
            ],
            [
                'staff_id' => 2,
                'date' => '2026-05-22',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'capacity' => 2
            ]
        ];

        $this->db->table('schedules')->insertBatch($data);
    }
}