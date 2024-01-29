<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClasseMigrate extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'class_id'              =>[
                'type'                  =>'INT',
                'auto_increment'        =>true,
                'null'                  =>false
            ],
            'name'                  =>[
                'type'                  =>'TEXT',
                'null'                  =>false
            ],
            'number'                =>[
                'type'                  =>'TEXT',
                'null'                  =>false
            ],
            'status_class'          =>[
                'type'                  =>'INT'
            ],
            'etat_class'            =>[
                'type'                  =>'TEXT',
                'null'                  =>false
            ],
            'school_id'             =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'session_id'             =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'cycle_id'             =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'id_user'               =>[
                'type'                  =>'INT',
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
        $this->forge->addPrimaryKey('class_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('class');
    }

    public function down()
    {
        $this->forge->dropTable('class');
    }
}
