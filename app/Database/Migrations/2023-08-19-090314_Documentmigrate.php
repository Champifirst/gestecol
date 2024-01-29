<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Documentmigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'document_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'name'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'type_document'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'status_document'      =>[
                'type'              =>'INT'
            ],
            'etat_document'       =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'school_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'id_user'               =>[
                'type'              =>'INT',
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
        $this->forge->addPrimaryKey('document_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('document');
    }

    public function down()
    {
        $this->forge->dropTable('document');
    }
}
