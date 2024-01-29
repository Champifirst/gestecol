<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TeacherClassMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'teacher_class_id'     => [
                'type'                  => 'INT',
                'unsigned'              => true,
                'null'                  => false,
                'auto_increment'        => true,
            ],
            'class_id'             => [
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
            'id_user'               => [
                'type'                  => 'INT',
                'null'                  => false,
            ],
            'etat_teacher_class'   => [
                'type'                  => 'TEXT',
                'null'                  => false,
            ],
            'status_teacher_class' => [
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
        $this->forge->addPrimaryKey('teacher_class_id');
        $this->forge->addForeignKey('class_id','class','class_id');
        $this->forge->addForeignKey('year_id','year','year_id');
        $this->forge->addForeignKey('teacher_id','teacher','teacher_id');
        $this->forge->addForeignKey('id_user','user','id_user');
        $this->forge->createTable('teacher_class');
    }

    public function down()
    {
        $this->forge->dropTable('teacher_class');
    }
}
