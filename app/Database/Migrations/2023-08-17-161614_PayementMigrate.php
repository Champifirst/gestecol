<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PayementMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'payment_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'montant'                =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'montant_lettre'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'mode_payment'         =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
             'motif_payment'         =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],

            'id_user'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'year_id'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'school_id'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'student_id'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'class_id'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'session_id'              =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'status_payment'       =>[
                'type'              =>'INT'
            ],
            'etat_payment'        =>[
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
        $this->forge->addPrimaryKey('payment_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('session_id','session','session_id');
        $this->forge->createTable('payment');
    }

    public function down()
    {
        $this->forge->dropTable('payment');
    }
}
