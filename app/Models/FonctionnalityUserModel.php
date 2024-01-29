<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\FonctionnalityModel;

class FonctionnalityUserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'fonctionnalityuser';
    protected $primaryKey       = 'id_fonctionnalityuser';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_fonctionnalityuser',
        'id_user',
        'id_fonctionnality',
        'status_fonct_user',
        'etat_fonct_user',
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

    public function getFoncUserByIdUserIdFonc($id_user, $id_fonct){
        $builder = $this->db->table('fonctionnalityuser');
        $builder->select('fonctionnalityuser.id_fonctionnalityuser, fonctionnalityuser.id_user, fonctionnalityuser.status_fonct_user, fonctionnalityuser.etat_fonct_user, fonctionnalityuser.created_at, fonctionnalityuser.updated_at, fonctionnalityuser.deleted_at');
        $builder->join('user', 'user.id_user=fonctionnalityuser.id_user');
        $builder->join('fonctionnality', 'fonctionnality.id_fonctionnality=fonctionnalityuser.id_fonctionnality');
        $builder->where('fonctionnalityuser.id_user', $id_user);
        $builder->where('fonctionnalityuser.id_fonctionnality', $id_fonct);
        $builder->where('status_fonct_user', 0);
        $builder->where('etat_fonct_user', 'actif');
        $builder->where('status_user', 0);
        $builder->where('etat_user', 'actif');
        $builder->where('status_fonc', 0);
        $builder->where('etat_fonc', 'actif');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function insertFonctUser($data){
        $builder = $this->db->table('fonctionnalityuser');
        $verdic = $builder->insert($data);
        return $verdic;
    }

    public function getFonctUser($id_user){
        $builder = $this->db->table('fonctionnalityuser');
        $builder->select('fonctionnalityuser.id_fonctionnalityuser, fonctionnalityuser.id_user, fonctionnalityuser.status_fonct_user, fonctionnalityuser.etat_fonct_user, fonctionnalityuser.created_at, fonctionnalityuser.updated_at, fonctionnalityuser.deleted_at');
        $builder->join('user', 'user.id_user = fonctionnalityuser.id_user', "inner");
        $builder->join('fonctionnality', 'fonctionnality.id_fonctionnality = fonctionnalityuser.id_fonctionnality', "inner");
        $builder->where('fonctionnalityuser.id_user', $id_user);
        $builder->where('status_fonct_user', 0);
        $builder->where('etat_fonct_user', 'actif');
        $builder->where('status_user', 0);
        $builder->where('status_fonc', 0);
        $builder->where('etat_user', 'actif');
        $builder->where('etat_fonc', 'actif');
        
        $res = $builder->get();
        return $res->getResultArray();
    }

    public function getParentChild($id_user){
        $list_funct = $this->getFonctUser($id_user);
       
        $tab_parent = array();
        $tab_coded_parent = array();
        $tab_child = array();
        $tab_coded_child = array();
        $finaly = array();
        $FonctionnalityModel = new FonctionnalityModel();

        foreach ($list_funct as $key) {
            $fonct = $FonctionnalityModel->getFoncById($key['id_fonctionnality']);
            if ($fonct[0]['type_fonct'] == 'parent') {
                $tab_parent[] = $fonct[0]['id_fonctionnality'];
                $tab_coded_parent[] = $fonct[0]['coded'];
            }else if ($fonct[0]['type_fonct'] == 'child') {
                $tab_child[] = $fonct[0]['id_fonctionnality'];
                $tab_coded_child[] = $fonct[0]['coded'];
            }
        }

        for ($e=0; $e < sizeof($tab_parent); $e++) { 
           $finaly[] = [
                'id_parent'     => $tab_parent[$e],
                'coded_parent'  => $tab_coded_parent[$e],
                'childs'        => array()
            ];
        }

        for ($i=0; $i < sizeof($tab_child); $i++) { 
            $parent = $FonctionnalityModel->getParent($tab_child[$i]);
            if(sizeof($parent) != 0){
                //--
                $verif = 0;
                for ($j=0; $j < sizeof($finaly); $j++) { 
                    if ($finaly[$j]['coded_parent'] == $parent['coded']) {
                        $verif++;
                        $data_child = $finaly[$j]['childs'];
                        $data_child[] = [
                            'id_child' => $tab_child[$i],
                            'coded_child' => $tab_coded_child[$i],
                        ];
                        $finaly[$j]['childs'] = $data_child;
                    }
                }

                if ($verif == 0) {
                    $finaly[] = [
                        'id_parent'     => $parent['id_fonctionnality'],
                        'coded_parent'  => $parent['coded'],
                        'childs'        => array()
                    ];
                }
            }
        }

        return [];
        
    }
}
