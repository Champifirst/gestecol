<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenceModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'licence';
    protected $primaryKey       = 'licence_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'licence_id',
        'date_debut',
        'date_fin',
        'id_user',
        'school_id',
        'status_licence',
        'etat_licence',
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

    public function getLicenceDescBySchool($id_school){
        $builder = $this->db->table('licence');
        $builder->select('licence.licence_id, licence.date_debut, licence.date_fin, licence.id_user, licence.school_id, licence.status_licence, licence.etat_licence, licence.created_at, licence.updated_at, licence.deleted_at');
        $builder->join('school', 'school.school_id=licence.school_id', 'inner');
        $builder->where('licence.school_id', $id_school);
        $builder->where('licence.status_licence', 0);
        $builder->where('licence.etat_licence', 'actif');
        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        $builder->orderBy('licence.licence_id', 'DESC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

}
