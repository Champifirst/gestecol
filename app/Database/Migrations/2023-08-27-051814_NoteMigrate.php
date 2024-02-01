<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NoteMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'note_id'         =>[
            'type'              =>  'INT',
            'auto_increment'    =>  true,
            'null'              =>  false
        ],  
        'note'      =>[ 
            'type'              =>  'TEXT',
            'null'              =>  false
        ],  
        'status_note'       =>[ 
            'type'              =>  'INT' 
        ],  
        'etat_note'        =>[  
            'type'              =>  'TEXT',
            'null'              =>  false
        ],  
        'student_id'       =>[  
            'type'              =>  'INT',
            'null'              =>  false
        ],  
        'teachingunit_id'  =>[  
            'type'              =>  'INT',
            'null'              =>  false
        ],  
        'year_id'          =>[  
            'type'              =>  'INT',
            'null'              =>  false
        ],  
        'sequence_id'      =>[  
            'type'              =>  'INT',
            'null'              =>  false
        ], 
        'close'           =>[   
            'type'              =>  'TEXT', // yes fermer no ouvert autorisation par admin avant ouverture
            'null'              =>  false
        ],  
        'created_at'       =>[  
            'type'              =>  'TIMESTAMP',
            'null'              =>  true
        ],  
        'updated_at'       =>[  
            'type'              =>  'TIMESTAMP',
            'null'              =>  true
        ],  
        'deleted_at'       =>[  
            'type'              =>  'TIMESTAMP',
            'null'              =>  true
        ]   
    ]); 
    $this->forge->addPrimaryKey('note_id');
    $this->forge->addForeignKey('student_id','student','student_id');
    $this->forge->addForeignKey('teachingunit_id','teachingunit','teachingunit_id');
    $this->forge->addForeignKey('year_id','year','year_id');
    $this->forge->addForeignKey('sequence_id','sequence','sequence_id');
    $this->forge->createTable('note');
    }

    public function down()
    {
        $this->forge->dropTable('note');
    }
}
