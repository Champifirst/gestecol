<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'note';
    protected $primaryKey       = 'note_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'note_id',
        'note',
        'student_id',
        'teachingunit_id',
        'year_id',
        'sequence_id',
        'status_note',
        'etat_note',
        'close',
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


    public function getNoteByStudent($student_id, $id_teaching_unit, $year_id, $id_sequence){
        $builder = $this->db->table('note');
        $builder->select('note.close, note.note_id, note.note, note.student_id, note.teachingunit_id, note.year_id, note.sequence_id, note.status_note, note.etat_note, note.status_note, note.created_at, note.updated_at, note.deleted_at');
        
        $builder->where('note.student_id',  $student_id);
        $builder->where('note.teachingunit_id',  $id_teaching_unit);
        $builder->where('note.year_id',  $year_id);
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 13 --> insertion des notes
    #- use:
    #-
    function insertnote($data1)
    {
        $builder = $this->db->table('note');
        $verdic = $builder->insertBatch($data1);
        return $verdic;
    }


    #@-- 22 --> modification des notes
    #- use:
    #-
    function updatenote($data)
    {
        $builder = $this->db->table('note');
        $verdic = $builder->updateBatch($data);
        return $verdic;
    }

}