<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentSessionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student_session';
    protected $primaryKey       = 'student_session_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_session_id',
        'session_id',
        'student_id',
        'year_id',
        'id_user',
        'status_stu_sess',
        'etat_stu_sess',
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

    public function getStudentSessionExist($student_id, $session_id, $year_id){
        $builder = $this->db->table('student_session');
        $builder->select('*');
        $builder->join('student', 'student.student_id=student_session.student_id');
        $builder->join('session', 'session.session_id=student_session.session_id');
        $builder->join('year', 'year.year_id=student_session.year_id');
        $builder->where('student_session.session_id', $session_id);
        $builder->where('student_session.student_id', $student_id);
        $builder->where('student_session.year_id', $year_id);
        
        $builder->where('student_session.status_stu_sess', 0);
        $builder->where('student_session.etat_stu_sess', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
