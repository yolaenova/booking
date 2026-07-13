<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'gross_amount' => [
                'type'     => 'INT',
                'constraint' => 11,
            ],
            'payment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'snap_token' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'transaction_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'pending',
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
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}