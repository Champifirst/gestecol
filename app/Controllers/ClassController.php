<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\ClassModel;
use App\Models\DocumentModel;
use App\Models\SessionModel;
use App\Models\CycleModel;
use App\Models\YearModel;
use App\Models\SchoolModel;
use App\Controllers\History;
use App\Models\TeacherClassModel;
use App\Models\TeacherModel;
use App\Models\StudentClassModel;
include('History/HistorySession.php');


class ClassController extends ResourcePresenter
{
    
    use ResponseTrait;

    public function save(){
        return view('class/save.php');
    }
    
    public function liste(){
        return view('class/liste.php');
    }

    #@-- 1 --> insertion des classes
    #- use:
    #-
    public function insertclass()
    {
        // validation du formulaire 
        $rules = [
            'name_class'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_class'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'id_cycle'              => [
                'rules'     => 'required'
            ],
            'name_serie'            => [
                'rules'     => 'required'
            ],
            'name_session'           => [
                'rules'     => 'required'
            ]
        ];

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];

        if ($this->validate($rules)) {
            //validation good
            $name_class     = $this->request->getvar('name_class');
            $number_class   = $this->request->getvar('number_class');
            $name_school    = $this->request->getvar('name_school');
            $id_cycle       = $this->request->getvar('id_cycle');
            $name_serie     = $this->request->getvar('name_serie');
            $name_session   = $this->request->getvar('name_session');
            $numero_class   = $this->request->getvar('numero_class');
            if ($numero_class == null) {
                $numero_class = 0;
            }
            
            $user_id        = $this->request->getvar('user_id');
            

            $ClassModel     = new ClassModel();
            $data_class     = $ClassModel->getClass($name_class, $number_class, $name_school, $id_cycle, $name_session);

            if (sizeof($data_class) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La classe existe déja',
                ];
                 // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Classe", "", "", "Cette classe existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'session_id'        => $name_session,
                    'name'              => strtolower($name_class)."#".$name_serie."#".$numero_class,
                    'number'            => strtolower($number_class),
                    'school_id'         => $name_school,
                    'id_user'           => $user_id,
                    'cycle_id'          => $id_cycle,
                    'status_class'      => 0,
                    'etat_class'        => 'actif',
                    'created_at'        => date("Y-m-d H:m:s"),
                    'updated_at'        => date("Y-m-d H:m:s"),
                ];
                if ($ClassModel->insertclass($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'insertion reussir',
                    ];
                      // history
                    $donnee = $data["session_id"].",".$data["name"].",".$data["number"].",".$data["school_id"].",".$data["id_user"].",".$data["cycle_id"].",".$data["status_class"].",".$data["etat_class"].",".$data["created_at"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Classe", "", "", $donnee);
                    return $this->respond($response);

                }
                else{
                    // echec d'insertion
                    
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'echec insertion',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Classe", "", "", "Echec d'insertion");
                    return $this->respond($response);
                }
            }
        }
        else {
            ///validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "Error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec d'insertion"
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Classe", "", "", "Echec d'insertion, les données sont incorrectes ");
            return $this->respond($response);
        }
    }

    #@-- 2 --> modifications des classes
    #- use:
    #-

    public function updateclass()
    {
        // extenciation de la classe ClassModel
        $ClassModel = new ClassModel();

        // validation des champs
        $rules = [
            'name_class'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_class'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'id_cycle'              => [
                'rules'     => 'required'
            ],
            'name_serie'            => [
                'rules'     => 'required'
            ],
            'name_session'           => [
                'rules'     => 'required'
            ],
            'numero_class'          => [
                'rules'     => 'required'
            ],
            'class_id'               => [
                'rules'     => 'required'
            ],
        ];

         if ($this->validate($rules)) {
            // validation good
            //validation good
            $name_class     = $this->request->getvar('name_class');
            $number_class   = $this->request->getvar('number_class');
            $name_school    = $this->request->getvar('name_school');
            $id_cycle       = $this->request->getvar('id_cycle');
            $name_serie     = $this->request->getvar('name_serie');
            $name_session   = $this->request->getvar('name_session');
            $numero_class   = $this->request->getvar('numero_class');
                if ($numero_class == null) {
                $numero_class = 0;
            }
            $user_id        = $this->request->getvar('user_id');

            $class_id        = $this->request->getvar('class_id');
            $name            = strtolower($name_class)."#".$name_serie."#".$numero_class;

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            # verifier si une classe existe
            $data_class= $ClassModel->getUpdateClass($name, $number_class, $name_school, $id_cycle, $name_session);

            if (sizeof($data_class) != 0) {
               
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     =>'cette classe existe deja',
                ];
               // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Classe", "", "", "cette classe existe deja");
                return $this->respond($response);
            } 
            else {
                $data = [
                    'session_id'        => $name_session,
                    'name'              => $name,
                    'number'            => strtolower($number_class),
                    'school_id'         => $name_school,
                    'id_user'           => $user_id,
                    'cycle_id'          => $id_cycle,
                    'updated_at'        => date("Y-m-d H:m:s"),
                ];

                if ($ClassModel->where('class_id', $class_id)->set($data)->update() === false) {
                    // echec de modification
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec modification",
                        ];
                    // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Classe", "", "", "Echec de modification");
                return $this->respond($response);
                }else
                    {
                     // modification reussir
                    $response = [
                        'success' => true,
                        'status'  => 200,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "modification reussir",
                    ];
                    $donnee = $data['session_id'].",".$data['name'].",". $data['number_class'].",". $data['school_id'].",". $data['id_user'].",". $data['cycle_id'].",". $data['updated_at'];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Classe", "", "", $donnee);

                    return $this->respond($response);
                }

            }
                
        }else{
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec de validation"
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Classe", "", "", "Echec de validation, les champs sont incorrects");
            return $this->respond($response);
        }
    }

    public function getOneClassAndTeacher($class_id,$teacher_id,$id_school){
        // extenciation de la classe ClassModel
        $ClassModel             = new ClassModel();
        $YearModel              = new YearModel();
        $TeacherModel           = new TeacherModel();
        $SchoolModel            = new SchoolModel();
        $school                 = $SchoolModel->findAllSchoolByidSchool($id_school);
        $class                  = $ClassModel->getOneClass($class_id);
        // session
        $HistorySession         = new HistorySession();
        $data_session           = $HistorySession-> getInfoSession();
        $id_user                = $data_session['id_user'];
        $type_user              = $data_session['type_user'];
        $login                  = $data_session['login'];
        $password               = $data_session['password'];
        $yearActif              = $YearModel->getYearActif();
        $year_id                = $yearActif[0]['year_id']; 

        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver cette école',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette ecole n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'avons pas pu trouver cette classe',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette classe n'existe pas");
            return $this->respond($response);
        }

        $class = $ClassModel->getOnclasseTeachYear($class_id, $teacher_id, $id_school, $year_id);
        $data_final = array();
        if (sizeof($class) != 0) {
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération reussir',
                "data"    => array(),
            ];

            $data_final[] = [
                "class_id"          => $class['class_id'],
                "class_name"        => $class['class_name'],
                "class_number"      => $class['class_number'],
                "teacher_name"      => $class['teacher_name'],
                "teacher_surname"   => $class['teacher_surname'],
                "teacher_id"        => $teacher_id,
            ];
        }
            $enseignants = $TeacherModel->getAllTeacherBySchool($id_school);

            $response = [
                "success"           => true,
                "status"            => 200,
                "code"              => "success",
                "title"             => "Réussite",
                "msg"               => 'Opération reussir',
                "data"              => $data_final,
                "enseignants"       => $enseignants,
            ];
             //history
             $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "Opération reussir");
             return $this->respond($response);

    }

    #@-- 3 --> supprimer des classes
    #- use:
    #-

    public function deleteclass($class_id){

        $ClassModel = new ClassModel(); 
        $data = $ClassModel->getOneClass($class_id);

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password']; 
  
        if (sizeof($data) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'msg'     => "Cette classe n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Classe", "", "", "Cette classe n'existe pas");
            return $this->respond($response);
        }else{
                $tab = [
                    "status_class" => 1,
                    "etat_class"   =>'inactif',
                    "deleted_at"  => date("Y-m-d H:m:s"),
                ];

            if ($ClassModel->where('class_id', $class_id)->set($tab)->update() !== false) {

                // suppression reussir
                $response = [
                  "success" => true,
                  "status"  => 200,
                  "code"    => "Success",
                  "title"   => "Réussite",
                  "msg"     => 'Suppression reussir',
                ];

                $donnee = $tab['status_class'].",".$tab['etat_class']. ",". $tab['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Classe", "", "", $donnee);
                return $this->respond($response);
                
            }else{
                // echec de suppression
                $response = [
                  "success" => false,
                  "status"  => 500,
                  "code"    => "error",
                  "title"   => "Erreur",
                  "msg"     => 'echec Suppression',
                ];
                //history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Classe", "", "", "echec Suppression");
                return $this->respond($response);
                 
            }
        }
    }

    public function allClass($id_school, $id_session, $id_cycle){

        $ClassModel   = new ClassModel();
        $SessionModel = new SessionModel();
        $CycleModel   = new CycleModel();
        $YearModel    = new YearModel();
        $SchoolModel  = new SchoolModel();

        $school       = $SchoolModel->findAllSchoolByidSchool($id_school);
        $session      = $SessionModel->getSessionById($id_session);
        $cycle        = $CycleModel->getCycleById($id_cycle);

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password']; 

        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette école n\'existe pas',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "cette ecole n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($session) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette session n\'existe pas',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "cette session n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($cycle) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Ce cycle n\'existe pas',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "ce cycle n'existe pas");
            return $this->respond($response);
        }

        $data_class = $ClassModel->getAllClassSchoolSessionCycle($id_school, $id_session, $id_cycle);

        if (sizeof($data_class) == 0) {
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération reussir',
                "data"    => array(),
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "Opération reussir");
            return $this->respond($response);
        }
        //-- restaure data
        $data_organize = array();
        foreach ($data_class as $row) {
            $row['name'] = $ClassModel->format_name_class($row['name']);
            $data_organize[] = $row;
        }

        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "success",
            "title"   => "Réussite",
            "msg"     => 'Opération reussir',
            "data"    => $data_organize,
        ];
        //history
        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", 'Opération reussir');
        return $this->respond($response);
    }

    public function getClassSchoolYearControl($id_school, $id_session, $id_cycle){
        $TeacherModel = new TeacherModel();
        $ClassModel = new ClassModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $StudentClassModel = new StudentClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }
        
        $data_classe = $ClassModel->getAllClassSchoolSessionCycle($id_school, $id_session, $id_cycle);
        
        $data_final = array();
        $enseignants = array();
        // verifier ceux qui ont des salles de classes
        foreach ($data_classe as $row) {
            # selectionner les enseignant de chaque salle
            $teacher = $TeacherClassModel->getTeacherClass($row["class_id"], $year_id);
            $nombre_eleve = $StudentClassModel->getStudentCountByClassAndYear($row["class_id"], $year_id);
            $name_teacher = "";
            $id_teacher = "";
            if (sizeof($teacher) != 0) {
                $name_teacher = $teacher[0]["name"]." ".$teacher[0]["surname"];
                $id_teacher = $teacher[0]["teacher_id"];
            }
            $data_final[] = [
                "class_id"       => $row["class_id"],
                "code_class"     => $row["number"],
                "name_class"     => $ClassModel->format_name_class($row["name"]),
                "id_enseignant"  => $id_teacher,
                "name_enseignant"=> $name_teacher,
                "nombre_eleve"   => $nombre_eleve,
            ];
            $enseignants = $TeacherModel->getAllTeacherBySchool($id_school);
        }

        $response = [
            "success"       => true,
            "status"        => 200,
            "code"          => "Success",
            "title"         => "Réussite",
            "msg"           => 'Opération réussir',
            "data"          => $data_final,
            "enseginants"   => $enseignants
        ];
        return $this->respond($response);
    }
}
