<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeacherSchool extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'teacher_school_id'     => [
                'type'                  => 'INT',
                'unsigned'              => true,
                'null'                  => false,
                'auto_increment'        => true,
            ],
            'school_id'             => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'teacher_id'            => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'year_id'               => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'salaire'               => [
                'type'                  => 'TEXT',
                'null'                  => false,
            ],
            'type_ens'              => [
                'type'                  => 'TEXT',
                'null'                  => false,
            ],
            'etat_teacher_school'   => [
                'type'                  => 'TEXT',
                'null'                  => false,
            ],
            'status_teacher_school' => [
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
        $this->forge->addPrimaryKey('teacher_school_id');
        $this->forge->addForeignKey('school_id','school','school_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('teacher_id','teacher','teacher_id');
        $this->forge->createTable('teacher_school');
    }

    public function down()
    {
        $this->forge->dropTable('teacher_school');
    }
}
