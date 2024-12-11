<?php

namespace App\Models;

use CodeIgniter\Model;

class MontantScolariteModel extends Model
{
    protected $DBGroup          = 'default'; 
    protected $table            = 'montant_scolarite';
    protected $primaryKey       = 'montant_scolarite_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'montant_scolarite_id',
        'montant',
        'school_id',
        'class_id',
        'year_id',
        'id_user',
        'etat_montant_scolarite',
        'status_montant_scolarite',
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

    public function getMontantScolarClass($year_id, $class_id, $id_school){
        $builder = $this->db->table('montant_scolarite');
        $builder->select('montant_scolarite.montant, montant_scolarite.montant_scolarite_id');
        $builder->join('school', 'school.school_id=montant_scolarite.school_id', 'inner');
        $builder->join('year', 'year.year_id=montant_scolarite.year_id', 'inner');
        $builder->join('class', 'class.class_id=montant_scolarite.class_id', 'inner');

        $builder->where('montant_scolarite.school_id', $id_school);
        $builder->where('montant_scolarite.class_id', $class_id);
        $builder->where('montant_scolarite.year_id', $year_id);
        $builder->where('montant_scolarite.etat_montant_scolarite', 'actif');
        $builder->where('montant_scolarite.status_montant_scolarite', 0);
        //--
        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        //--
        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');
        //--
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
