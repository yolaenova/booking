<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users     = $this->db->table('users')->get()->getResult();
        $services  = $this->db->table('services')->get()->getResult();
        $schedules = $this->db->table('schedules')->get()->getResult();

        if (!empty($users) && !empty($services) && !empty($schedules)) {
            
            $user     = $users[0];
            $service  = $services[0];
            $schedule = $schedules[0];

            $bookings = [
                [
                    'user_id'          => $user->id,
                    'service_id'       => $service->id,
                    'schedule_id'      => $schedule->id, // Mengambil jadwal pertama
                    'total_price'      => $service->price,
                    'booking_status'   => 'pending',     
                    'payment_status'   => 'unpaid',
                    'notes'            => 'Booking makeup wisuda',
                ],
                [
                    'user_id'          => $user->id,
                    'service_id'       => $service->id,
                    'schedule_id'      => $schedules[1]->id ?? $schedule->id, // Mengambil jadwal kedua jika ada
                    'total_price'      => $service->price,
                    'booking_status'   => 'confirmed',   
                    'payment_status'   => 'paid',
                    'notes'            => 'Booking makeup prewedding',
                ]
            ];

            // Bypass foreign key checks agar proses truncate aman
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
            $this->db->table('payments')->truncate(); // Bersihkan payments jika ada sangkutan
            $this->db->table('bookings')->truncate();
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

            $this->db->table('bookings')->insertBatch($bookings);
        }
    }
}