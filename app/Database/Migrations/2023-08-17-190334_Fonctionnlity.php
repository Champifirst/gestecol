<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Fonctionnlity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_fonctionnality'   => [
                'type'              => 'INT',
                'unsigned'          => true,
                'null'              => false,
                'auto_increment'    => true,
            ],
            'coded'             => [
                'type'              => 'TEXT',
                'null'              => false,
            ],
            'name'              => [
                'type'              => 'TEXT',
                'null'              => false,
            ],
            'array_fonct'       => [
                'type'              => 'TEXT',
                'null'              => false,
            ],
            'type_fonct'        => [
                'type'              => 'TEXT',
                'null'              => false,
            ],
            'etat_fonc'         => [
                'type'              => 'TEXT',
                'null'              => false,
            ],
            'status_fonc'       => [
                'type'              => 'INT',
                'null'              => false,
            ],
            'created_at'        => [
                'type'              => 'TIMESTAMP',
                'null'              => true,
            ],
            'updated_at'        => [
                'type'              => 'TIMESTAMP',
                'null'              => true,
            ],
            'deleted_at'        => [
                'type'              => 'TIMESTAMP',
                'null'              => true,
            ],
        ]);
        $this->forge->addKey('id_fonctionnality', true);
        $this->forge->createTable('fonctionnality');
    }

    public function down()
    {
        $this->forge->dropTable('fonctionnlity');
    }
}
