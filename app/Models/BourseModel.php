<?php

namespace App\Models;

use CodeIgniter\Model;

class BourseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'bourse';
    protected $primaryKey       = 'bourse_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'bourse_id',
        'name',
        'description',
        'amount',
        'status',
        'year_id',
        'etat',
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

    public function getAllBourses($year_id){
        $builder = $this->db->table('bourse');
        $builder->select('*');
        $builder->join('year', 'year.year_id = bourse.year_id', 'inner');

        $builder->where('bourse.status', 0);
        $builder->where('bourse.etat', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
