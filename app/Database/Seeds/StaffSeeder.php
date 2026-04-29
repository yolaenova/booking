<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insert([
            'name'     => 'Staff Booking',
            'email'    => 'staff@gmail.com',
            'password' => password_hash('staff123', PASSWORD_DEFAULT),
            'role'     => 'staff',
            'phone'    => '08123456788'
        ]);
    }
}