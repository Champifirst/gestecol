<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentSchoolModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student_school';
    protected $primaryKey       = 'student_school_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_school_id',
        'school_id',
        'student_id',
        'year_id',
        'id_user',
        'status_stu_scho',
        'etat_stu_scho',
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

    public function getStudentSchoolExist($student_id, $school_id, $year_id){
        $builder = $this->db->table('student_school');
        $builder->select('*');
        $builder->join('student', 'student.student_id=student_school.student_id');
        $builder->join('school', 'school.school_id=student_school.school_id');
        $builder->join('year', 'year.year_id=student_school.year_id');
        $builder->where('student_school.school_id', $school_id);
        $builder->where('student_school.student_id', $student_id);
        $builder->where('student_school.year_id', $year_id);
        
        $builder->where('student_school.status_stu_scho', 0);
        $builder->where('student_school.etat_stu_scho', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

}
