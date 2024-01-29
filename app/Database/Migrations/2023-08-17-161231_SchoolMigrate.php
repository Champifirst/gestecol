<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolMigrate extends Migration
{
    public function up()
    {
    $this->forge->addField([
            'school_id'             =>[
                'type'                      => 'INT',
                'auto_increment'            => true,
                'null'                      => false
            ],
            'name'                  =>[
                'type'                      => 'TEXT',
                'null'                      => false
            ],
            'logo'                  =>[
                'type'                      => 'TEXT',
                'null'                      => false
            ],
            'creation_date'         =>[
                'type'                      => 'DATETIME',
                'null'                      => false
                
            ],
            'couleur'               =>[
                'type'                      => 'TEXT',
                'null'                      => false
            ],
            'code'                  =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'matricule'             =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'responsable'           =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'email'                 =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'phone'                 =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'matricule'             =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'id_user'               =>[
                'type'                      =>'INT',
                'null'                      =>false
            ],
            'status_school'         =>[
                'type'                      =>'INT'
            ],
            'etat_school'           =>[
                'type'                      =>'TEXT',
                'null'                      =>false
            ],
            'created_at'            =>[
                'type'                      =>'TIMESTAMP',
                'null'                      =>true
            ],
            'updated_at'            =>[
                'type'                      =>'TIMESTAMP',
                'null'                      =>true
            ],
            'deleted_at'            =>[
                'type'                      =>'TIMESTAMP',
                'null'                      =>true
            ]
        ]);
        
        $this->forge->addPrimaryKey('school_id');
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->createTable('school');
    }

    public function down()
    {
        $this->forge->dropTable('school');
    }
}
