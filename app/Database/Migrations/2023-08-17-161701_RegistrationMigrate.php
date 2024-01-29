<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RegistrationMigrate extends Migration
{
    public function up()
    {
        
    $this->forge->addField([
            'registration_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            
            'registration_date'    =>[
                'type'              =>'DATETIME',
                'null'              =>false
            ],
            'status_registration'  =>[
                'type'              =>'INT'
            ],
            'etat_registration'    =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'student_id'          =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'year_id'             =>[
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
        $this->forge->addPrimaryKey('registration_id');
        $this->forge->addForeignKey('student_id','student','student_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->createTable('registration');
    }

    public function down()
    {
        $this->forge->dropTable('registration');
    }
}

