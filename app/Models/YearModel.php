<?php

namespace App\Models;

use CodeIgniter\Model;

class YearModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'year';
    protected $primaryKey       = 'year_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'year_id',
        'name_year',
        'start_year',
        'end_year',
        'id_user',
        'status_year',
        'etat_year',
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


    public function getYear($date_start, $date_end)
    {
        $builder = $this->db->table('year');
        $builder->select("*");
        $builder->where('start_year', $date_start);
        $builder->where('end_year', $date_end);
        $builder->where('status_year', 0);
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getYearActif()
    {
        $builder = $this->db->table('year');
        $builder->select("*");
        $builder->where('status_year', 0);
        $builder->where('etat_year', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllYear()
    {
        $builder = $this->db->table('year');
        $builder->select("*");
        $builder->where('status_year', 0);

        $res  = $builder->get();
        return $res->getResultArray();
    }
    
    #@-- 12 --> insertion des annees
    #- use:
    #-
    function insertyear($data)
    {
        $builder = $this->db->table('year');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    public function getOneYear($id_year){
        $builder = $this->db->table('year');
        $builder->select("*");
        $builder->where('status_year', 0);
        $builder->where('year_id', $id_year);
        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 22 --> modification des annees
    #- use:
    #-
    function updateyear($data)
    {
        $builder = $this->db->table('year');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des annees
    #- use:
    #-
    function deleteyear($data){
        $builder = $this->db->table('school');
        $verdic = $builder->delete($data);
         return $verdic;
    }

}
