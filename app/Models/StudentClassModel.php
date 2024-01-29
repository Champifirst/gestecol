<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student_class';
    protected $primaryKey       = 'student_class_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_class_id',
        'class_id',
        'student_id',
        'year_id',
        'id_user',
        'redouble',
        'status_stu_class',
        'etat_stu_class',
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

    public function getStudentByClass($id_class, $year_id){
        $builder = $this->db->table('student_class');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at');
        $builder->join('student', 'student.student_id = student_class.student_id', 'inner');
        $builder->join('year', 'year.year_id = student_class.year_id', 'inner');
        $builder->join('class', 'class.class_id = student_class.class_id', 'inner');

        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getStudentClassExist($student_id, $class_id, $year_id){
        $builder = $this->db->table('student_class');
        $builder->select('*');
        $builder->join('student', 'student.student_id=student_class.student_id');
        $builder->join('class', 'class.class_id=student_class.class_id');
        $builder->join('year', 'year.year_id=student_class.year_id');
        $builder->where('student_class.class_id', $class_id);
        $builder->where('student_class.student_id', $student_id);
        $builder->where('student_class.year_id', $year_id);
        
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
