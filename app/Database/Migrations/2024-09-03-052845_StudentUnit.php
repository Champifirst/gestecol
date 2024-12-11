<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentUnit extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'studentunit_id'     =>[
                'type'                  =>  'INT',
                'auto_increment'        =>  true,
                'null'                  =>  false
            ],  
            'student_id'         =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ], 
            'teachingunit_id'    =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],   
            'year_id'            =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'user_id'            =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'class_id'           =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],
            'cycle_id'           =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'session_id'         =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'school_id'          =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'status_studentunit' =>[
                'type'                  =>  'INT'
            ],  
            'etat_studentunit'   =>[   
                'type'                  =>  'TEXT',
                'null'                  =>  false
            ],  
            'created_at'         =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ],  
            'updated_at'         =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ],  
            'deleted_at'         =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ]
        ]);
        $this->forge->addPrimaryKey('studentunit_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('teachingunit_id','teachingunit','teachingunit_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('user_id','user','user_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->createTable('student_unit');
    }


    public function down()
    {
        $this->forge->dropTable('student_unit');
    }
}
