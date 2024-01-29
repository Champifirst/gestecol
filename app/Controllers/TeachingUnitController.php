<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\TeachingUnitModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\CycleModel;
use App\Models\YearModel;

class TeachingUnitController extends ResourcePresenter
{

    use ResponseTrait;

    public function save(){
        return view('teachingUnit/save.php');
    }
    
    public function liste(){
        return view('teachingUnit/list.php');
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
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Matiere", "", "", "ces  matiere existent deja");
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


    #@-- 1 --> modification des matieres
    #- use:
    #-

    public function updateteaching()
    {
       $TeachingUnitModel = new TeachingUnitModel();

        /// validation du formulaire 
        $rules = [
            'name'             => [
                'rules' => 'required|max_length[50]'
            ],
            'code'      => [
                'rules' => 'required|max_length[15]'
            ],
            'coefficient'      => [
                'rules' => 'required|max_length[2]'
            ],
            'school'      => [
                'rules' => 'required'
            ],
            'year'      => [
                'rules' => 'required'
            ],
            'cycle'      => [
                'rules' => 'required'
            ],
            'session'      => [
                'rules' => 'required'
            ],
            'classe'      => [
                'rules' => 'required'
            ],
            'user_id'      => [
                'rules' => 'required'
            ],
            'teachingunit_id'      => [
                'rules' => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            $name                   = $this->request->getvar('name');
            $code                   = $this->request->getvar('code');
            $coefficient            = $this->request->getvar('coefficient');
            $coefficient            = $this->request->getvar('coefficient');
            $school                 = $this->request->getvar('school');
            $year                   = $this->request->getvar('year');
            $cycle                  = $this->request->getvar('cycle');
            $session                = $this->request->getvar('session');
            $classe                 = $this->request->getvar('classe');
            $user_id                = $this->request->getvar('user_id');
            $teachingunit_id        = $this->request->getvar('teachingunit_id');
            
            
            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- if teaching unit exists
            $data_teaching = $TeachingUnitModel->getTeaching($name,$code,$coefficient,$school,$year,$cycle, $session, $classe);

            if (sizeof($data_teaching) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La matiere existe deja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "La matiere existe déjà");
                return $this->respond($response);
            }
            else {

                    $data = [
                    'name'                   => $name,
                    'code'                   => $code,
                    'coefficient'            => $coefficient,
                    'school_id'              => $school_id,
                    'year_id'                => $year_id,
                    'id_user'                => $user_id,
                    'updated_at'             => date("Y-m-d H:m:s"),
                    ];

                if ($TeachingUnitModel->where('teachingunit_id', $teachingunit_id)->set($data)->update() !== false) {
                        
                    // success modified
                    $response = [
                        'success' => true,
                        'status'  => 200,
                        "code"    => "success",
                        "title"   => "Réussite",
                        'msg'     => 'modification reussir',
                    ];
                    // history
                    $donnee = $data["name"].",".$data["code"].",".$data["coefficient"].",".$data["school_id"].",".$data["year_id"].",".$data["id_user"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Matiere", "", "", $donnee);
                    return $this->respond($response);
                    }
                else{
                    // failed modified
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => 'echec de modification',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Matiere", "", "", "Echec de modification");
                    return $this->respond($response);
                }
                
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
   
    public function deleteteaching($id_teachingunit){

        $TeachingUnitModel = new TeachingUnitModel(); 
        $data = $TeachingUnitModel->getTeachingById($id_teachingunit);

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
                'msg'     => "Cette matiere n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Matiere", "", "", "Cette matiere n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_teachingunit'    => 1,
          'etat_teachingunit'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($TeachingUnitModel->where('teachingunit_id', $teachingunit_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
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
                    'msg'     => "Suppression reussir",
                ];
                $donnee = $data['status_teachingunit'].",".$data['etat_teachingunit']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Matiere", "", "", $donnee);
                return $this->respond($response);
            
            }   
        }            
     
    }


    public function allteaching($id_school, $id_session, $id_cycle, $id_class){

        $TeachingUnitModel   = new TeachingUnitModel();
        $ClassModel   = new ClassModel();
        $SessionModel = new SessionModel();
        $CycleModel   = new CycleModel();
        $YearModel    = new YearModel();
        $SchoolModel  = new SchoolModel();

        $school       = $SchoolModel->findAllSchoolByidSchool($id_school);
        $session      = $SessionModel->getSessionById($id_session);
        $cycle        = $CycleModel->getCycleById($id_cycle);
        $class        = $ClassModel->getOneClass($id_class);

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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette ecole n'existe pas");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "cette session n'existe pas");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Matiere", "", "", "ce cycle n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette classe n\'existe pas',
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
        foreach ($data_class as $row) {
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
    }

