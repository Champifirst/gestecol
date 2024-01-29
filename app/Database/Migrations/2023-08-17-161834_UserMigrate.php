<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user'   => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
                'auto_increment' => true,
            ],
            'login'     => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'password'  => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'type_user'  => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'etat_user'  => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'status_user'=> [
                'type'           => 'INT',
                'null'           => false,
            ],
            'connected' => [
                'type'           => 'TEXT',
                'null'           => false,
                'default'        => 'false',
            ],
            'created_at' => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
            'deleted_at' => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }

}
