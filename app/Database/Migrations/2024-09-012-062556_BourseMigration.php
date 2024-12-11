<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BourseMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'bourse_id'     =>[
                'type'              => 'INT',
                'auto_increment'    => true,
                'null'              => false
            ],
            'name'            =>[
                'type'               => 'TEXT',
                'null'               => false
            ],
            'description'           =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'amount'         =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'status'             =>[
                'type'              => 'INT'
            ],
            'year_id'             =>[
                'type'              => 'INT'
            ],
            'etat'         =>[
                'type'              => 'TEXT'
            ],
            'created_at'         =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'updated_at'         =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'deleted_at'         =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ]
        ]);
        $this->forge->addPrimaryKey('bourse_id');
        $this->forge->addForeignKey('year_id', 'year', 'year_id');
        $this->forge->createTable('bourse');
    }

    public function down()
    {
        $this->forge->dropTable('bourse');
    }
}
