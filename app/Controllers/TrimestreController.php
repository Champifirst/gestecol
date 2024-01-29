<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\TrimestreModel;
use App\Models\ClassModel;
use App\Models\CycleModel;
use App\Models\SchoolModel;
use App\Models\SessionModel;
use App\Models\DocumentModel;
use App\Controllers\History;
include('History/HistorySession.php');

class TrimestreController extends ResourcePresenter
{

    use ResponseTrait;

    public function save(){
        return view('trimestre/save.php');
    }
    
    public function liste(){
        return view('trimestre/list.php');
    }

    #@-- 1 --> insertion des trimestres
    #- use:
    #-
    public function inserttrimestre()
    {
    //extanciation de la TrimestreModel
        $TrimestreModel = new TrimestreModel;

        // validation du formulaire 
        $rules = [
            'number_trimestre'  => [
                'rules'             => 'required|max_length[20]'
            ],
            'name_trimestre'    => [
                'rules'             => 'required|max_length[20]'
            ],
            'name_cycle'        => [
                'rules'             => 'required'
            ],
            'name_classe'       => [
                'rules'             => 'required'
            ],
            'name_session'      => [
                'rules'             => 'required'
            ],
            'name_school'       => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $number_trimestre   = $this->request->getvar('number_trimestre');
            $name_trimestre     = $this->request->getvar('name_trimestre');
            $name_cycle         = $this->request->getvar('name_cycle');
            $name_classe        = $this->request->getvar('name_classe');
            $name_session       = $this->request->getvar('name_session');
            $name_school        = $this->request->getvar('name_school');
            $user_id            = $this->request->getvar('user_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- verifier si le trimestre existe dans la base de donnnees
            $data_trim = $TrimestreModel->getTrimestre($name_trimestre, $number_trimestre,$name_cycle, $name_classe,$name_session,$name_school);
            
            if (sizeof($data_trim) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Ce trimestre existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Trimestre", "", "", "Ce trimestre existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name'              => $name_trimestre,
                    'coded'             => $number_trimestre,
                    'status_trimestre'  => 0,
                    'etat_trimestre'    => 'actif',
                    'id_user'           => $user_id,
                    'session_id'        => $name_session,
                    'cycle_id'          => $name_cycle,
                    'class_id'          => $name_classe,
                    'school_id'         => $name_school,
                    'created_at'        => date("Y-m-d H:m:s"),
                    'updated_at'        => date("Y-m-d H:m:s")
                ];
                
                if ($TrimestreModel->inserttrimestre($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'Insertion réussir',
                    ];
                    // history
                    $donnee = $data["name"].",".$data["coded"].",".$data["status_trimestre"].",".$data["etat_trimestre"].",".$data["id_user"].",".$data["session_id"].",".$data["cycle_id"].",".$data["class_id"].",".$data["school_id"].",".$data["created_at"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Trimestre", "", "", $donnee);
                    return $this->respond($response);
                }
                else{
                    // echec d'insertion
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'Echec d\'insertion',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Trimestre", "", "", "Echec d'insertion");
                    return $this->respond($response);
                }
            }
        }
        else {
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'erreur'  => $this->validator->getErrors(),
                'msg'     =>'Echec d\'insertion les données sont incorrectes',
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Trimestre", "", "", "Echec d'insertion, les données sont incorrectes ");
            return $this->respond($response);
        }
    }

    public function alltrimestreFilter( $name_school  ,$name_session  ,$name_cycle  ,$name_classe ){
        $TrimestreModel = new TrimestreModel();
        $trimestre = $TrimestreModel->getTrimestreBySchoolSessionCycleName($name_school  ,$name_session  ,$name_cycle  ,$name_classe);

        if (sizeof($trimestre) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "data"    => $trimestre,
                "msg"     => 'Auccun trimestre trouver',
            ];
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "data"    => $trimestre,
                "msg"     => 'Opération réussir',
            ];
            // history
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];
            
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Trimestre", "", "", "Echec de validation ");
            return $this->respond($response);
        }
    }

    public function alltrimestre($id_school){
        $TrimestreModel = new TrimestreModel();
        $ClassModel      = new ClassModel();
        $CycleModel      = new CycleModel();
        $SchoolModel     = new SchoolModel();
        $SessionModel    = new SessionModel();
        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];
        $trimestre = array();

        if ($id_school == 0) {
            $trimestre = $TrimestreModel->getAllTrimestre();
        }else {
            $trimestre = $TrimestreModel->getTrimestreBySchool($id_school);
        }

        $data_final = array();
        foreach ($trimestre as $row) {
            $school = $SchoolModel->getIDSchool($row['school_id']);
            $session = $SessionModel->getSessionById($row['session_id']);
            $cycle = $CycleModel->getCycleById($row['cycle_id']);
            $class = $ClassModel->getClassById($row['class_id']);

            if (sizeof($school) != 0 || sizeof($session) != 0 || sizeof($cycle) != 0 || sizeof($class) != 0) {
                $data_final[] = [
                    "school"        =>  $school[0]['name'],
                    "session"       =>  $session[0]['name_session'],
                    "cycle"         =>  $cycle[0]['name_cycle'],
                    "class"         =>  $ClassModel->format_name_class($class[0]['name']),
                    "trimestre_id"  =>  $row['trimestre_id'],
                    "coded"         =>  $row['coded'],
                    "name"          =>  $row['name'],
                ];
            }
        }

        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "affichage", "Réussite", "Trimestre", "", "", "Opération réussir");
        return $this->respond($data_final);
    }

   
    #@-- 3 --> supprimer des trimestres
   
    public function deletetrimestre($id_trimestre){

        $TrimestreModel = new TrimestreModel(); 
        $data = $TrimestreModel->getTrimestreById($id_trimestre);

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
                'msg'     => "Ce trimestre n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Trimestre", "", "", "Ce trimestre n'existe pas");
            return $this->respond($response);
        }else{ 
  
            $data = [
            'status_trimestre'   => 1,
            'etat_trimestre'     => 'inactif',
            'deleted_at'         => date("Y-m-d H:m:s"),
            ];
            if ($TrimestreModel->where('trimestre_id', $id_trimestre)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Trimestre", "", "", "echec Suppression");
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
                $donnee = $data['status_trimestre'].",".$data['etat_trimestre']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Trimestre", "", "", $donnee);
                return $this->respond($response);
            
            }   
                    
        }
    }
}
