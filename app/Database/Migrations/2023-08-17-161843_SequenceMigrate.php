<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SequenceMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
        'sequence_id'      =>[
            'type'              =>'INT',
            'auto_increment'    =>true,
            'null'              =>false
        ],
        'name'    =>[
            'type'              =>'TEXT',
            'null'              =>false
        ],
        'coded'    =>[
            'type'              =>'TEXT',
            'null'              =>false
        ],
        'status_sequence'  =>[
            'type'              =>'INT'
        ],
        'etat_sequence'    =>[
            'type'              =>'TEXT',
            'null'              =>false
        ],
        'id_user'           =>[
            'type'              => 'INT',
            'null'              => false
        ],
        'session_id'        =>[
            'type'              => 'INT',
            'null'              => false
        ],
        'school_id'        =>[
            'type'              => 'INT',
            'null'              => false
        ],
        'cycle_id'          =>[
            'type'              => 'INT',
            'null'              => false
        ],
        'class_id'          =>[
            'type'              => 'INT',
            'null'              => false
        ],
        'trimestre_id'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'school_id'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'session_id'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'cycle_id'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'class_id'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'id_user'     =>[
            'type'              =>'INT',
            'null'              =>false
        ],
        'created_at'       =>[
            'type'              =>'TIMESTAMP',
            'null'              =>true
        ],
        'updated_at'       =>[
            'type'              =>'TIMESTAMP',
            'null'              =>true
        ],
        'deleted_at'       =>[
            'type'              =>'TIMESTAMP',
            'null'              =>true
        ]
    ]);
    $this->forge->addPrimaryKey('sequence_id');
    $this->forge->addForeignKey('trimestre_id','trimestre','trimestre_id');
    $this->forge->addForeignKey('id_user', 'user', 'id_user');
    $this->forge->addForeignKey('session_id','session','session_id');
    $this->forge->addForeignKey('cycle_id','cycle','cycle_id');
    $this->forge->addForeignKey('class_id','class','class_id');
    $this->forge->addForeignKey('school_id','school','school_id');
    $this->forge->createTable('sequence');
    }

    public function down()
    {
        $this->forge->dropTable('sequence');
    }
}
