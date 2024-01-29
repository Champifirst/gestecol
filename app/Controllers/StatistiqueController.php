<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\FonctionnalityUserModel;
use App\Models\UserModel;
use App\Models\StudentModel;
use App\Models\SchoolModel;
use App\Models\TeacherModel;
use App\Models\SalaireModel;
use App\Models\YearModel;
use App\Models\ClassModel;

use App\Controllers\History;
use \Firebase\JWT\JWT;
include('History/HistorySession.php');

class StatistiqueController extends ResourcePresenter
{
    use ResponseTrait;


    public function statistique1($id_school){
        $UserModel      = new UserModel();
        $StudentModel   = new StudentModel();
        $SchoolModel    = new SchoolModel();
        $TeacherModel   = new TeacherModel();
        $YearModel      = new YearModel();
        $SalaireModel   = new SalaireModel();

        // select year active
        $data_year = $YearModel->getYearActif(); 
        $year = $data_year[0]["year_id"];

        $data_personnel = array();
        $data_conntect  = array();
        $data_student   = array();
        $data_school    = array();
        $data_salaire   = array();
        $data_scolarite = array();

        if ($id_school == 0) {
            # concerne toutes les ecoles
            $data_personnel = $TeacherModel->getAllTeacherYear($year);
            $data_conntect  = $TeacherModel->getAllConnected($year);
            $data_student   = $StudentModel->getAllStudent($year);
            $data_school    = $SchoolModel->findAllSchool();
            $data_salaire   = $SalaireModel->getSumAllSalaireShool($year);
        }else{
            //concerne une seule ecole
            $data_personnel = $TeacherModel->getAllTeacherBySchoolYear($id_school, $year);
            $data_conntect  = $TeacherModel->getAllConnectedBySchool($id_school, $year);
            $data_student   = $StudentModel->getAllStudentBySchool($id_school, $year);
            $data_school    = $SchoolModel->getIDSchool($id_school);
            $data_salaire   = $SalaireModel->getSumSalaireBySchoolYear($id_school, $year);
        }
        
        $data_return = [
            "personnel" => sizeof($data_personnel),
            "student"   => sizeof($data_student),
            "school"    => sizeof($data_school),
            "scolarite" => 0,
            "salaire"   => $data_salaire[0]["total"],
            "connected" => sizeof($data_conntect)
        ];

        return $this->respond($data_return);
    }

    public function Diagramme_effectif($id_school){
        $StudentModel   = new StudentModel();
        $YearModel      = new YearModel();
        $ClassModel     = new ClassModel();

        // select year active
        $data_year = $YearModel->getYearActif(); 
        $year = $data_year[0]["year_id"];

        $data_class = $ClassModel->getAllClass();
        $data_final = array();

        foreach ($data_class as $row) {
            $data_student = $ClassModel->getClassAllStudentYearClass($year, $row['class_id']);
            // redoublant
            $student_redouble = $ClassModel->getRedoubleStudentYearByClass($year, $row['class_id']);
            // fille
            $data_fille = $ClassModel->getSexeStudentYearByClass($year, $row['class_id'], 'F');

            $data_final[] = [
                "class"    => mb_strtoupper($ClassModel->format_name_class($row['name'])),
                "count"    => sizeof($data_student),
                "redouble" => sizeof($student_redouble),
                "fille"    => sizeof($data_fille),
            ];
        }

        $response = [
            "success"           => true,
            "status"            => 200,
            "code"              => "success",
            "title"             => "Réussite",
            "data_effectif"     => $data_final
        ];
        return $this->respond($response);

    }
}
