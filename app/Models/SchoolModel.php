<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'school';
    protected $primaryKey       = 'school_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'school_id',
        'name',
        'logo',
        'creation_date',
        'couleur',
        'code',
        'code',
        'responsable',
        'email',
        'phone',
        'id_user',
        'matricule',
        'status_school',
        'etat_school',
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

    public function getSchool($name_school, $coded_school,$create_at_school,$responsable,$email)
    {
        $builder = $this->db->table('school');
        $builder->select('*');
        $builder->where('name', $name_school);
        $builder->where('code', $coded_school);
        $builder->where('creation_date', $create_at_school);
        $builder->where('responsable', $responsable);
        $builder->where('email', $email);
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getUpdateSchool($name_school, $coded_school,$create_at_school,$responsable,$email, $logo, $color, $phone)
    {
        $builder = $this->db->table('school');
        $builder->select('*');
        $builder->where('name', $name_school);
        $builder->where('code', $coded_school);
        $builder->where('creation_date', $create_at_school);
        $builder->where('responsable', $responsable);
        $builder->where('email', $email);
        $builder->where('logo', $logo);
        $builder->where('color', $color);
        $builder->where('phone', $phone);
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getIDSchool($id_school){
        $builder = $this->db->table('school');
        $builder->select("*");
        $builder->where('school_id', $id_school);
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSchoolStudent($id_student, $id_year){
        $builder = $this->db->table('school');
        $builder->select('school.school_id, school.name, school.logo, school.creation_date, school.couleur, school.code, school.responsable, school.email, school.phone, school.matricule');
        $builder->join("student_school", "student_school.school_id = school.school_id", "inner");
        $builder->join("student", "student.student_id = student_school.student_id", "inner");
        $builder->join("year", "year.year_id = student_school.year_id", "inner");

        $builder->where('student_school.year_id', $year_id);
        $builder->where('student_school.student_id', $id_student);
        $builder->where('student_school.status_stu_scho', 0);
        $builder->where('student_school.etat_stu_scho', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> insertion des ecoles
    #- use:
    #-
    function insertschool($data)
    {
        $builder = $this->db->table('school');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    #@-- 22 --> modification des ecoles
    #- use:
    #-
    function updateschool($data)
    {
        $builder = $this->db->table('school');
        $verdic = $builder->update($data);
        return $verdic;
    }

    #@-- 3 --> supprimer des ecoles
    #- use:
    #-
    function deleteschool($data){
        $builder = $this->db->table('school');
        $verdic = $builder->delete($data);
         return $verdic;
    }

    public function findAllSchool(){
        $builder = $this->db->table('school');
        $builder->select("*");
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        $builder->orderBy('name', 'ASC');
        
        $res = $builder->get();
        return $res->getResultArray();
    }

    public function findAllSchoolByidSchool($id_school){
        $builder = $this->db->table('school');
        $builder->select("*");
        $builder->where('school_id', $id_school);
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        
        $res = $builder->get();
        return $res->getResultArray();
    }

    public function findSchoolSearch(){
        $builder = $this->db->table('school');
        $builder->select("*");
        $builder->where('status_school', 0);
        $builder->where('etat_school', 'actif');
        $builder->orderBy('name', 'ASC');

        $res = $builder->get();
        return $res->getResultArray();
    }

    // update student school
    public function update_student_school($data, $id_student, $id_year){
        $builder = $this->db->table('student_school');
        $builder->where('year_id', $id_year);
        $builder->where('student_id', $id_student);
        $verdic = $builder->update($data);
        return $verdic;
    }

    // update student session
    public function update_student_session($data, $id_student, $id_year){
        $builder = $this->db->table('student_session');
        $builder->where('year_id', $id_year);
        $builder->where('student_id', $id_student);
        $verdic = $builder->update($data);
        return $verdic;
    }

    // update student cycle
    public function update_student_cycle($data, $id_student, $id_year){
        $builder = $this->db->table('student_cycle');
        $builder->where('year_id', $id_year);
        $builder->where('student_id', $id_student);
        $verdic = $builder->update($data);
        return $verdic;
    }

    // update student class
    public function update_student_class($data, $id_student, $id_year){
        $builder = $this->db->table('student_class');
        $builder->where('year_id', $id_year);
        $builder->where('student_id', $id_student);
        $verdic = $builder->update($data);
        return $verdic;
    }

}
