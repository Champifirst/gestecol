<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeachingUnitMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'teachingunit_id'     =>[
                'type'                  =>  'INT',
                'auto_increment'        =>  true,
                'null'                  =>  false
            ],  
            'name'                =>[   
                'type'                  =>  'TEXT',
                'null'                  =>  false
            ],  
            'code'                =>[
                'type'                  =>  'TEXT',
                'null'                  =>  false
            ],  
            'coefficient'         =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'year_id'             =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'user_id'             =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],  
            'class_id'            =>[   
                'type'                  =>  'INT',
                'null'                  =>  false
            ],
            'cycle_id'             =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'session_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'school_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'class_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_teachingunit' =>[
                'type'                  =>  'INT'
            ],  
            'etat_teachingunit'   =>[   
                'type'                  =>  'TEXT',
                'null'                  =>  false
            ],  
            'created_at'          =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ],  
            'updated_at'          =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ],  
            'deleted_at'          =>[   
                'type'                  =>  'TIMESTAMP',
                'null'                  =>  true
            ]
        ]);
        $this->forge->addPrimaryKey('teachingunit_id');
        $this->forge->addForeignKey('user_id','user','user_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->createTable('teachingunit');
    }

    public function down()
    {
        $this->forge->dropTable('teachingunit');
    }
}

