<?php

namespace App\Models;

use CodeIgniter\Model;

class TeachingUnitModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'teachingunit';
    protected $primaryKey       = 'teachingunit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'teachingunit_id',
        'name',
        'code',
        'coefficient',
        'year_id',
        'user_id',
        'class_id',
        'cycle_id',
        'session_id',
        'school_id',
        'status_teachingunit',
        'etat_teachingunit',
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

    public function getTeachingById($id_teaching_unit){
        $builder = $this->db->table('teachingunit');
        $builder->select('*');
        $builder->where('teachingunit.teachingunit_id', $id_teaching_unit);
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getTeachingIns()
    {
       $builder = $this->db->table('teachingunit');
        $builder->select('teachingunit.name, teachingunit.code, teachingunit.coefficient');
        
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }
    #@-- 11 --> verifier si une matiere existe
    #- use:
    #-
    public function getTeaching($code, $name, $class_id, $year_id)
    {
        $builder = $this->db->table('teachingunit');
        $builder->select('*');
        $builder->join('class', 'class.class_id=teachingunit.class_id');
        $builder->join('year', 'year.year_id=teachingunit.year_id');

        $builder->where('teachingunit.code', $code);
        $builder->where('teachingunit.name', $name);
        $builder->where('teachingunit.class_id', $class_id);
        $builder->where('teachingunit.year_id', $year_id);
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getAllTeachingSchoolSessionCycleClass($id_school, $id_session, $id_cycle,$id_class){
        $builder = $this->db->table('teachingunit');
        $builder->select('teachingunit.teachingunit_id, teachingunit.name, teachingunit.code,teachingunit.coefficient,teachingunit.status_teachingunit,teachingunit.etat_teachingunit, teachingunit.school_id,teachingunit.created_at,teachingunit.updated_at,teachingunit.deleted_at, teachingunit.id_user,teachingunit.cycle_id,teachingunit.session_id,teachingunit.class_id');
        $builder->join('school', 'school.school_id=teachinguni.school_id');
        $builder->join('session', 'session.session_id=teachinguni.session_id');
        $builder->join('cycle', 'cycle.cycle_id=teachinguni.cycle_id');
        $builder->join('class', 'class.class_id=teachingunit.cycle_id');
        $builder->where('teachinguni.school_id', $id_school);
        $builder->where('teachinguni.session_id', $id_session);
        $builder->where('teachinguni.cycle_id', $id_cycle);
        $builder->where('teachinguni.class_id', $id_class);

        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');
        
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

    public function getAllTeachingSchoolSessionCycleClassOTHER($id_school, $id_session, $id_cycle, $id_class) {
        $builder = $this->db->table('teachingunit');
        
        // Sélection des colonnes nécessaires
        $builder->select('teachingunit.teachingunit_id, teachingunit.name, teachingunit.code, teachingunit.coefficient, teachingunit.status_teachingunit, teachingunit.etat_teachingunit, teachingunit.school_id, teachingunit.created_at, teachingunit.updated_at, teachingunit.deleted_at, teachingunit.user_id, teachingunit.cycle_id, teachingunit.session_id, teachingunit.class_id');
        
        // Jointures avec les autres tables
        $builder->join('school', 'school.school_id = teachingunit.school_id');
        $builder->join('session', 'session.session_id = teachingunit.session_id');
        $builder->join('cycle', 'cycle.cycle_id = teachingunit.cycle_id');
        $builder->join('class', 'class.class_id = teachingunit.class_id');
        
        // Conditions de filtrage
        $builder->where('teachingunit.school_id', $id_school);
        $builder->where('teachingunit.session_id', $id_session);
        $builder->where('teachingunit.cycle_id', $id_cycle);
        $builder->where('teachingunit.class_id', $id_class);
        
        // Statuts et état actifs
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');
        
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');
        
        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');
        
        // Exécution de la requête et récupération des résultats
        $res = $builder->get();
        return $res->getResultArray();
    }
    
    #@-- 12 --> insertion des matieres
    #- use:
    #-
    function insertteaching($data)
    {
        $builder = $this->db->table('teachingunit');
        $verdic = $builder->insertBatch($data);
        return $verdic;
    }

    #@-- 21 --> selectionne une matiere ayant ce code
    #- use:
    #-
    public function getUpdateTeaching($last_code)
    {
        $builder = $this->db->table('teachingunit');
        $builder->select('*');
        $builder->where('code', $last_code);
        $builder->where('status_teachingunit', 0);
        $builder->where('etat_teachingunit', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 22 --> modification des matieres
    #- use:
    #-
    function updateteaching($data)
    {
        $builder = $this->db->table('teachingunit');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des matieres
    #- use:
    #-
    function deleteteaching($data){
        $builder = $this->db->table('teachingunit');
        $verdic = $builder->delete($data);
         return $verdic;
    }

    public function getByIDClassByYear($id_class, $year_id){
        $builder = $this->db->table('teachingunit');
        $builder->select('teachingunit.teachingunit_id, teachingunit.name, teachingunit.code, teachingunit.status_teachingunit, teachingunit.etat_teachingunit, teachingunit.created_at, teachingunit.updated_at, teachingunit.deleted_at, teachingunit.user_id, teachingunit.year_id, teachingunit.class_id, teachingunit.coefficient');
        $builder->join('class', 'class.class_id=teachingunit.class_id');
        $builder->join('year', 'year.year_id=teachingunit.year_id');

        $builder->where('teachingunit.class_id', $id_class);
        $builder->where('teachingunit.year_id', $year_id);
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


}
