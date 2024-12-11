<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentCycleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student_cycle';
    protected $primaryKey       = 'student_cycle_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_cycle_id',
        'cycle_id',
        'student_id',
        'year_id',
        'id_user',
        'status_stu_cycle',
        'etat_stu_cycle',
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

     public function getStudentCycleById($student_id, $year_id){
        $builder = $this->db->table('student_cycle');
        $builder->select('*');
        $builder->join('student', 'student.student_id=student_cycle.student_id');
        $builder->join('cycle', 'cycle.cycle_id=student_cycle.cycle_id');
        $builder->join('year', 'year.year_id=student_cycle.year_id');
        $builder->where('student_cycle.student_id', $student_id);
        $builder->where('student_cycle.year_id', $year_id);
        
        $builder->where('student_cycle.status_stu_cycle', 0);
        $builder->where('student_cycle.etat_stu_cycle', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
    
    public function getStudentCycleExist($student_id, $cycle_id, $year_id){
        $builder = $this->db->table('student_cycle');
        $builder->select('*');
        $builder->join('student', 'student.student_id=student_cycle.student_id');
        $builder->join('cycle', 'cycle.cycle_id=student_cycle.cycle_id');
        $builder->join('year', 'year.year_id=student_cycle.year_id');
        $builder->where('student_cycle.cycle_id', $cycle_id);
        $builder->where('student_cycle.student_id', $student_id);
        $builder->where('student_cycle.year_id', $year_id);
        
        $builder->where('student_cycle.status_stu_cycle', 0);
        $builder->where('student_cycle.etat_stu_cycle', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

}
