<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedules extends Migration
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
            'staff_id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true
            ],
            'date'=>[
                'type'=>'DATE'
            ],
            'start_time'=>[
                'type'=>'TIME'
            ],
            'end_time'=>[
                'type'=>'TIME'
            ],
            'capacity'=>[
                'type'=>'INT',
                'default'=>1
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('staff_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedules');
    }

    public function down()
    {
        $this->forge->dropTable('schedules');
    }
}