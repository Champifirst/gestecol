<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class YearMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'year_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'name_year'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'start_year'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'end_year'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'id_user'               =>[
                'type'                  =>'INT',
                'null'                  =>false
            ],
            'status_year'      =>[
                'type'              =>'INT'
            ],
            'etat_year'       =>[
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
        $this->forge->addPrimaryKey('year_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('year');
    }

    public function down()
    {
        $this->forge->dropTable('year');
    }
}

