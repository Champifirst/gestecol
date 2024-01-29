<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'parent';
    protected $primaryKey       = 'parent_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'parent_id',
        'name_parent',
        'surnameParent',
        'emailParent',
        'professionParent',
        'contactParent',
        'adresseParent',
        'etat_parent',
        'status_parent',
        'id_user',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function getIDParent($id_parent){
        $builder = $this->db->table('parent');
        $builder->select('parent.parent_id');
        $builder->where('parent_id', $id_parent);
        $builder->where('status_parent', 0);
        $builder->where('etat_parent', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
