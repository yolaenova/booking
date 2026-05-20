<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('payments')->insert([
            'booking_id'         => 1,
            'transaction_id'     => 'TRX001',
            'payment_type'       => 'bank_transfer',
            'gross_amount'       => 1500000,
            'transaction_status' => 'pending',
            'payment_time'       => date('Y-m-d H:i:s')
        ]);
    }
}