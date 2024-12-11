<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BourseStudent extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'bourse_student_id'     =>[
                'type'                  =>  'INT',
                'auto_increment'        =>  true,
                'null'                  =>  false
            ], 
            'session_id'         =>[
                'type'                  =>'INT',
                'null'                  =>false
            ], 
            'cycle_id'           =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'class_id'           =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],
            'student_id'         =>[   
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
            'bourse_id'            =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'status' =>[
                'type'                  =>  'INT'
            ],  
            'etat'   =>[   
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
        $this->forge->addPrimaryKey('bourse_student_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('user_id','user','user_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->createTable('bourse_student');
    }


    public function down()
    {
        $this->forge->dropTable('bourse_student');
    }
}
