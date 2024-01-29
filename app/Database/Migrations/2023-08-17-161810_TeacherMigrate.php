<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeacherMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'teacher_id'    => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null'           => false,
                'auto_increment' => true,
            ],
            'matricule'     => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'name'          => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'surname'       => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'diplome'       => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'login'         => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'photo'         => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'tel'           => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'sexe'          => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'email'         => [
                'type'           => 'TEXT',
                'null'           => true
            ],
            'password'      => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'etat_teacher'  => [
                'type'           => 'TEXT',
                'null'           => false,
            ],
            'year_id'       => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'id_user'       => [
                'type'           => 'INT',
                'null'           => false,
            ],
            'status_teacher'=> [
                'type'           => 'INT',
                'null'           => false,
            ],
            'created_at'    => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
            'updated_at'    => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
            'deleted_at'    => [
                'type'           => 'TIMESTAMP',
                'null'           => true,
            ],
        ]);
        $this->forge->addPrimaryKey('teacher_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->createTable('teacher');
    }

    public function down()
    {
        $this->forge->dropTable('teacher');
    }

}
