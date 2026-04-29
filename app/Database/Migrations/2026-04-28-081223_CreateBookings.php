<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true
            ],

            'user_id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true
            ],

            'service_id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true
            ],

            'schedule_id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true
            ],

            'notes'=>[
                'type'=>'TEXT',
                'null'=>true
            ],

            'total_price'=>[
                'type'=>'INT',
                'constraint'=>11
            ],

            'booking_status'=>[
                'type'=>'ENUM',
                'constraint'=>[
                    'pending',
                    'confirmed',
                    'rejected',
                    'process',
                    'done',
                    'cancelled'
                ],
                'default'=>'pending'
            ],

            'payment_status'=>[
                'type'=>'ENUM',
                'constraint'=>[
                    'unpaid',
                    'paid',
                    'failed',
                    'expired'
                ],
                'default'=>'unpaid'
            ],

            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
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