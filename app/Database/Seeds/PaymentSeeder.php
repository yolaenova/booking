<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'booking_id'         => 1,
            'order_id'           => 'TRX001', // KODE DIUBAH: disamakan dengan migration kamu
            'gross_amount'       => 1500000,
            'payment_type'       => 'bank_transfer',
            'snap_token'         => 'sample-token-12345', // Ditambahkan karena ada di migration kamu
            'transaction_status' => 'pending'
        ];

        $this->db->table('payments')->insert($data);
    }
}