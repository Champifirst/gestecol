<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\SequenceModel;
use App\Models\DocumentModel;
use App\Models\ClassModel;
use App\Models\CycleModel;
use App\Models\TrimestreModel;
use App\Models\SchoolModel;
use App\Models\SessionModel;

use App\Controllers\History;
include('History/HistorySession.php');

class SequenceController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('sequence/save.php');
    }
    
    public function liste(){
        return view('sequence/list.php');
    }

    public function insertsequence()
    {
        $SequenceModel = new SequenceModel;

        // validation du formulaire 
        $rules = [
            'number_sequence'   => [
                'rules'             => 'required|max_length[20]'
            ],
            'name_sequence'     => [
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
            'name_trimestre'    => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $number_sequence    = $this->request->getvar('number_sequence');
            $name_sequence      = $this->request->getvar('name_sequence');
            $name_cycle         = $this->request->getvar('name_cycle');
            $name_classe        = $this->request->getvar('name_classe');
            $name_session       = $this->request->getvar('name_session');
            $name_school        = $this->request->getvar('name_school');
            $name_trimestre     = $this->request->getvar('name_trimestre');
            $user_id            = $this->request->getvar('user_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];
            //-- verifier si la sequence existe dans la base de donnnees
            $data_sequence = $SequenceModel->getSequence($name_sequence, $number_sequence, $name_trimestre, $name_cycle, $name_classe, $name_session, $name_school);

            if (sizeof($data_sequence) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette séquence existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Séquence", "", "", "Cette séquence existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name'            => $name_sequence,
                    'coded'           => $number_sequence,
                    'id_user'         => $user_id,
                    'session_id'      => $name_session,
                    'cycle_id'        => $name_cycle,
                    'class_id'        => $name_classe,
                    'school_id'       => $name_school,
                    'trimestre_id'    => $name_trimestre,
                    'status_sequence' => 0,
                    'etat_sequence'   => 'actif',
                    'created_at'      => date("Y-m-d H:m:s"),
                    'updated_at'      => date("Y-m-d H:m:s")
                ];
                if ($SequenceModel->insertsequence($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'Insertion réussir',
                    ];
                    // history
                    $donnee = $data["name"].$data["coded"].$data["id_user"].$data["session_id"].$data["cycle_id"].$data["class_id"].$data["school_id"].$data["trimestre_id"].$data["status_sequence"].$data["etat_sequence"].$data["created_at"].$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Séquence", "", "", $donnee);
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
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Séquence", "", "", "Echec d'insertion");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Séquence", "", "", "Echec de validation ");
            return $this->respond($response);
        }
    }

    public function allsequence($id_school){
        $SequenceModel   = new SequenceModel();
        $ClassModel      = new ClassModel();
        $CycleModel      = new CycleModel();
        $TrimestreModel  = new TrimestreModel();
        $SchoolModel     = new SchoolModel();
        $SessionModel    = new SessionModel();
        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];

        $sequence = array();

        if ($id_school == 0) {
            $sequence = $SequenceModel->getAllSequence();
        }else {
            $sequence = $SequenceModel->getSequenceBySchool($id_school);
        }
        $data_final = array();
        foreach ($sequence as $row) {
            $school = $SchoolModel->getIDSchool($row['school_id']);
            $session = $SessionModel->getSessionById($row['session_id']);
            $cycle = $CycleModel->getCycleById($row['cycle_id']);
            $class = $ClassModel->getClassById($row['class_id']);
            $trimestre = $TrimestreModel->getTrimestreById($row['trimestre_id']);

            if (sizeof($school) != 0 || sizeof($session) != 0 || sizeof($cycle) != 0 || sizeof($class) != 0 || sizeof($trimestre) != 0) {
                $data_final[] = [
                    "school"        =>  $school[0]['name'],
                    "session"       =>  $session[0]['name_session'],
                    "cycle"         =>  $cycle[0]['name_cycle'],
                    "class"         =>  $ClassModel->format_name_class($class[0]['name']),
                    "trimestre"     =>  $trimestre[0]['name'],
                    "sequence_id"   =>  $row['sequence_id'],
                    "coded"         =>  $row['coded'],
                    "name"          =>  $row['name'],
                ];
            }
        }

        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "affichage", "Réussite", "Séquence", "", "", "Opération réussir");
        return $this->respond($data_final);
    }

    public function allFiltersequence( $name_school  ,$name_session  ,$name_cycle  ,$name_classe, $name_trimestre ){
        $SequenceModel = new SequenceModel();
        $sequence = $SequenceModel->getSequenceBySchoolSessionCycleClasseTrim($name_school  ,$name_session  ,$name_cycle  ,$name_classe, $name_trimestre);

        if (sizeof($sequence) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "data"    => $sequence,
                "msg"     => 'Auccune séquence trouver',
            ];
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "data"    => $sequence,
                "msg"     => 'Opération réussir',
            ];
            return $this->respond($response);
        }
    }

    #@-- 2 --> modifications des sequenceS
    #- use:
    #-

    public function updatesequence()
    {
    //extanciation de la sequenceModel
        $SequenceModel = new SequenceModel;

        // validation du formulaire 
        $rules = [
            'name_sequence'           => [
                'rules' => 'required|max_length[15]'
            ],
            'number_sequence'          => [
                'rules' => 'required|max_length[15]'
            ],
            'name_trimestre'          => [
                'rules' => 'required|max_length[15]'
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
            ],
            'sequence_id'           => [
                'rules'             => 'required'
            ],
        ];

        if ($this->validate($rules)) {
           //validation good
            $name_sequence      = $this->request->getvar('name_sequence');
            $number_sequence    = $this->request->getvar('number_sequence');
            $name_trimestre     = $this->request->getvar('name_trimestre');
            $name_cycle         = $this->request->getvar('name_cycle');
            $name_classe        = $this->request->getvar('name_classe');
            $name_session       = $this->request->getvar('name_session');
            $name_school        = $this->request->getvar('name_school');
            $user_id            = $this->request->getvar('user_id');
            $sequence_id        = $this->request->getvar('sequence_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];
            
            
            //-- verifier si la sequence existe dans la base de donnnees

            $data_seq = $SequenceModel->getSequence($name_sequence, $number_sequence, $name_trimestre,$name_cycle, $name_classe,$name_session,$name_school);

            if (sizeof($data_seq) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'sequence existe deja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Sequence", "", "", "La sequence existe déjà");
                return $this->respond($response);
            } 
            else {
                $data = [
                    'name'              => $name_sequence,
                    'coded'             => $number_sequence,
                    'trimestre_id'      => $name_trimestre,
                    'id_user'           => $user_id,
                    'session_id'        => $name_session,
                    'cycle_id'          => $name_cycle,
                    'class_id'          => $name_classe,
                    'school_id'         => $name_school,
                    'updated_at'        => date("Y-m-d H:m:s")
                ];

                if ($SequenceModel->where('sequence_id', $sequence_id)->set($data)->update() === false) {
                    // echec de modification
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de modification",
                        ];
                    // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Sequence", "", "", "Echec de modification");
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
                    $donnee = $data["name"].",".$data["coded"].",".$data["trimestre_id"].",".$data["id_user"].",".$data["session_id"].",".$data["cycle_id"].",".$data["class_id"].",".$data["school_id"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Sequence", "", "", $donnee);
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
                "Error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec de validation"
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Sequence", "", "", "Echec de validation, les données sont incorrectes ");
            return $this->respond($response);
        }
    }


    
    #@-- 3 --> supprimer des sequences
    #- use:
    #-
    public function deletesequence($sequence_id){

        $SequenceModel = new SequenceModel(); 
        $data = $SequenceModel->getOneSequence($sequence_id);

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
                'msg'     => "Cette sequence n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Sequence", "", "", "Cette sequence n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_sequence'    => 1,
          'etat_sequence'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($SequenceModel->where('sequence_id', $sequence_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Sequence", "", "", "echec Suppression");
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
                $donnee = $data['status_sequence'].",".$data['etat_sequence']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Sequence", "", "", $donnee);
                return $this->respond($response);
            
            }   
                    
        }
    }

}