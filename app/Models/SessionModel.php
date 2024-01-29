<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'session';
    protected $primaryKey       = 'session_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_id',
        'code_session',
        'name_session',
        'id_user',
        'school_id',
        'status_session',
        'etat_session',
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

    public function getSession($name_session, $number_session, $id_school)
    {
        $builder = $this->db->table('session');
        $builder->select("*");
        $builder->join('school', 'school.school_id=session.school_id');
        $builder->where('session.code_session', $number_session);
        $builder->where('session.name_session', $name_session);
        $builder->where('session.school_id', $id_school);
        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllSession($id_school){
        $builder = $this->db->table('session');
        $builder->select("*");
        $builder->join('school', 'school.school_id=session.school_id');
        $builder->where('session.school_id', $id_school);
        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSessionById($id_session){
        $builder = $this->db->table('session');
        $builder->select("*");
        $builder->where('session.session_id', $id_session);
        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSessionStudentYear($id_student, $id_year){
        $builder = $this->db->table('session');
        $builder->select('session.session_id, session.name_session, session.code_session, session.id_user, session.school_id, session.status_session, session.etat_session, session.created_at');
        $builder->join("student_session", "student_session.session_id = session.session_id", "inner");
        $builder->join("student", "student.student_id = student_session.student_id", "inner");
        $builder->join("year", "year.year_id = student_session.year_id", "inner");

        $builder->where('student_session.year_id', $id_year);
        $builder->where('student_session.student_id', $id_student);
        $builder->where('student_session.status_stu_sess', 0);
        $builder->where('student_session.etat_stu_sess', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

}
