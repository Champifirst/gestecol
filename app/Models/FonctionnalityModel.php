<?php

namespace App\Models;

use CodeIgniter\Model;

class FonctionnalityModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'fonctionnality';
    protected $primaryKey       = 'id_fonctionnality';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_fonctionnality',
        'array_fonct',
        'type_fonct',
        'coded',
        'name',
        'status_fonc',
        'etat_fonc',
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

    public function getFunctByType($type_funct){
        $builder = $this->db->table('fonctionnality');
        $builder->where('type_fonct', $type_funct);
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getParent($id_funct){
        $builder = $this->db->table('fonctionnality');
        $builder->where('id_fonctionnality', $id_funct);
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        $data = $res->getResultArray();

        if ($data[0]['type_fonct'] == 'parent') {
            return [];
        }else if ($data[0]['type_fonct'] == 'child') {
            $all_parent_select = $this->getFunctByType("parent");
            foreach ($all_parent_select as $parent) {
                $array_funct = explode(',', $parent['array_fonct']);
                for ($i=0; $i < sizeof($array_funct); $i++) { 
                    $funct = $this->getFoncByCoded($array_funct[$i]);
                    if ($funct[0]['coded'] == $data[0]['coded']) {
                        return $parent;
                    }
                }
            }
        }

        return [];
    }

    public function getFoncByCoded($coded){
        $builder = $this->db->table('fonctionnality');
        $builder->where('coded', $coded);
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getFoncById($id_fonctionnality){
        $builder = $this->db->table('fonctionnality');
        $builder->where('id_fonctionnality', $id_fonctionnality);
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function insertFonct($data){
        $builder = $this->db->table('fonctionnality');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    public function getChildFonct($coded, $coded_verif){
        $builder = $this->db->table('fonctionnality');
        $builder->where('coded', $coded);
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        $data = $res->getResultArray();

        $data_child = array();
        foreach ($data as $row) {
            if ($row["array_fonct"] != "") {
                $coded_child = explode(",", $row["array_fonct"]);
                for ($i=0; $i < sizeof($coded_child); $i++) { 
                    $child = $this->getFoncByCoded($coded);
                    if ($coded_verif == $child[0]["coded"]) {
                        $data_child[] = $child[0];
                    }
                }
            }
        }

        return $data_child;
    }


}
