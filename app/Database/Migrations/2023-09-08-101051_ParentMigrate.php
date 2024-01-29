<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ParentMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'parent_id'         =>[
                'type'              => 'INT',
                'auto_increment'    => true,
                'null'              => false
            ],
            'name_parent'        =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'surnameParent'      =>[
                'type'              => 'TEXT',
                'null'              => true
            ],
            'emailParent'        =>[
                'type'              => 'TEXT',
                'null'              => true
            ],
            'professionParent'   =>[
                'type'              => 'TEXT',
                'null'              => true
            ],
            'contactParent'      =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'adresseParent'      =>[
                'type'              => 'TEXT',
                'null'              => true
            ],
            'etat_parent'        =>[
                'type'              => 'TEXT',
                'null'              => false
            ],
            'status_parent'      =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'id_user'         =>[
                'type'              => 'INT',
                'null'              => false
            ],
            'created_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'updated_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ],
            'deleted_at'          =>[
                'type'              => 'TIMESTAMP',
                'null'              => true
            ]
        ]);
        $this->forge->addPrimaryKey('parent_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->createTable('parent');
    }

    public function down()
    {
        $this->forge->dropTable('parent');
    }
}
