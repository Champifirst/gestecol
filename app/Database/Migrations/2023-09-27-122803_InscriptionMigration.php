<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InscriptionMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'inscription_id'     =>[
                'type'              => 'INT',
                'auto_increment'    => true,
                'null'              => false
            ],
            'id_user'            =>[
                'type'               => 'INT',
                'null'               => false
            ],
            'class_id'           =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'student_id'         =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'amount'             =>[
                'type'              => 'INT'
            ],
            'status_ins'         =>[
                'type'              => 'INT'
            ],
            'etat_ins'           =>[
                'type'              => 'TEXT',
                'null'              => false
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
        $this->forge->addPrimaryKey('inscription_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->createTable('inscription');
    }

    public function down()
    {
        $this->forge->dropTable('inscription');
    }
}
