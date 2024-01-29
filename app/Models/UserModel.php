<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'login',
        'password',
        'type_user',
        'etat_user',
        'status_user',
        'created_at',
        'updated_at',
        'deleted_at',
        'connected'
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

    public function getUserById($id_user){
        $builder = $this->db->table('user');
        $builder->where('id_user', $id_user);
        $builder->where('status_user', 0);
        $builder->where('etat_user', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function listAllUser(){
        $builder = $this->db->table('user');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function listUserActif(){
        $builder = $this->db->table('user');
        $builder->where('status_user', 0);
        $builder->where('etat_user', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function listUserInactif(){
        $builder = $this->db->table('user');
        $builder->where('status_user', 0);
        $builder->where('etat_user', 'inactif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function listUserDelete(){
        $builder = $this->db->table('user');
        $builder->where('status_user', 1);
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function listUserNotDelete(){
        $builder = $this->db->table('user');
        $builder->where('status_user', 0);
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getUserByLoginPassword($login, $password){
        $builder = $this->db->table('user');
        $builder->where('login', $login);
        $builder->where('password', $password);
        $builder->where('status_user', 0);
        $builder->where('etat_user', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

}   
