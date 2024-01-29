<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FonctionnalityUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_fonctionnalityuser' => [
                    'type'              => 'INT',
                    'unsigned'          => true,
                    'null'              => false,
                    'auto_increment'    => true
            ],
            'id_user'               => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'id_fonctionnality'     => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'etat_fonct_user'       => [
                'type'                  => 'TEXT',
                'null'                  => false,
            ],
            'status_fonct_user'     => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'created_at'            => [
                'type'                  => 'TIMESTAMP',
                'null'                  => true,
            ],
            'updated_at'            => [
                'type'                  => 'TIMESTAMP',
                'null'                  => true,
            ],
            'deleted_at'            => [
                'type'                  => 'TIMESTAMP',
                'null'                  => true,
            ],
        ]);
        $this->forge->addKey('id_fonctionnalityuser', true);
        $this->forge->addForeignKey('id_user', 'user', 'id_user');
        $this->forge->addForeignKey('id_fonctionnality', 'fonctionnality', 'id_fonctionnality');
        $this->forge->createTable('fonctionnalityuser');
    }

    public function down()
    {
        $this->forge->dropTable('fonctionnalityuser');
    }
}
