<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentCycleMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_cycle_id'    =>[
                'type'                  =>'INT',
                'auto_increment'        =>true,
                'null'                  =>false
            ],
            'cycle_id'             =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'student_id'            =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'year_id'               =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'id_user'               =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'status_stu_cycle'       =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'etat_stu_cycle'         =>[
                'type'                  =>'TEXT',
                'null'                  =>false
            ],
            'created_at'            =>[
                'type'                  =>'TIMESTAMP',
                'null'                  =>true
            ],
            'updated_at'            =>[
                'type'                  =>'TIMESTAMP',
                'null'                  =>true
            ],
            'deleted_at'            =>[
                'type'                  =>'TIMESTAMP',
                'null'                  =>true
            ]
        ]);
        $this->forge->addPrimaryKey('student_cycle_id');
        $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('student_cycle');
    }

    public function down()
    {
        $this->forge->dropTable('student_cycle');
    }
}
