<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrancheMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'tranche_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'montant'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'name'         =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'year_id'         =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_tranche'       =>[
                'type'              =>'INT'
            ],
            'etat_tranche'        =>[
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
        $this->forge->addPrimaryKey('tranche_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->createTable('tranche');
    }

    public function down()
    {
        $this->forge->dropTable('tranche');
    }
}
