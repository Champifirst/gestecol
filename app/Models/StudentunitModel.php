<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentunitModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student_unit';
    protected $primaryKey       = 'studentunit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'studentunit_id',
        'student_id',
        'teachingunit_id',
        'year_id',
        'user_id',
        'status_studentunit',
        'etat_studentunit',
        'created_at',
        'updated_at',
        'deleted_at'
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

    public function getStudentunitById($id_student_unit){
        $builder = $this->db->table('student_unit');
        $builder->select('*');
        $builder->where('student_unit.studentunit_id', $id_student_unit);
        $builder->where('student_unit.status_studentunit', 0);
        $builder->where('student_unit.etat_studentunit', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getId(){
        $id = $this->db->insertID();

        return $id;
    }


    // public function getStudentSubjects($student_id)
    // {
    //     $builder = $this->db->table('student_unit su');
    //     $builder->select('tu.name AS matiere_name, tu.coefficient');
    //     $builder->join('teachingunit tu', 'su.teachingunit_id = tu.teachingunit_id');
    //     $builder->join('student st', 'su.student_id = st.student_id');
        
    //     $builder->where('su.student_id', $student_id);
    //     $builder->where('su.status_studentunit', 0);  // Optionnel, dépend des besoins
    //     $builder->where('su.etat_studentunit', 'actif');  // Optionnel, dépend des besoins

    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

    function insertstudentunit($data)
    {
        $builder = $this->db->table('student_unit');
        $verdic = $builder->insertBatch($data);
        return $verdic;
    }

    public function getStudentUnitData($student_id, $year_id)
    {
        $builder = $this->db->table('student_unit su');
        $builder->select('su.*, tu.name AS matiere_name, tu.coefficient, y.year_name');
        $builder->join('teachingunit tu', 'su.teachingunit_id = tu.teachingunit_id');
        $builder->join('year y', 'su.year_id = y.year_id');

        $builder->where('su.student_id', $student_id);
        $builder->where('su.year_id', $year_id);
        
        $builder->where('su.status_studentunit', 0);  
        $builder->where('su.etat_studentunit', 'actif');

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getStudentSubjects($id_school, $id_session, $id_cycle, $id_class, $student_id) {
        $builder = $this->db->table('student_unit');
        
        // Sélection des colonnes nécessaires des tables teachingunit et student
        $builder->select('
            teachingunit.teachingunit_id, 
            teachingunit.name, 
            teachingunit.code, 
            teachingunit.coefficient, 
            teachingunit.status_teachingunit, 
            teachingunit.etat_teachingunit, 
            teachingunit.school_id, 
            student.surname, 
            student.matricule
        ');
    
        // Jointures avec les autres tables
        $builder->join('teachingunit', 'teachingunit.teachingunit_id = student_unit.teachingunit_id');
        $builder->join('student', 'student.student_id = student_unit.student_id');
        $builder->join('student_class', 'student_class.student_id = student.student_id');
        $builder->join('class', 'class.class_id = student_class.class_id');
        $builder->join('cycle', 'cycle.cycle_id = teachingunit.cycle_id');
        $builder->join('session', 'session.session_id = teachingunit.session_id');
        $builder->join('school', 'school.school_id = teachingunit.school_id');
        
        // Conditions de filtrage par les paramètres donnés
        $builder->where('teachingunit.school_id', $id_school);
        $builder->where('teachingunit.session_id', $id_session);
        $builder->where('teachingunit.cycle_id', $id_cycle);
        $builder->where('class.class_id', $id_class);
        $builder->where('student.student_id', $student_id);
        
        // Statuts et états actifs pour les tables concernées
        $builder->where('teachingunit.status_teachingunit', 0);
        $builder->where('teachingunit.etat_teachingunit', 'actif');
        
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        
        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');
        
        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        
        $builder->where('cycle.status_cycle', 0);
        $builder->where('cycle.etat_cycle', 'actif');
        
        $builder->where('student_unit.status_studentunit', 0);
        $builder->where('student_unit.etat_studentunit', 'actif');
        
        // Exécution de la requête et récupération des résultats
        $res = $builder->get();
        return $res->getResultArray();
    }
    
}
