<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'student';
    protected $primaryKey       = 'student_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'surname',
        'name',
        'birth_place',
        'date_of_birth',
        'photo',
        'nationality',
        'sexe',
        'matricule',
        'status_student',
        'etat_student',
        'id_user',
        'year_id',
        'parent_id',
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

    public function getAllStudent(){
        $builder = $this->db->table('student');
        $builder->select('*');
        $builder->where('status_student', 0);
        $builder->where('etat_student', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    // tout les eleves qui appartienne a des salles 
    public function getAllStudentByYear($year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.name, student.surname, student.matricule');
        $builder->join('student_class', 'student_class.student_id=student.student_id');
        $builder->join('class', 'class.class_id=student_class.class_id');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllStudentBySchool($id_school, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.name, student.surname, student.matricule');
        $builder->join('student_school', 'student_school.student_id=student.student_id');
        $builder->join('school', 'school.school_id=student_school.school_id');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('student_school.school_id', $id_school);
        $builder->where('student_school.year_id', $year_id);
        $builder->where('student_school.status_stu_scho', 0);
        $builder->where('student_school.etat_stu_scho', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudent($matricule)
    {
        $builder = $this->db->table('student');
        $builder->select('*');
        $builder->where('matricule', $matricule);
        $builder->where('status_student', 0);
        $builder->where('etat_student', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentExist($name, $surname, $date_birth, $place_birth){
        $builder = $this->db->table('student');
        $builder->select('*');
        $builder->where('name', $name);
        $builder->where('surname', $surname);
        $builder->where('date_of_birth', $date_birth);
        $builder->where('birth_place', $place_birth);
        $builder->where('status_student', 0);
        $builder->where('etat_student', 'actif');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearSexeAll($id_class, $year_id, $sexe){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.sexe', $sexe);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearSexeAllInscrit($id_class, $year_id, $sexe){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");
        $builder->join("inscription", "inscription.student_id = student.student_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.sexe', $sexe);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->where('inscription.etat_ins', 'actif');
        $builder->where('inscription.status_ins', 0);
        $builder->orderBy('student.name', 'ASC');
        $builder->groupBy('inscription.student_id');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearSexeAllInscritNot($id_class, $year_id, $sexe){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->whereNotIn('student.student_id', function ($query) {
            $query->select('DISTINCT(inscription.student_id)')
                ->where('inscription.etat_ins', 'actif')
                ->where('inscription.status_ins', 0)
                ->from('inscription');
        });
        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.sexe', $sexe);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearSexe($id_class, $year_id, $sexe, $redouble){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.redouble', $redouble);
        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.sexe', $sexe);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearSexeNew($id_class, $year_id, $sexe){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.sexe', $sexe);
        $builder->where('student.status_student', 0);
        $builder->where('student.year_id', $year_id);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearRedouble($id_class, $year_id, $redouble){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.redouble', $redouble);
        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYear($id_class, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentBySchoolYear($id_school, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at');
        $builder->join("student_school", "student_school.student_id = student.student_id", "inner");
        $builder->join("school", "school.school_id = student_school.school_id", "inner");
        $builder->join("year", "year.year_id = student_school.year_id", "inner");

        $builder->where('student_school.year_id', $year_id);
        $builder->where('student_school.school_id', $id_school);
        $builder->where('student_school.status_stu_scho', 0);
        $builder->where('student_school.etat_stu_scho', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneStudentByClassYear($student_id, $id_class, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.student_id', $student_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearInscrit($id_class, $year_id){ //--
        $builder = $this->db->table('student');
        $builder->select('DISTINCT(inscription.student_id), inscription.created_at as date, SUM(inscription.amount) as total, student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");
        $builder->join("inscription", "inscription.student_id = student.student_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->where('inscription.status_ins', 0);
        $builder->where('inscription.etat_ins', 'actif');
        $builder->groupBy('inscription.student_id');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearAllInscritNot($id_class, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->whereNotIn('student.student_id', function ($query) {
            $query->select('DISTINCT(inscription.student_id)')
                ->where('inscription.etat_ins', 'actif')
                ->where('inscription.status_ins', 0)
                ->from('inscription');
        });
        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getStudentByClassYearNew($id_class, $year_id){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.surname, student.name, student.birth_place, student.date_of_birth, student.photo, student.nationality, student.sexe, student.matricule, student.status_student, student.etat_student, student.year_id, student.parent_id, student.created_at, student.updated_at, student.deleted_at, parent.name_parent, parent.surnameParent, parent.contactParent, student_class.redouble');
        $builder->join("student_class", "student_class.student_id = student.student_id", "inner");
        $builder->join("class", "class.class_id = student_class.class_id", "inner");
        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");
        $builder->join("year", "year.year_id = student_class.year_id", "inner");

        $builder->where('student_class.year_id', $year_id);
        $builder->where('student_class.class_id', $id_class);
        $builder->where('student_class.status_stu_class', 0);
        $builder->where('student_class.etat_stu_class', 'actif');
        $builder->where('student.year_id', $year_id);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');
        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');
        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');
        $builder->orderBy('student.name', 'ASC');
        
        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getOneStudent($id_student){
        $builder = $this->db->table('student');
        $builder->select('student.student_id, student.name, student.surname, student.birth_place, 
        student.date_of_birth, student.matricule, student.sexe, student.photo, parent.parent_id, 
        parent.name_parent, parent.surnameParent, parent.emailParent, parent.professionParent, parent.contactParent,
        parent.adresseParent');

        $builder->join("parent", "parent.parent_id = student.parent_id", "inner");

        $builder->where('student.student_id', $id_student);
        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('parent.status_parent', 0);
        $builder->where('parent.etat_parent', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 21 --> selectionne un eleve ayant ce matricule
    #- use:
    #-
    public function getUpdateStudent($last_matricule)
    {
        $builder = $this->db->table('student');
        $builder->select('*');
        $builder->where('matricule', $last_matricule);
        $builder->where('status_student', 0);
        $builder->where('etat_student', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


    #@-- 22 --> modification des eleves
    #- use:
    #-
    function updatestudent($data)
    {
        $builder = $this->db->table('student');
        $verdic = $builder->update($data);
        return $verdic;
    }


    #@-- 3 --> supprimer des eleves
    #- use:
    #-
    function deletestudent($data){
        $builder = $this->db->table('student');
        $verdic = $builder->delete($data);
         return $verdic;
    }
}
