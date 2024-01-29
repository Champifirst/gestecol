<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SalaireMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'id_salaire'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'code_payement'      =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'mode_payement'      =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'date_payement'      =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'montant'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'montant_lettre'    =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'teacher_id'        =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'id_user'            =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'year_id'            =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_salaire'     =>[
                'type'              =>'INT'
            ],
            'etat_salaire'       =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'created_at'         =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'updated_at'         =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ],
            'deleted_at'         =>[
                'type'              =>'TIMESTAMP',
                'null'              =>true
            ]
        ]);
        $this->forge->addPrimaryKey('id_salaire');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('teacher_id','teacher','teacher_id');
        $this->forge->createTable('salaire');
    }

    public function down()
    {
        $this->forge->dropTable('salaire');
    }
}
