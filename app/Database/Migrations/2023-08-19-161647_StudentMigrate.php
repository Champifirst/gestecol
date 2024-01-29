<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StudentMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id'         =>[
                'type'              =>'INT',
                'auto_increment'    =>true,
                'null'              =>false
            ],
            'name'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'surname'            =>[
                'type'              =>'TEXT'
            ],
            'birth_place'         =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'date_of_birth'      =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'matricule'           =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'sexe'                  =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'photo'                =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'nationality'          =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'status_student'       =>[
                'type'              =>'INT'
            ],
            'etat_student'        =>[
                'type'              =>'TEXT',
                'null'              =>false
            ],
            'year_id'             =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'parent_id'           =>[
                'type'              =>'INT',
                'null'              =>false
            ],
            'id_user'            =>[
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
        $this->forge->addPrimaryKey('student_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('parent_id','parent','parent_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->createTable('student');
    }

    public function down()
    {
        $this->forge->dropTable('student');
    }
}
