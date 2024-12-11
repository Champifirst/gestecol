<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherUnitClassModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'teacher_unit_class';
    protected $primaryKey       = 'teacherunitclass_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'teacherunitclass_id',
        'teacher_id',
        'teachingunit_id',
        'year_id',
        'user_id',
        'class_id',
        'status_teacher_unit_class',
        'etat_teacher_unit_class',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Dates
    protected $useTimestamps = false;
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

    // public function getAllTeachersByClassAndYear($class_id, $year_id) {
    //     // Démarrer la requête en sélectionnant les informations nécessaires
    //     $builder = $this->db->table('teacher_unit_class');
        
    //     // Joindre les tables pertinentes pour récupérer les informations des enseignants et des matières
    //     $builder->select('teacher.teacher_id, teacher.name, teacher.surname, MAX(teachingunit.name) as unit_name, MAX(class.name) as class_name, teacher_unit_class.year_id');
        
    //     $builder->join('teacher', 'teacher.teacher_id = teacher_unit_class.teacher_id', 'inner');
    //     $builder->join('teachingunit', 'teachingunit.teachingunit_id = teacher_unit_class.teachingunit_id', 'inner');
    //     $builder->join('class', 'class.class_id = teacher_unit_class.class_id', 'inner');
        
    //     // Filtrer par l'année et la classe
    //     $builder->where('teacher_unit_class.class_id', $class_id);
    //     $builder->where('teacher_unit_class.year_id', $year_id);
        
    //     // Filtrer uniquement les enseignants actifs
    //     $builder->where('teacher_unit_class.etat_teacher_unit_class', 'actif');
    //     $builder->where('teacher_unit_class.status_teacher_unit_class', 0);  // Si 0 représente l'état actif
        
    //     // Grouper par enseignant pour éviter les doublons
    //     $builder->groupBy('teacher.teacher_id');
        
    //     // Exécuter la requête
    //     $result = $builder->get();
        
    //     // Retourner le résultat sous forme de tableau
    //     return $result->getResultArray();
    // }

    public function getTeachersByTeachingUnitClassAndYear($teachingunit_id, $class_id, $year_id) {
        // Démarrer la requête en sélectionnant les informations nécessaires
        $builder = $this->db->table('teacher_unit_class');
        
        // Joindre les tables pertinentes pour récupérer les informations des enseignants et des matières
        $builder->select('teacher.teacher_id, teacher.name, teacher.surname, teachingunit.name as unit_name, class.name as class_name');
        
        $builder->join('teacher', 'teacher.teacher_id = teacher_unit_class.teacher_id', 'inner');
        $builder->join('teachingunit', 'teachingunit.teachingunit_id = teacher_unit_class.teachingunit_id', 'inner');
        $builder->join('class', 'class.class_id = teacher_unit_class.class_id', 'inner');
        
        // Filtrer par l'ID de la matière, la classe et l'année
        $builder->where('teacher_unit_class.teachingunit_id', $teachingunit_id);
        $builder->where('teacher_unit_class.class_id', $class_id);
        $builder->where('teacher_unit_class.year_id', $year_id);
        
        // Filtrer uniquement les enseignants actifs
        $builder->where('teacher_unit_class.etat_teacher_unit_class', 'actif');
        $builder->where('teacher_unit_class.status_teacher_unit_class', 0);  // Si 0 représente l'état actif
        
        // Exécuter la requête
        $result = $builder->get();
        
        // Retourner le résultat sous forme de tableau
        return $result->getResultArray();
    }

    
    public function getSubjectsByTeacherClassAndYear($teacher_id, $class_id, $year_id) {
        $builder = $this->db->table('teacher_unit_class');
    
        // Sélectionner les colonnes nécessaires
        $builder->select('teachingunit.teachingunit_id, teachingunit.name, teachingunit.code, teachingunit.coefficient');
    
        // Joindre les tables nécessaires
        $builder->join('teachingunit', 'teachingunit.teachingunit_id = teacher_unit_class.teachingunit_id', 'inner');
        $builder->join('class', 'class.class_id = teacher_unit_class.class_id', 'inner');
        $builder->join('year', 'year.year_id = teacher_unit_class.year_id', 'inner');
    
        // Ajouter les conditions pour l'enseignant, la classe et l'année
        $builder->where('teacher_unit_class.teacher_id', $teacher_id);
        $builder->where('teacher_unit_class.class_id', $class_id);
        $builder->where('teacher_unit_class.year_id', $year_id);
    
        // Ajouter les conditions pour s'assurer que les données sont actives et valides
        $builder->where('teacher_unit_class.status_teacher_unit_class', 0);
        $builder->where('teacher_unit_class.etat_teacher_unit_class', 'actif');
    
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');
    
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
    
        $res = $builder->get();
    
        // Retourner les résultats sous forme de tableau
        return $res->getResultArray();
    }
    
    
    
    
    
    
}
