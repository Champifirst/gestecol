<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'teacher';
    protected $primaryKey       = 'teacher_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'teacher_id',
        'matricule',
        'name',
        'surname',
        'diplome',
        'email',
        'tel',
        'photo',
        'login',
        'password',
        'id_user',
        'sexe',
        'year_id',
        'status_teacher',
        'etat_teacher',
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

    public function getId(){
        $id = $this->db->insertID();
        return $id;
    }

    public function getAllTeacherBySchool($school_id)
    {
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('school', 'school.school_id=teacher_school.school_id', 'inner');

        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTeacherBySchoolYear($school_id, $year_id){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('school', 'school.school_id=teacher_school.school_id', 'inner');

        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        $builder->where('teacher_school.year_id', $year_id);

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getTeacherBySchool($school_id, $vaccataire, $permanent)
    {
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->Where('teacher_school.type_ens', $vaccataire);
        $builder->orWhere('teacher_school.type_ens', $permanent);
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneTeacherBySchool($id_school, $teacher_id){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $id_school);
        $builder->where('teacher_school.teacher_id', $teacher_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneTeacherByLoginAndPassword($login, $password){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        
        // Conditions pour login et mot de passe
        $builder->where('teacher.login', $login);
        $builder->where('teacher.password', $password);
    
        // Conditions supplémentaires
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res = $builder->get();
        return $res->getRowArray(); // getRowArray() pour obtenir une seule ligne
    }
    

    public function getAllTeacherBySchoolTypeEng($school_id, $type_eng){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher_school.type_ens', $type_eng);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getTeacherBySchoolSexe($school_id, $sexe, $vaccataire, $permanent)
    {
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->Where('teacher_school.type_ens', $vaccataire);
        $builder->orWhere('teacher_school.type_ens', $permanent);
        $builder->where('teacher.sexe', $sexe);
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTeacherBySchoolSexe($school_id, $sexe)
    {
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->where('teacher.sexe', $sexe);
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTeacherBySchoolTypeEngSexe($school_id, $type_eng, $sexe){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teacher.diplome, teacher.email, teacher.tel, teacher.photo, teacher.login, teacher.password, teacher.matricule, teacher.sexe, teacher_school.salaire, teacher_school.type_ens');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher_school.type_ens', $type_eng);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->where('teacher.sexe', $sexe);
        
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    

    #@-- 11 --> verifier si un enseignant existe
    #- use:
    #-
    public function getTeacherExists($school_id, $year_id, $matricule, $name)
    {
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('school', 'school.school_id=teacher_school.school_id', 'inner');
        $builder->where('teacher_school.year_id', $year_id);
        $builder->where('teacher_school.school_id', $school_id);
        $builder->where('teacher.matricule', $matricule);
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        

        $builder->where('teacher.name', $name);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> selectionne un enseignant ayant ce mot de pass
    #- use:
    #-


    public function getPassword($password)
    {
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $builder->where('password', $password);
        $builder->where('status_teacher', 0);
        $builder->where('etat_teacher', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getTeacherById($id_teacher){
        $builder = $this->db->table('teacher');
        $builder->select("*");
        $builder->where('teacher.teacher_id', $id_teacher);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
    
    public function getAllTeacher($id_school){
        $builder = $this->db->table('teacher');
        $builder->select("*");
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id');
        $builder->join('school', 'school.school_id=teacher_school.school_id');

        $builder->where('teacher_school.school_id', $id_school);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    } 
    
    public function getAllConnected($id_year){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.matricule, teacher.name, teacher.surname, teacher.diplome, teacher.login, teacher.photo, teacher.tel, teacher.sexe, teacher.email');
        $builder->join('year', 'year.year_id=teacher.year_id', 'inner');

        $builder->join('teacher_class', 'teacher_class.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('class', 'class.class_id=teacher_class.class_id', 'inner');
        $builder->where('teacher_class.year_id', $id_year);
        $builder->where('teacher_class.status_teacher_class', 0);
        $builder->where('teacher_class.etat_teacher_class', 'actif');

        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');
        $builder->where('teacher.connected', 'true');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllConnectedBySchool($id_school, $year_id){
        $builder = $this->db->table('teacher');
        $builder->select("*");
        $builder->join('teacher_school', 'teacher_school.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('school', 'school.school_id=teacher_school.school_id', 'inner');

        $builder->join('teacher_class', 'teacher_class.teacher_id=teacher.teacher_id', 'inner');
        $builder->join('class', 'class.class_id=teacher_class.class_id', 'inner');
        $builder->where('teacher_class.year_id', $year_id);
        $builder->where('teacher_class.status_teacher_class', 0);
        $builder->where('teacher_class.etat_teacher_class', 'actif');

        $builder->where('teacher_school.school_id', $id_school);
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');

        $builder->where('teacher.connected', 'true');
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 13 --> insertion des enseignants
    #- use:
    #-
    public function insertteacher($data){
        $builder = $this->db->table('teacher');
        $verdic = $builder->insert($data); 
        return $verdic;
    }


    #@-- 21 --> selectionne un enseignant ayant ce mot de passe
    #- use:
    #-
    public function getLastPassword($last_password)
    {
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $builder->where('password', $last_password);
        $builder->where('status_teacher', 0);
        $builder->where('etat_teacher', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTeacherYear($id_year){
        $builder = $this->db->table('teacher');
        $builder->select('teacher.matricule, teacher.name, teacher.surname, teacher.diplome, teacher.login, teacher.photo, teacher.tel, teacher.sexe, teacher.email');
        $builder->join('year', 'year.year_id=teacher.year_id', 'inner');
        $builder->where('teacher.year_id', $id_year);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 21 --> modification des enseignants
    #- use:
    #-
    function updateteacher($data){
        $builder = $this->db->table('teacher');
        $verdic = $builder->update($data);
         return $verdic;
    }

    #@-- 3 --> supprimer des enseignants
    #- use:
    #-
    function deleteteacher($data){
        $builder = $this->db->table('teacher');
        $verdic = $builder->delete($data);
         return $verdic;
    }

    #@-- 4 --> liste des enseignants cote utilisateur
    #- use:
    #-

    function select_teacheruser(){
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $builder->where('status_teacher', 0);
        $result = $builder->get();
        return $result->getResult();

      
    }    

    #@-- 5 --> liste des enseignants cote admin
    #- use:
    #-
    function select_teacheradmin(){
        $builder = $this->db->table('teacher');
        $builder->select('*');
        $result = $builder->get();
        return $result->getResult();

      
    }    
}