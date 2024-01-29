<?php

namespace App\Models;

use CodeIgniter\Model;

class CycleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'cycle';
    protected $primaryKey       = 'cycle_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cycle_id',
        'code_cycle',
        'name_cycle',
        'session_id',
        'id_user',
        'school_id',
        'status_cycle',
        'etat_cycle',
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

    public function getCycle($name_cycle, $number_cycle, $id_school, $session_id)
    {
        $builder = $this->db->table('cycle');
        $builder->select("*");
        $builder->join('school', 'school.school_id=cycle.school_id');
        $builder->join('session', 'session.session_id=cycle.session_id');

        $builder->where('cycle.code_cycle', $number_cycle);
        $builder->where('cycle.name_cycle', $name_cycle);
        $builder->where('cycle.school_id', $id_school);
        $builder->where('cycle.session_id', $session_id);
        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllCycle($id_school, $id_session){
        $builder = $this->db->table('cycle');
        $builder->select("*");
        $builder->join('school', 'school.school_id=cycle.school_id');
        $builder->join('session', 'session.session_id=cycle.session_id');
        $builder->where('cycle.school_id', $id_school);
        $builder->where('cycle.session_id', $id_session);
        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getCycleById($cycle_id){
        $builder = $this->db->table('cycle');
        $builder->select("*");
        $builder->where('cycle.cycle_id', $cycle_id);
        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    
}
