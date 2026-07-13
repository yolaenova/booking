<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'service_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'schedule_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'service_type' => [
                'type'       => 'ENUM',
                'constraint' => ['gallery', 'home_service'],
                'default'    => 'gallery',
            ],
            'customer_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'latitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total_price' => [
                'type'     => 'INT',
                'constraint' => 11,
            ],
            'booking_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'rejected', 'process', 'done', 'cancelled'],
                'default'    => 'pending',
            ],
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['unpaid', 'paid', 'failed', 'expired'],
                'default'    => 'unpaid',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}