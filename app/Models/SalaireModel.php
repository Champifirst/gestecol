<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaireModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'salaire';
    protected $primaryKey       = 'id_salaire';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_salaire',
        'code_payement',
        'teacher_id',
        'montant',
        'montant_lettre',
        'mode_payement',
        'date_payement',
        'status_salaire',
        'etat_salaire',
        'id_user',
        'year_id',
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

    public function getSumSalaireBySchoolYear($id_school, $year_id){
        $builder = $this->db->table('salaire');
        $builder->select('SUM(salaire.montant) as total');
        $builder->join("teacher", "teacher.teacher_id = salaire.teacher_id", "inner");
        $builder->join("teacher_school", "teacher_school.teacher_id = teacher.teacher_id", "inner");
        $builder->join("school", "school.school_id = teacher_school.school_id", "inner");
        $builder->join("year", "year.year_id = salaire.year_id", "inner");

        $builder->where('teacher.year_id', $year_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $builder->where('teacher_school.school_id', $id_school);
        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('salaire.status_salaire', 0);
        $builder->where('salaire.etat_salaire', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getSumAllSalaireShool($year_id){
        $builder = $this->db->table('salaire');
        $builder->select('SUM(salaire.montant) as total');
        $builder->join("teacher", "teacher.teacher_id = salaire.teacher_id", "inner");
        $builder->join("teacher_school", "teacher_school.teacher_id = teacher.teacher_id", "inner");
        $builder->join("school", "school.school_id = teacher_school.school_id", "inner");
        $builder->join("year", "year.year_id = salaire.year_id", "inner");

        $builder->where('teacher.year_id', $year_id);
        $builder->where('teacher.status_teacher', 0);
        $builder->where('teacher.etat_teacher', 'actif');

        $builder->where('teacher_school.status_teacher_school', 0);
        $builder->where('teacher_school.etat_teacher_school', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('salaire.status_salaire', 0);
        $builder->where('salaire.etat_salaire', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }
}
