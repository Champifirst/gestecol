<?php

namespace App\Models;

use CodeIgniter\Model;

class TrimestreModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'trimestre';
    protected $primaryKey       = 'trimestre_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'trimestre_id',
        'name',
        'coded',
        'status_trimestre',
        'etat_trimestre',
        'id_user',
        'session_id',
        'cycle_id',
        'class_id',
        'school_id',
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

    public function getTrimestreBySchool($id_school){
        $builder = $this->db->table('trimestre');
        $builder->select('trimestre.trimestre_id, trimestre.name, trimestre.coded, trimestre.status_trimestre, trimestre.etat_trimestre, trimestre.created_at, trimestre.updated_at, trimestre.deleted_at, trimestre.id_user, trimestre.session_id, trimestre.cycle_id, trimestre.class_id, trimestre.school_id');
        $builder->join('school', 'school.school_id=trimestre.school_id');
        $builder->where('trimestre.school_id', $id_school);
        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllTrimestre(){
        $builder = $this->db->table('trimestre');
        $builder->select('*');
        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 11 --> verifier si un trimestre existe deja dans la base de donnees
    #- use:
    #-
   public function getTrimestre($name_trimestre, $number_trimestre, $cycle_id, $class_id, $session_id, $school_id)
    {
        $builder = $this->db->table('trimestre');
        $builder->select('*');
        $builder->join('session', 'session.session_id=trimestre.session_id');
        $builder->join('cycle', 'cycle.cycle_id=trimestre.cycle_id');
        $builder->join('class', 'class.class_id=trimestre.class_id');
        $builder->join('school', 'school.school_id=trimestre.school_id');
        $builder->where('trimestre.name', $name_trimestre);
        $builder->where('trimestre.coded', $number_trimestre);
        $builder->where('trimestre.cycle_id', $cycle_id);
        $builder->where('trimestre.class_id', $class_id);
        $builder->where('trimestre.session_id', $session_id);
        $builder->where('trimestre.school_id', $school_id);
        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getTrimestreById($id_trimestre){
        $builder = $this->db->table('trimestre');
        $builder->select("*");
        $builder->where('trimestre.trimestre_id', $id_trimestre);
        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> insertion des trimestres
    #- use:
    #-
    function inserttrimestre($data)
    {
        $builder = $this->db->table('trimestre');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    #@-- 21 --> selectionne un trimestre ayant ce number
    #- use:
    #-
    public function getUpdateTrim($last_coded)
    {
        $builder = $this->db->table('trimestre');
        $builder->select('*');
        $builder->where('coded', $last_coded);
        $builder->where('status_trimestre', 0);
        $builder->where('etat_trimestre', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 22 --> modification des trimestres
    #- use:
    #-
    function updatetrimestre($data)
    {
        $builder = $this->db->table('trimestre');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des trimestres
    #- use:
    #-
    function deletetrimestre($data){
        $builder = $this->db->table('trimestre');
        $verdic = $builder->delete($data);
         return $verdic;
    }

    function getTrimestreBySchoolSessionCycleName($id_school, $id_session, $id_cycle, $id_class){
        $builder = $this->db->table('trimestre');
        $builder->select('trimestre.trimestre_id, trimestre.name, trimestre.coded, trimestre.status_trimestre, trimestre.etat_trimestre, trimestre.id_user, trimestre.session_id, trimestre.cycle_id, trimestre.class_id, trimestre.school_id, trimestre.created_at, trimestre.deleted_at, trimestre.updated_at');
        $builder->join('school', 'school.school_id=trimestre.school_id');
        $builder->join('session', 'session.session_id=trimestre.session_id');
        $builder->join('cycle', 'cycle.cycle_id=trimestre.cycle_id');
        $builder->join('class', 'class.class_id=trimestre.class_id');
        $builder->where('trimestre.school_id', $id_school);
        $builder->where('trimestre.session_id', $id_session);
        $builder->where('trimestre.cycle_id', $id_cycle);
        $builder->where('trimestre.class_id', $id_class);
        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }
}
