<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $user = $this->db->table('users')->get()->getRow();
        $service = $this->db->table('services')->get()->getRow();
        $schedule = $this->db->table('schedules')->get()->getRow();

        if ($user && $service && $schedule) {
            $data = [
                'id'               => 1,
                'user_id'          => $user->id,
                'service_id'       => $service->id,
                'schedule_id'      => $schedule->id,
                // Kolom service_method & customer_address dihapus agar tidak bentrok dengan migration-mu
                'total_price'      => $service->price,
                'booking_status'   => 'pending',
                'payment_status'   => 'unpaid',
                'notes'            => 'Booking makeup wedding'
            ];

            $this->db->table('bookings')->insert($data);
        }
    }
}