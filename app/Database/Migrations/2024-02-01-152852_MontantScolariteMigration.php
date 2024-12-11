<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MontantScolariteMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'montant_scolarite_id'      => [
                'type'                      => 'INT',
                'unsigned'                  => true,
                'null'                      => false,
                'auto_increment'            => true,
            ],
            'class_id'                  => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'montant'                  => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'year_id'                   => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'id_user'                   => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'school_id'                   => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'etat_montant_scolarite'    => [
                'type'                      => 'TEXT',
                'null'                      => false,
            ],
            'status_montant_scolarite'  => [
                'type'                      => 'INT',
                'null'                      => false,
            ],
            'created_at'                => [
                'type'                      => 'TIMESTAMP',
                'null'                      => true,
            ],
            'updated_at'                => [
                'type'                      => 'TIMESTAMP',
                'null'                      => true,
            ],
            'deleted_at'                => [
                'type'                      => 'TIMESTAMP',
                'null'                      => true,
            ],
        ]);
        $this->forge->addPrimaryKey('montant_scolarite_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->createTable('montant_scolarite');
    }

    public function down()
    {
        $this->forge->dropTable('montant_scolarite');
    }
}
