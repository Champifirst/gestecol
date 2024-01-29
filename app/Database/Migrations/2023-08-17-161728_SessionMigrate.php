<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SessionMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'session_id'          =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'code_session'        =>[
                'type'              =>'TEXT',
                'null'              => false
            ],
            'name_session'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'id_user'             =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'school_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_session'      =>[
                'type'              =>'INT'
            ],
            'etat_session'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'created_at'          =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'updated_at'          =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'deleted_at'          =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ]
        ]);
        $this->forge->addPrimaryKey('session_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->createTable('session');
    }

    public function down()
    {
        $this->forge->dropTable('session');
    }
}
