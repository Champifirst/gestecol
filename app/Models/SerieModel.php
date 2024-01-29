<?php

namespace App\Models;

use CodeIgniter\Model;

class SerieModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'serie';
    protected $primaryKey       = 'id_serie';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'serie_id',
        'code_serie',
        'name_serie',
        'id_user',
        'school_id',
        'status_serie',
        'etat_serie',
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

    public function getSerie($name_serie, $number_serie, $id_school)
    {
        $builder = $this->db->table('serie');
        $builder->select("*");
        $builder->join('school', 'school.school_id=serie.school_id');
        $builder->where('serie.code_serie', $name_serie);
        $builder->where('serie.name_serie', $number_serie);
        $builder->where('serie.school_id', $id_school);
        $builder->where('serie.status_serie', 0);
        $builder->where('serie.etat_serie', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllSerie($id_school){
        $builder = $this->db->table('serie');
        $builder->select("*");
        $builder->join('school', 'school.school_id=serie.school_id');
        $builder->where('serie.school_id', $id_school);
        $builder->where('serie.status_serie', 0);
        $builder->where('serie.etat_serie', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }


    public function getOneSerie($serie_id){
        $builder = $this->db->table('serie');
        $builder->select("*");
        $builder->where('status_serie', 0);
        $builder->where('etat_serie', 'actif');
        $builder->where('serie_id', $serie_id);

        $res  = $builder->get();
        return $res->getResultArray();
    }
}
