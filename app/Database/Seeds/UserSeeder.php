<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
                'phone'    => '08123456789'
            ],
            [
                'name'     => 'Staff Booking',
                'email'    => 'staff@gmail.com',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'role'     => 'staff',
                'phone'    => '08123456788'
            ],
        ];

        // Menggunakan insertBatch untuk memasukkan banyak data sekaligus
        $this->db->table('users')->insertBatch($data);
    }
}