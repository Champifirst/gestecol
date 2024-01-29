<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LicenceMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'licence_id'         =>[
                'type'              => 'INT',
                'auto_increment'    => true,
                'null'              => false
            ],
            'date_debut'         =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'date_fin'            =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'id_user'             =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'school_id'             =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'etat_licence'        =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'status_licence'      =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'created_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'updated_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'deleted_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ]
        ]);
        $this->forge->addPrimaryKey('licence_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->addForeignKey('school_id', 'school', 'school_id');
        $this->forge->createTable('licence');
    }

    public function down()
    {
        $this->forge->dropTable('licence');
    }
}
