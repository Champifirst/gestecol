<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentClasseMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_class_id'    =>[
                'type'                  =>'INT',
                'auto_increment'        =>true,
                'null'                  =>false
            ],
            'class_id'             =>[
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
            'status_stu_class'       =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'etat_stu_class'         =>[
                'type'                  =>'TEXT',
                'null'                  =>false
            ],
            'redouble'               =>[
                'type'                  =>'TEXT',
                'null'                  =>false,
                'default'               =>"non",
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
        $this->forge->addPrimaryKey('student_class_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('student_class');
    }

    public function down()
    {
        $this->forge->dropTable('student_class');
    }
}
