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
        $builder->where('note.sequence_id',  $id_sequence);
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getNoteByTeaching($id_teaching_unit, $year_id, $id_sequence){
        $builder = $this->db->table('note');
        $builder->select('note.close, note.note_id, note.note, note.student_id, note.teachingunit_id, note.year_id, note.sequence_id, note.status_note, note.etat_note, note.status_note, note.created_at, note.updated_at, note.deleted_at');
        
        $builder->where('note.teachingunit_id',  $id_teaching_unit);
        $builder->where('note.year_id',  $year_id);
        $builder->where('note.sequence_id',  $id_sequence);
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getNoteTrimByTeaching($id_teaching_unit, $year_id, $id_sequence1, $id_sequence2) {
        $builder = $this->db->table('note');
        
        // Sélectionner les notes des deux séquences
        $builder->select('note.student_id, note.teachingunit_id, note.sequence_id, SUM(note.note) as total_notes, COUNT(note.note) as nb_notes');
        $builder->where('note.teachingunit_id', $id_teaching_unit);
        $builder->where('note.year_id', $year_id);
        $builder->whereIn('note.sequence_id', [$id_sequence1, $id_sequence2]); // Filtrer sur les deux séquences
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');
        $builder->groupBy('note.student_id, note.teachingunit_id'); // Grouper par étudiant et matière
    
        $res = $builder->get();
    
        // Ajouter une colonne pour la moyenne
        $results = $res->getResultArray();
        foreach ($results as &$result) {
            // Calcul de la moyenne trimestrielle
            $result['average_trim'] = $result['total_notes'] / $result['nb_notes'];
        }
    
        return $results;
    }
    

    public function getMinMaxNoteFromData($notes) {
        $minNote = isset($notes[0]['note']) ? $notes[0]['note'] : null;
        $maxNote = isset($notes[0]['note']) ? $notes[0]['note'] : null;
    
        foreach ($notes as $note) {
            if ($note['note'] < $minNote) {
                $minNote = $note['note'];
            }
            if ($note['note'] > $maxNote) {
                $maxNote = $note['note'];
            }
        }
    
        return [
            'min_note' => $minNote,
            'max_note' => $maxNote,
        ];
    }

    public function getMinMaxNoteFromDataTim($notes) {
        $minNote = isset($notes[0]['average_trim']) ? $notes[0]['average_trim'] : null;
        $maxNote = isset($notes[0]['average_trim']) ? $notes[0]['average_trim'] : null;
    
        foreach ($notes as $note) {
            if ($note['average_trim'] < $minNote) {
                $minNote = $note['average_trim'];
            }
            if ($note['average_trim'] > $maxNote) {
                $maxNote = $note['average_trim'];
            }
        }
    
        return [
            'min_note' => $minNote,
            'max_note' => $maxNote,
        ];
    }
    

    public function getStudentAverage($student_id, $year_id, $id_sequence) {
        $builder = $this->db->table('note');
        
        $builder->select('note.note, teachingunit.coefficient');
        $builder->join('teachingunit', 'teachingunit.teachingunit_id = note.teachingunit_id');
        $builder->where('note.student_id', $student_id);
        $builder->where('note.year_id', $year_id);
        $builder->where('note.sequence_id', $id_sequence);
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');
        
        $notes = $builder->get()->getResultArray();
        
        $total_notes_ponderees = 0;
        $total_coefficients = 0;
    
        foreach ($notes as $note) {
            $total_notes_ponderees += $note['note'] * $note['coefficient'];
            $total_coefficients += $note['coefficient'];
        }
    
        if ($total_coefficients > 0) {
            $resultat = $total_notes_ponderees / $total_coefficients;
            return $data []          = [
                'total_notes'        => $total_notes_ponderees,
                'total_coefficients' => $total_coefficients,
                'moyenne_student'    => round($resultat,2)
            ];
        }
    
        return null;
    }


    public function getStudentAverageTrim($student_id, $year_id, $id_sequence1, $id_sequence2) {
        $builder = $this->db->table('note');
    
        // Sélectionner les notes et coefficients pour les deux séquences
        $builder->select('note.note, note.sequence_id, teachingunit.coefficient');
        $builder->join('teachingunit', 'teachingunit.teachingunit_id = note.teachingunit_id');
        $builder->where('note.student_id', $student_id);
        $builder->where('note.year_id', $year_id);
        $builder->whereIn('note.sequence_id', [$id_sequence1, $id_sequence2]); // Filtrer les deux séquences
        $builder->where('note.status_note', 0);
        $builder->where('note.etat_note', 'actif');
    
        $notes = $builder->get()->getResultArray();
    
        $total_notes_ponderees = 0;
        $total_coefficients = 0;
    
        foreach ($notes as $note) {
            $total_notes_ponderees += $note['note'] * $note['coefficient'];
            $total_coefficients += $note['coefficient'];
        }
    
        // Calcul de la moyenne trimestrielle
        if ($total_coefficients > 0) {
            $resultat = $total_notes_ponderees / $total_coefficients;
            return [
                'total_notes'        => $total_notes_ponderees,
                'total_coefficients' => $total_coefficients,
                'moyenne_student'    => round($resultat, 2)
            ];
        }
    
        return null;
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