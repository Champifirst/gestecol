<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'teacher_class';
    protected $primaryKey       = 'teacher_class_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'teacher_class_id',
        'class_id',
        'teacher_id',
        'year_id',
        'id_user',
        'etat_teacher_class',
        'status_teacher_class',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getTeacherClass($id_class, $year_id){
        $builder = $this->db->table('teacher_class');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, ');
        $builder->join('class', 'class.class_id=teacher_class.class_id');
        $builder->join('teacher', 'teacher.teacher_id=teacher_class.teacher_id');

        $builder->where('teacher_class.class_id', $id_class);
        $builder->where('teacher_class.year_id', $year_id);
        $builder->where('teacher_class.etat_teacher_class', 'actif');
        $builder->where('teacher_class.status_teacher_class', 0);

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getClassOneTeacher($id_teacher, $year_id){
        $builder = $this->db->table('teacher_class');
        $builder->select('class.class_id, class.name');
        $builder->join('class', 'class.class_id=teacher_class.class_id');
        $builder->join('teacher', 'teacher.teacher_id=teacher_class.teacher_id');

        $builder->where('teacher_class.teacher_id', $id_teacher);
        $builder->where('teacher_class.year_id', $year_id);
        $builder->where('teacher_class.etat_teacher_class', 'actif');
        $builder->where('teacher_class.status_teacher_class', 0);

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTeacherBySchoolYear($id_school, $year_id)
    {
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $builder->join('year', 'year.year_id=teacher.year_id', 'inner');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('school', 'school.school_id=teacher_school.school_id', 'inner');

        $builder->where('teacher.year_id', $year_id);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->where('teacher.status_teacher', 0);

        $builder->where('teacher_school.school_id', $id_school);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        $builder->where('teacher_school.status_teacher_school', 0);

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
    

    // public function getClassTeacher($id_school, $id_session, $id_cycle, $year_id){

    //     $builder = $this->db->table('class');
    //     $builder->select('class.class_id, class.name, teacher_class.year_id, teacher.teacher_id, teacher.name')
    //     ->join('teacher_class', 'teacher_class.class_id = class.class_id')
    //     ->join('teacher', 'teacher.teacher_id = teacher_class.teacher_id')
    //     ->join('cycle', 'cycle.cycle_id = class.cycle_id')
    //     ->join('session', 'session.session_id = class.session_id')
    //     ->join('school', 'school.school_id = class.school_id')
    //     ->where('class.etat_class', 'actif')
    //     ->where('class.status_class', 0)
    //     ->where('class.session_id', $id_session)
    //     ->where('class.cycle_id', $id_cycle)
    //     ->where('class.school_id', $id_school)
    //     ->where('(
    //         CASE
    //             WHEN teacher_class.year_id = '.$year_id.' THEN 1
    //             WHEN teacher_class.etat_teacher_class = "actif" THEN 1
    //             WHEN teacher_class.status_teacher_class = 0 THEN 1
    //             ELSE 0
    //         END
    //     ) = 1', null, false)
    //     ->where('(
    //         CASE
    //             WHEN teacher.etat_teacher = "actif" THEN 1
    //             WHEN teacher.status_teacher = 0 THEN 1
    //             ELSE 0
    //         END
    //     ) = 1', null, false)
    //     ->where('(
    //         CASE
    //             WHEN cycle.etat_cycle = "actif" THEN 1
    //             WHEN cycle.status_cycle = 0 THEN 1
    //             ELSE 0
    //         END
    //     ) = 1', null, false)
    //     ->where('(
    //         CASE
    //             WHEN session.etat_session = "actif" THEN 1
    //             WHEN session.status_session = 0 THEN 1
    //             ELSE 0
    //         END
    //     ) = 1', null, false)
    //     ->where('(
    //         CASE
    //             WHEN school.etat_school = "actif" THEN 1
    //             WHEN school.status_school = 0 THEN 1
    //             ELSE 0
    //         END
    //     ) = 1', null, false);
    
    //     return $builder->get()->getResultArray();
    // }

    
}
