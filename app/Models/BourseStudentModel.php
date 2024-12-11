<?php

namespace App\Models;

use CodeIgniter\Model;

class BourseStudentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'bourse_student';
    protected $primaryKey       = 'bourse_student_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'bourse_student_id',
        'session_id',
        'cycle_id',
        'class_id',
        'student_id',
        'year_id',
        'bourse_id',
        'user_id',
        'status',
        'etat',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function isGiveBourse($id_session, $id_cycle, $id_classe, $year_id, $id_student, $id_bourse){
        $builder = $this->db->table('bourse_student');
        $builder->select('*');
        //$builder->join('year', 'year.year_id = bourse.year_id', 'inner');
        
        $builder->where('session_id', $id_session);
        $builder->where('cycle_id', $id_cycle);
        $builder->where('class_id', $id_classe);
        $builder->where('student_id', $id_student);
        $builder->where('year_id', $year_id);
        $builder->where('bourse_id', $id_bourse);
        $builder->where('status', 0);
        $builder->where('etat', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function AllBourseStudent($id_session, $id_cycle, $id_classe, $year_id, $id_student){
        $builder = $this->db->table('bourse_student');
        $builder->select('*');
        $builder->join('bourse', 'bourse.bourse_id = bourse_student.bourse_id', 'inner');
        $builder->join('student', 'student.student_id = bourse_student.student_id', 'inner');
        
        $builder->where('bourse_student.session_id', $id_session);
        $builder->where('bourse_student.cycle_id', $id_cycle);
        $builder->where('bourse_student.class_id', $id_classe);
        $builder->where('bourse_student.student_id', $id_student);
        $builder->where('bourse_student.year_id', $year_id);
        $builder->where('bourse_student.status', 0);
        $builder->where('bourse_student.etat', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getStudentBourses($id_student, $year_id) {
        $builder = $this->db->table('bourse_student');
        
        $builder->select('bourse.name, bourse.amount, bourse.description');
        $builder->join('bourse', 'bourse.bourse_id = bourse_student.bourse_id', 'inner');
        
        $builder->where('bourse_student.student_id', $id_student);
        $builder->where('bourse_student.year_id', $year_id);
        $builder->where('bourse_student.status', 0);
        $builder->where('bourse_student.etat', 'actif');
        
        $result = $builder->get();
        return $result->getResultArray();
    }
    
}
