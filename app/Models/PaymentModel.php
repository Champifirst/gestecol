<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'payment';
    protected $primaryKey       = 'payment_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'payment_id',
        'montant',
        'montant_lettre',
        'mode_payment',
        'motif_payment',
        'status_payment',
        'etat_payment',
        'id_user',
        'year_id',
        'school_id',
        'student_id',
        'class_id',
        'session_id',
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


public function getPayment($montant,$mode_payment,$motif_payment,$year_id, $school_id, $session_id, $class_id, $student_id)
    {
        $builder = $this->db->table('payment');
        $builder->select("*");
        $builder->join('year', 'year.year_id=payment.year_id');
        $builder->join('class', 'class.class_id=payment.class_id');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->join('session', 'session.session_id=payment.session_id');
        $builder->join('student', 'student.student_id=payment.student_id');
        

        $builder->where('payment.montant', $montant);
        $builder->where('payment.mode_payment', $mode_payment);
        $builder->where('payment.motif_payment', $motif_payment);
        $builder->where('payment.year_id', $year_id);
        $builder->where('payment.school_id', $school_id);
        $builder->where('payment.session_id', $session_id);
        $builder->where('payment.class_id', $class_id);
        $builder->where('payment.student_id', $student_id);
        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');


        $res  = $builder->get();
        return $res->getResultArray();
    }

    #@-- 1 --> insertion des payments
    #- use:
    #-
    function insertpayment($data)
    {
        $builder = $this->db->table('payment');
        $verdic = $builder->insert($data);
        return $verdic;
    }

     public function getAllPayment(){
        $builder = $this->db->table('payment');
        $builder->select('*');
        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getPaymentBySchool($id_school){
        $builder = $this->db->table('payment');
        $builder->select('payment.payment_id, payment.montant, payment.mode_payment,payment.motif_payment, payment.status_payment, payment.etat_payment, payment.created_at, payment.updated_at, payment.deleted_at, payment.id_user, payment.year_id, payment.session_id, payment.school_id, payment.class_id, payment.student_id');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->where('payment.school_id', $id_school);

        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }
   
   public function getAllPaymentYearSchool($id_school, $id_year){
        $builder = $this->db->table('payment');
        $builder->select('payment.payment_id, payment.montant, payment.mode_payment,payment.motif_payment, student.name, class.name');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->join('year', 'year.year_id=payment.year_id');
        $builder->join('student', 'student.student_id=payment.student_id');
        $builder->join('class', 'class.class_id=payment.class_id');

        $builder->where('payment.school_id', $id_school);
        $builder->where('payment.year_id', $id_year);

        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllPaymentSession($id_school, $id_year, $id_session){
        $builder = $this->db->table('payment');
        $builder->select('payment.payment_id, payment.montant, payment.mode_payment,payment.motif_payment, session.name_session, student.name, class.name');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->join('year', 'year.year_id=payment.year_id');
        $builder->join('session', 'session.year_id=payment.session_id');
        $builder->join('student', 'student.student_id=payment.student_id');
        $builder->join('class', 'class.class_id=payment.class_id');

        $builder->where('payment.school_id', $id_school);
        $builder->where('payment.year_id', $id_year);
        $builder->where('payment.session_id', $id_session);


        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('year.etat_year', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

     public function getAllPaymentClass($id_school, $id_year, $id_class){
        $builder = $this->db->table('payment');
        $builder->select('payment.payment_id, payment.montant, payment.mode_payment,payment.motif_payment,student.name, class.name');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->join('year', 'year.year_id=payment.year_id');
        $builder->join('class', 'class.class_id=payment.class_id');
         $builder->join('student', 'student.student_id=payment.student_id');

        $builder->where('payment.school_id', $id_school);
        $builder->where('payment.year_id', $id_year);
        $builder->where('payment.class_id', $id_class);


        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('payment.etat_year', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }

    public function getAllPaymentStudent($id_school, $id_year, $id_class, $id_session, $id_student){

        $builder = $this->db->table('payment');
        $builder->select('payment.payment_id, payment.montant, payment.mode_payment,payment.motif_payment,class.name, student.name, session.name');
        $builder->join('school', 'school.school_id=payment.school_id');
        $builder->join('year', 'year.year_id=payment.year_id');
        $builder->join('student', 'student.student_id=payment.student_id');
        $builder->join('class', 'class.class_id=payment.student_id');
        $builder->join('session', 'session.session_id=payment.student_id');

        $builder->where('payment.school_id', $id_school);
        $builder->where('payment.year_id', $id_year);
        $builder->where('payment.student_id', $id_student);
        $builder->where('payment.class_id', $id_class);
        $builder->where('payment.session_id', $id_session);


        $builder->where('payment.status_payment', 0);
        $builder->where('payment.etat_payment', 'actif');

        $builder->where('school.status_school', 0);
        $builder->where('school.etat_school', 'actif');

        $builder->where('year.status_year', 0);
        $builder->where('payment.etat_year', 'actif');

        $builder->where('class.status_class', 0);
        $builder->where('class.etat_class', 'actif');

        $builder->where('student.status_student', 0);
        $builder->where('student.etat_student', 'actif');

        $builder->where('session.status_session', 0);
        $builder->where('session.etat_session', 'actif');

        $res  = $builder->get();
        return $res->getResultArray();
    }


}
