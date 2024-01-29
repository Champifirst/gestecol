<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'document';
    protected $primaryKey       = 'document_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'document_id',
        'name',
        'type_document',
        'school_id',
        'id_user',
        'status_document',
        'etat_document',
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


    #@-- 11 -->  recupere le id d'une table 
    #- use:
    #-
    public function getId($table, $id, $value_bd, $value_enter, $status, $etat)
    {
        $builder = $this->db->table($table);
        $builder->select($id);
        $builder->where($value_bd,  $value_enter);
        $builder->where($status, 0);
        $builder->where($etat, 'actif');
        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 12 --> verifier si un document existe deja dans la base de donnees
    #- use:
    #-
    public function getDocument($name_document, $type_document,$school_id)
    {
        
        $builder = $this->db->table('document');
        $builder->select("*");
        $builder->join('school', 'school.school_id=document.school_id');
        
        $builder->where('document.name_document', $name_document);
        $builder->where('document.type_document', $type_document);
        $builder->where('document.school_id', $school_id);
        
        $builder->where('document.status_document', 0);
        $builder->where('document.etat_document', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneDocument($document_id){
        $builder = $this->db->table('document');
        $builder->select("*");
        $builder->where('status_document', 0);
        $builder->where('etat_document', 'actif');
        $builder->where('document_id', $document_id);

        $res  = $builder->get();
        return $res->getResultArray();
    }

     public function getAllDocument($id_school){
        $builder = $this->db->table('document');
        $builder->select("*");
        $builder->join('school', 'school.school_id=document.school_id');
        $builder->where('document.school_id', $id_school);
        $builder->where('document.status_document', 0);
        $builder->where('document.etat_document', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');


        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 13 --> insertion des documents
    #- use:
    #-
    function insertdocument($data)
    {
        $builder = $this->db->table('document');
        $verdic = $builder->insert($data);
        return $verdic;
    }
    /*
    #@-- 21 --> selectionne un document ayant ce code
    #- use:
    #-
    public function getUpdateDocument($last_namedoc)
    {
        $builder = $this->db->table('document');
        $builder->select('*');
        $builder->where('name', $last_namedoc);
        $builder->where('status_document', 0);
        $builder->where('etat_document', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
*/

    #@-- 22 --> modification des documents
    #- use:
    #-
    function updatedocument($data)
    {
        $builder = $this->db->table('document');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des documents
    #- use:
    #-
    function deletedocument($data){
        $builder = $this->db->table('document');
        $verdic = $builder->delete($data);
         return $verdic;
    }
}
