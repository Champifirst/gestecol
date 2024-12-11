<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeacherUnitClassMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'teacherunitclass_id'=>[
                'type'                  =>  'INT',
                'auto_increment'        =>  true,
                'null'                  =>  false
            ],  
            'teacher_id'         =>[   
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
            'status_teacher_unit_class' =>[
                'type'                  =>  'INT'
            ],  
            'etat_teacher_unit_class'   =>[   
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
        $this->forge->addPrimaryKey('teacherunitclass_id');
        $this->forge->addForeignKey('teacher_id','teacher','teacher_id');
        $this->forge->addForeignKey('teachingunit_id','teachingunit','teachingunit_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('user_id','user','user_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->createTable('teacher_unit_class');
    }

    public function down()
    {
        $this->forge->dropTable('teacher_unit_class');
    }
}
