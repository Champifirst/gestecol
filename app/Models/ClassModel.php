<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'class';
    protected $primaryKey       = 'class_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'class_id',
        'name',
        'session_id',
        'cycle_id',
        'id_user',
        'number',
        'school_id',
        'status_class',
        'etat_class',
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

    public function getIDClass($id_class){
        $builder = $this->db->table('class');
        $builder->select("*");
        $builder->where('class_id', $id_class);

        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> verifier si une classe existe deja dans la base de donnees
    #- use:
    #-
    public function getClass($name_class, $number_class, $id_school, $id_cycle, $session_id)
    {
        $builder = $this->db->table('class');
        $builder->select("*");
        $builder->join('school', 'school.school_id=cycle.school_id');
        $builder->join('session', 'session.session_id=class.session_id');
        $builder->join('cycle', 'cycle.cycle_id=class.cycle_id');

        $builder->where('class.name', $name_class);
        $builder->where('class.number', $number_class);
        $builder->where('class.school_id', $id_school);
        $builder->where('class.cycle_id', $id_cycle);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

     public function getUpdateClass($name, $number_class, $id_school, $id_cycle, $session_id)
    {
        $builder = $this->db->table('class');
        $builder->select("*");
        $builder->join('school', 'school.school_id=cycle.school_id');
        $builder->join('session', 'session.session_id=class.session_id');
        $builder->join('cycle', 'cycle.cycle_id=class.cycle_id');

        $builder->where('class.name', $name);
        $builder->where('class.number', $number_class);
        $builder->where('class.school_id', $id_school);
        $builder->where('class.cycle_id', $id_cycle);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneClass($class_id){
        $builder = $this->db->table('class');
        $builder->select("*");
        $builder->where('status_class', 0);
        $builder->where('etat_class', 'actif');
        $builder->where('class_id', $class_id);

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getClassStudentYear($id_student, $id_year){
        $builder = $this->db->table('class');
        $builder->select('class.class_id, class.name');
        $builder->join("student_class", "student_class.class_id = class.class_id", "inner");
        $builder->join("student", "student.student_id = student_class.student_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $id_year);
        $builder->where('student_class.student_id', $id_student);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> insertion des classes
    #- use:
    #-
    function insertclass($data)
    {
        $builder = $this->db->table('class');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    #@-- 22 --> modification des classes
    #- use:
    #-
    function updateclass($data)
    {
        $builder = $this->db->table('class');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des classes
    #- use:
    #-
    function deleteclass($data){
        $builder = $this->db->table('class');
        $verdic = $builder->delete($data);
         return $verdic;
    }

    public function getClassById($class_id){
        $builder = $this->db->table('class');
        $builder->select('*');
        $builder->where('class_id', $class_id);
        $builder->where('status_class', 0);
        $builder->where('etat_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllClassSchoolSessionCycle($id_school, $id_session, $id_cycle){
        $builder = $this->db->table('class');
        $builder->select('class.class_id, class.name, class.number, class.status_class, class.etat_class, class.etat_class, class.school_id, class.created_at, class.updated_at, class.deleted_at, class.id_user, class.cycle_id, class.session_id');
        $builder->join('school', 'school.school_id=class.school_id');
        $builder->join('session', 'session.session_id=class.session_id');
        $builder->join('cycle', 'cycle.cycle_id=class.cycle_id');
        $builder->where('class.school_id', $id_school);
        $builder->where('class.session_id', $id_session);
        $builder->where('class.cycle_id', $id_cycle);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllClass(){
        $builder = $this->db->table('class');
        $builder->select('*');
        $builder->where('status_class', 0);
        $builder->where('etat_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getClassAllStudentYearClass($id_year, $id_class){
        $builder = $this->db->table('class');
        $builder->select('class.class_id, class.name');
        $builder->join("student_class", "student_class.class_id = class.class_id", "inner");
        $builder->join("student", "student.student_id = student_class.student_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $id_year);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.class_id', $id_class);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getRedoubleStudentYearByClass($id_year, $id_class){
        $builder = $this->db->table('class');
        $builder->select('class.class_id, class.name');
        $builder->join("student_class", "student_class.class_id = class.class_id", "inner");
        $builder->join("student", "student.student_id = student_class.student_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $id_year);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.redouble', 'oui');
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.class_id', $id_class);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSexeStudentYearByClass($id_year, $id_class, $sexe){
        $builder = $this->db->table('class');
        $builder->select('class.class_id, class.name');
        $builder->join("student_class", "student_class.class_id = class.class_id", "inner");
        $builder->join("student", "student.student_id = student_class.student_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $id_year);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('student.sexe', $sexe);

        $builder->where('class.class_id', $id_class);
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    // format name class
    public function format_name_class($name){
        $explode_name = explode("#", $name);
        // $new_name = $explode_name[0]." ".$explode_name[1]." ".$explode_name[2];
        return $explode_name[0];
    }

    
}
