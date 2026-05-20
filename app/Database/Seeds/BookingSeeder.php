<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // Ambil data pertama dari masing-masing tabel
        $user = $this->db->table('users')->get()->getRow();
        $service = $this->db->table('services')->get()->getRow();
        $schedule = $this->db->table('schedules')->get()->getRow();

        // Insert booking
        $this->db->table('bookings')->insert([
            'user_id'          => $user->id,
            'service_id'       => $service->id,
            'schedule_id'      => $schedule->id,
            'booking_date'     => $schedule->date,
            'booking_time'     => $schedule->start_time,
            'service_method'   => 'studio',
            'customer_address' => 'Jl. Mawar No. 10',
            'total_price'      => $service->price,
            'booking_status'   => 'pending',
            'payment_status'   => 'unpaid',
            'notes'            => 'Booking makeup wedding'
        ]);
    }
}