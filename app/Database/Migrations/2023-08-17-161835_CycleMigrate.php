<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CycleMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'cycle_id'          =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'code_cycle'        =>[
                'type'              =>'TEXT',
                'null'              => false
            ],
            'name_cycle'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'id_user'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'school_id'         =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'session_id'         =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_cycle'      =>[
                'type'              =>'INT'
            ],
            'etat_cycle'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'created_at'        =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'updated_at'        =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'deleted_at'        =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ]
        ]);
        $this->forge->addPrimaryKey('cycle_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->createTable('cycle');
    }

    public function down()
    {
        $this->forge->dropTable('cycle');
    }
}
