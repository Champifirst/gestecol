<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\TeachingUnitModel;
use App\Models\TeacherClassModel;
use App\Models\TeacherUnitClassModel;
use App\Models\TeacherModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\CycleModel;
use App\Models\YearModel;
use App\Controllers\History;
include('History/HistorySession.php');
class TeachingUnitController extends ResourcePresenter
{

    use ResponseTrait;

    public function save(){
        return view('teachingUnit/save.php');
    }
    
    public function liste(){
        return view('teachingUnit/list.php');
    }

    public function GetOne($id_teachingunit){
        $TeachingUnitModel = new TeachingUnitModel();
        $data = $TeachingUnitModel->getTeachingById($id_teachingunit);
        if (sizeof($data) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette matière n\'existe pas',
                "data"    => $data,
            ];
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Opération réussir',
                "data"    => $data[0],
            ];
            return $this->respond($response);
        }
    }

    public function allTeachingUnit($id_class){
        $TeachingUnitModel = new TeachingUnitModel(); 
        $ClassModel = new ClassModel();
        $YearModel = new YearModel();

        $class = $ClassModel->getIDClass($id_class);

        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette classe n\'existe pas',
            ];
            return $this->respond($response);
        }

        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]['year_id'];

        $teaching_unit = $TeachingUnitModel->getByIDClassByYear($id_class, $year_id);
        // var_dump($teaching_unit);
        return $this->respond($teaching_unit);
    }

    public function allTeachingUnitByTeacher($id_class, $id_teacher){
        $TeacherUnitClassModel = new TeacherUnitClassModel();
        $TeachingUnitModel = new TeachingUnitModel(); 
        $TeacherModel = new TeacherModel();
        $ClassModel = new ClassModel();
        $YearModel = new YearModel();

        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]['year_id'];

        $class = $ClassModel->getIDClass($id_class);
        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette classe n\'existe pas',
            ];
            return $this->respond($response);
        }

        $teaching_unit = $TeacherUnitClassModel->getSubjectsByTeacherClassAndYear($id_teacher, $id_class, $year_id);
        // var_dump($teaching_unit);
        return $this->respond($teaching_unit);
    }

    #@-- 1 --> insertion des matieres
    #- use:
    #-
    public function insertteaching()
    {
        $TeachingUnitModel = new TeachingUnitModel();
        $SchoolModel       = new SchoolModel();
        $ClassModel        = new ClassModel();
        $SessionModel      = new SessionModel();
        $CycleModel        = new CycleModel();
        $YearModel         = new YearModel();

        // validation du formulaire 
        $rules = [
            'name_school'   => [
                'rules'         => 'required'
            ],
            'name_session'  => [
                'rules'         => 'required'
            ],
            'name_cycle'    => [
                'rules'         => 'required'
            ],
            'name_classe'   => [
                'rules'         => 'required'
            ],
            'code'          => [
                'rules'         => 'required'
            ],
            'matiere'       => [
                'rules'         => 'required'
            ],
            'coefficient'   => [
                'rules'         => 'required'
            ],
            'user_id'       => [
                'rules'         => 'required'
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
            $name_school     = $this->request->getvar('name_school');
            $name_session    = $this->request->getvar('name_session');
            $name_cycle      = $this->request->getvar('name_cycle');
            $name_classe     = $this->request->getvar('name_classe');
            $code            = $this->request->getvar('code');
            $matiere         = $this->request->getvar('matiere');
            $coefficient     = $this->request->getvar('coefficient');
            $user_id         = $this->request->getvar('user_id');
            $data_school     = $SchoolModel->findAllSchoolByidSchool($name_school);
            $data_session    = $SessionModel->getSessionById($name_session);
            $data_cycle      = $CycleModel->getCycleById($name_cycle);
            $data_classe     = $ClassModel->getClassById($name_classe);

            if (sizeof($data_school) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette école n\'existe pas',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Matière", "", "", "ces  matière existent déjà");
                return $this->respond($response);
            }
            if (sizeof($data_session) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette session n\'existe pas',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Matière", "", "", "cette session n'existe pas");
                return $this->respond($response);
            }
            if (sizeof($data_cycle) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Ce cycle n\'existe pas',
                ];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Matière", "", "", "ce cycle n'existe pas");
                return $this->respond($response);
            }
            if (sizeof($data_classe) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette classe n\'existe pas',
                ];
                return $this->respond($response);
            }

            $yearActif = $YearModel->getYearActif();
            $year_id = $yearActif[0]['year_id'];
            
            for ($i=1; $i < sizeof($code); $i++) { 
                //-- if teaching unit exists
                $data_teaching = $TeachingUnitModel->getTeaching($code[$i], $matiere[$i], $name_classe, $year_id);
                if (sizeof($data_teaching) == 0) {
                   $data = [
                    'name'                  =>  $matiere[$i],
                    'code'                  =>  $code[$i],
                    'coefficient'           =>  $coefficient[$i],
                    'year_id'               =>  $year_id,
                    'user_id'               =>  $user_id,
                    'cycle_id'              =>  $name_cycle,
                    'session_id'            =>  $name_session,
                    'school_id'             =>  $name_school,
                    'class_id'              =>  $name_classe,
                    'status_teachingunit'   =>  0,
                    'etat_teachingunit'     =>  'actif',
                    'created_at'            =>  date("Y-m-d H:m:s"),
                    'updated_at'            =>  date("Y-m-d H:m:s"),
                   ];

                   $TeachingUnitModel->save($data);
                }
            }
            
            $response = [
                'success' => true,
                'status'  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                'msg'     => 'Insertion réussir',
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Matiere", "", "", "Insertion reussir ");
            return $this->respond($response);
            
        }else {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "error"   => $this->validator->getErrors(),
                "msg"     => "Veuillez insérer toute les informations",
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Matiere", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }

    #@-- 1 --> liste des matieres
    #- use:
    #-
    public function allteaching($id_school, $id_session, $id_cycle, $id_class){

        $TeachingUnitModel      = new TeachingUnitModel();
        $ClassModel             = new ClassModel();
        $SessionModel           = new SessionModel();
        $CycleModel             = new CycleModel();
        $YearModel              = new YearModel();
        $SchoolModel            = new SchoolModel();
        $school                 = $SchoolModel->findAllSchoolByidSchool($id_school);
        $session                = $SessionModel->getSessionById($id_session);
        $cycle                  = $CycleModel->getCycleById($id_cycle);
        $class                  = $ClassModel->getOneClass($id_class);
        // session
        $HistorySession         = new HistorySession();
        $data_session           = $HistorySession-> getInfoSession();
        $id_user                = $data_session['id_user'];
        $type_user              = $data_session['type_user'];
        $login                  = $data_session['login'];
        $password               = $data_session['password']; 

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
        if (sizeof($session) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver cette session',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette session n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($cycle) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver ce cycle',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "ce cycle n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver cette classe',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette classe n'existe pas");
            return $this->respond($response);
        }
        $data_teaching = $TeachingUnitModel->getAllTeachingSchoolSessionCycleClass($id_school, $id_session, $id_cycle,$id_class);

        if (sizeof($data_teaching) == 0) {
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération reussir',
                "data"    => array(),
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "Opération reussir");
            return $this->respond($response);
        }
        //-- restaure data
        $data_organize = array();
        foreach ($class as $row) {
            $explode_name = explode("#", $row['name']);
            $new_name = $explode_name[0]." ".$explode_name[1]." ".$explode_name[2];
            $row['name'] = $new_name;
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
        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", $data_organize);
        return $this->respond($response);

    }


    #@-- 1 --> other get all teaching unit classe 
    #- use:
    #-
    public function allteachingOther($id_school, $id_session, $id_cycle, $id_class){
        
        $TeachingUnitModel      = new TeachingUnitModel();
        $TeacherClassModel      = new TeacherClassModel();
        $teacherUnitClassModel  = new TeacherUnitClassModel();
        $TeacherModel           = new TeacherModel();
        $ClassModel             = new ClassModel();
        $SessionModel           = new SessionModel();
        $CycleModel             = new CycleModel();
        $YearModel              = new YearModel();
        $SchoolModel            = new SchoolModel();
        $school                 = $SchoolModel->findAllSchoolByidSchool($id_school);
        $session                = $SessionModel->getSessionById($id_session);
        $cycle                  = $CycleModel->getCycleById($id_cycle);
        $class                  = $ClassModel->getOneClass($id_class);
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
                "msg"     => 'Désoler nous n\'avons pas pu trouver cette école',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette ecole n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($session) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver cette session',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette session n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($cycle) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver ce cycle',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "ce cycle n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Désoler nous n\'qvons pas pu trouver cette classe',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette classe n'existe pas");
            return $this->respond($response);
        }
        
        $data_teaching = $TeachingUnitModel->getAllTeachingSchoolSessionCycleClassOTHER($id_school, $id_session, $id_cycle,$id_class);
        $data_final = array();
        $enseignants = array();
        
        foreach ($data_teaching as $row) {
            $teacher = $teacherUnitClassModel->getTeachersByTeachingUnitClassAndYear($row['teachingunit_id'], $row['class_id'], $year_id);
            // var_dump($teacher);
            $name_teacher = "";
            $id_teacher = "";
            if (sizeof($teacher) != 0) {
                $name_teacher = $teacher[0]["name"]." ".$teacher[0]["surname"];
                $id_teacher = $teacher[0]["teacher_id"];
            }
            $data_final[] = [
                "teachingunit_id"       => $row["teachingunit_id"],
                "code"                  => $row["code"],
                "name"                  => $row["name"],
                "id_enseignant"         => $id_teacher,
                "name_enseignant"       => $name_teacher,
            ];
            // var_dump($data_final);
            $enseignants = $TeacherModel->getAllTeacherBySchool($id_school);

        }

        // echo sizeof($data_teaching) == 0 ;
        if (!empty($data_teaching)) {
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
        //-- restaure data
        $data_organize = array();
        foreach ($class as $row) {
            $explode_name = explode("#", $row['name']);
            $new_name = $explode_name[0]." ".$explode_name[1]." ".$explode_name[2];
            $row['name'] = $new_name;
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
        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", 'Opération reussir');
        return $this->respond($response);

    }

    #@-- 1 --> modification des matieres
    #- use:
    #-
    public function updateTeachingUnit()
    {
       $TeachingUnitModel = new TeachingUnitModel();

        /// validation du formulaire 
        $rules = [
            'name'          => [
                'rules'         => 'required|max_length[50]'
            ],
            'code'          => [
                'rules'         => 'required|max_length[15]'
            ],
            'coefficient'   => [
                'rules'         => 'required|max_length[2]'
            ],
            'school'        => [
                'rules'         => 'required'
            ],
            'cycle'         => [
                'rules'         => 'required'
            ],
            'session'       => [
                'rules'         => 'required'
            ],
            'classe'        => [
                'rules'         => 'required'
            ],
            'user_id'       => [
                'rules'         => 'required'
            ],
            'teachingunit_id'=> [
                'rules'         => 'required'
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
            $name            = $this->request->getvar('name');
            $code            = $this->request->getvar('code');
            $coefficient     = $this->request->getvar('coefficient');
            $school          = $this->request->getvar('school');
            $cycle           = $this->request->getvar('cycle');
            $session         = $this->request->getvar('session');
            $classe          = $this->request->getvar('classe');
            $user_id         = $this->request->getvar('user_id');
            $teachingunit_id = $this->request->getvar('teachingunit_id');

            // select year
            $YearModel  = new YearModel();
            $yearActif  = $YearModel->getYearActif();
            $year       = $yearActif[0]['year_id'];

            //-- if teaching unit exists
            $data_teaching = $TeachingUnitModel->getTeaching($name,$code,$coefficient,$school,$year,$cycle, $session, $classe);

            if (sizeof($data_teaching) > 1) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La matière existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "La matière existe déjà");
                return $this->respond($response);
            }

            if (sizeof($data_teaching) != 0 && sizeof($data_teaching) == 1 ) {
                if ($teachingunit_id != $data_teaching[0]["teachingunit_id"]) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => 'La matière existe déjà',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "La matière existe déjà");
                    return $this->respond($response);
                }
            }
            
            $data = [
                'name'        => $name,
                'code'        => $code,
                'coefficient' => $coefficient,
                'class_id'    => $classe,
                'cycle_id'    => $cycle,
                'session_id'  => $session,
                'school_id'   => $school,
                'year_id'     => $year,
                'id_user'     => $user_id,
                'updated_at'  => date("Y-m-d H:m:s"),
            ];

            if ($TeachingUnitModel->where('teachingunit_id', $teachingunit_id)->set($data)->update() !== false) {
                    
                // success modified
                $response = [
                    'success' => true,
                    'status'  => 200,
                    "code"    => "success",
                    "title"   => "Réussite",
                    'msg'     => 'Modification reussir',
                    'data'    => $data
                ];
                // history
                $donnee = $data["name"].",".$data["code"].",".$data["coefficient"].",".$data["school_id"].",".$data["year_id"].",".$data["id_user"].",".$data["updated_at"];

                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Matiere", "", "", $donnee);
                return $this->respond($response);
            }else{
                // failed modified
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     => 'Echec de modification',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "Echec de modification");
                return $this->respond($response);
            }
            
        }
        else {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }



    #@-- 3 --> supprimer des matieres
   
    public function deleteteachingUnit($id_teachingunit){

        $TeachingUnitModel  = new TeachingUnitModel(); 
        $data               = $TeachingUnitModel->getTeachingById($id_teachingunit);
        // session
        $HistorySession     = new HistorySession();
        $data_session       = $HistorySession-> getInfoSession();
        $id_user            = $data_session['id_user'];
        $type_user          = $data_session['type_user'];
        $login              = $data_session['login'];
        $password           = $data_session['password']; 
  
        if (sizeof($data) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'msg'     => "Cette matière n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Matiere", "", "", "Cette matiere n'existe pas");
            return $this->respond($response);
        }else{ 
  
            $data = [
                'status_teachingunit'  => 1,
                'etat_teachingunit'    => 'inactif',
                'deleted_at'           => date("Y-m-d H:m:s"),
            ];
            if ($TeachingUnitModel->where('teachingunit_id', $id_teachingunit)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success"     => false,
                        "status"    => 500,
                        "code"      => "error",
                        "title"     => "Erreur",
                        'msg'       => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Matiere", "", "", "echec Suppression");
                    return $this->respond($response);
            }else{
                   // suppression reussir
                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    'msg'     => "Suppression réussir",
                ];
                $donnee = $data['status_teachingunit'].",".$data['etat_teachingunit']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Matiere", "", "", $donnee);
                return $this->respond($response);
            }   
        }            
    }

}
    

