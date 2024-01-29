<?php

namespace App\Models;

use CodeIgniter\Model;

class SequenceModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sequence';
    protected $primaryKey       = 'sequence_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sequence_id',
        'name',
        'coded',
        'id_user',
        'session_id',
        'cycle_id',
        'class_id',
        'school_id',
        'trimestre_id',
        'status_sequence',
        'etat_sequence',
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


    #@-- 11 --> verifier si une sequence existe deja dans la base de donnees
    #- use:
    #-
    public function getSequence($name_sequence, $coded_sequence, $trimestre_id, $cycle_id, $class_id, $session_id, $school_id)
    {
        $builder = $this->db->table('sequence');
        $builder->select('*');
        $builder->join('session', 'session.session_id=sequence.session_id');
        $builder->join('cycle', 'cycle.cycle_id=sequence.cycle_id');
        $builder->join('class', 'class.class_id=sequence.class_id');
        $builder->join('school', 'school.school_id=sequence.school_id');
        $builder->join('trimestre', 'trimestre.trimestre_id=sequence.trimestre_id');
        $builder->where('sequence.name', $name_sequence);
        $builder->where('sequence.coded', $coded_sequence);
        $builder->where('sequence.trimestre_id', $trimestre_id);
        $builder->where('sequence.cycle_id', $cycle_id);
        $builder->where('sequence.class_id', $class_id);
        $builder->where('sequence.session_id', $session_id);
        $builder->where('sequence.school_id', $school_id);
        $builder->where('sequence.status_sequence', 0);
        $builder->where('sequence.etat_sequence', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getOneSequence($sequence_id){
        $builder = $this->db->table('sequence');
        $builder->select("*");
        $builder->where('status_sequence', 0);
        $builder->where('etat_sequence', 'actif');
        $builder->where('sequence_id', $sequence_id);

        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getAllSequence(){
        $builder = $this->db->table('sequence');
        $builder->select('*');
        $builder->where('sequence.status_sequence', 0);
        $builder->where('sequence.etat_sequence', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSequenceBySchool($id_school){
        $builder = $this->db->table('sequence');
        $builder->select('sequence.sequence_id, sequence.name, sequence.coded, sequence.status_sequence, sequence.etat_sequence, sequence.created_at, sequence.updated_at, sequence.deleted_at, sequence.trimestre_id, sequence.id_user, sequence.session_id, sequence.cycle_id, sequence.class_id, sequence.school_id');
        $builder->join('school', 'school.school_id=sequence.school_id');
        $builder->where('sequence.school_id', $id_school);
        $builder->where('sequence.status_sequence', 0);
        $builder->where('sequence.etat_sequence', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }
    #@-- 11 --> insertion des sequences
    #- use:
    #-
    function insertsequence($data)
    {
        $builder = $this->db->table('sequence');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    public function getSequenceBySchoolSessionCycleClasseTrim($id_school, $id_session, $id_cycle, $id_class, $id_trimestre){
        $builder = $this->db->table('sequence');
        $builder->select('sequence.sequence_id, sequence.name, sequence.coded, sequence.status_sequence, sequence.etat_sequence, sequence.id_user, sequence.session_id, sequence.cycle_id, sequence.class_id, sequence.school_id, sequence.trimestre_id, sequence.created_at, sequence.deleted_at, sequence.updated_at');
        $builder->join('school', 'school.school_id=sequence.school_id');
        $builder->join('session', 'session.session_id=sequence.session_id');
        $builder->join('cycle', 'cycle.cycle_id=sequence.cycle_id');
        $builder->join('class', 'class.class_id=sequence.class_id');
        $builder->join('trimestre', 'trimestre.trimestre_id=sequence.trimestre_id');
        $builder->where('sequence.school_id', $id_school);
        $builder->where('sequence.session_id', $id_session);
        $builder->where('sequence.cycle_id', $id_cycle);
        $builder->where('sequence.class_id', $id_class);
        $builder->where('sequence.trimestre_id', $id_trimestre);
        $builder->where('sequence.status_sequence', 0);
        $builder->where('sequence.etat_sequence', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('trimestre.status_trimestre', 0);
        $builder->where('trimestre.etat_trimestre', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }



#@-- 21 --> selectionne une sequence ayant ce code
    #- use:
    #-
    public function getUpdateSeq($last_coded)
    {
        $builder = $this->db->table('sequence');
        $builder->select('*');
        $builder->where('coded', $last_coded);
        $builder->where('status_sequence', 0);
        $builder->where('etat_sequence', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 22 --> modification des sequences
    #- use:
    #-
    function updatesequence($data)
    {
        $builder = $this->db->table('sequence');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des sequences
    #- use:
    #-
    function deletesequence($data){
        $builder = $this->db->table('sequence');
        $verdic = $builder->delete($data);
         return $verdic;
    }


}
