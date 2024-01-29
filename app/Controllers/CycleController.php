<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\CycleModel;
use App\Controllers\History;
include('History/HistorySession.php');


class CycleController extends ResourcePresenter
{
    
    use ResponseTrait;

    public function save(){
        return view('cycle/save.php');
    }
    
    public function liste(){
        return view('cycle/list.php');
    }

    #@-- 1 --> insertion des cycles
    #- use:
    #-
    public function insertcycle()
    {
        // validation du formulaire 
        $rules = [
            'name_cycle'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_cycle'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'name_session'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_cycle     = $this->request->getvar('name_cycle');
            $number_cycle   = $this->request->getvar('number_cycle');
            $name_school    = $this->request->getvar('name_school');
            $name_session    = $this->request->getvar('name_session');
            
            $user_id        = $this->request->getvar('user_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            $CycleModel     = new CycleModel();
            $data_cycle     = $CycleModel->getCycle($name_cycle, $number_cycle, $name_school, $name_session);

            if (sizeof($data_cycle) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Le cycle existe déjà',
                ];
                 // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Cycle", "", "", "Ce cycle existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'session_id'  => strtolower($name_session),
                    'code_cycle'  => strtolower($number_cycle),
                    'name_cycle'  => strtolower($name_cycle),
                    'id_user'     => $user_id,
                    'school_id'   => $name_school,
                    'status_cycle'=> 0,
                    'etat_cycle'  => 'actif',
                    'created_at'  => date("Y-m-d H:m:s"),
                    'updated_at'  => date("Y-m-d H:m:s"),
                ];
                if ($CycleModel->save($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'insertion reussir',
                    ];
                   // history
                    $donnee = $data["session_id"].",".$data["code_cycle"].",".$data["name_cycle"].",".$data["id_user"].",".$data["school_id"].",".$data["status_cycle"].",".$data["etat_cycle"].",".$data["created_at"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Cycle", "", "", $donnee);
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
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Cycle", "", "", "Echec d'insertion");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Cycle", "", "", "Echec d'insertion, les données sont incorrectes ");
            return $this->respond($response);
        }
    }

    #@-- 2 --> modifications des cycles
    #- use:
    #-

    public function updatecycle()
    {
        // extenciation de la classe CycleModel
        $CycleModel = new CycleModel();

        // validation des champs
        $rules = [
            'name_cycle'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_cycle'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'name_session'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'cycle_id'               => [
                'rules'     => 'required'
            ]
        ];

         if ($this->validate($rules)) {
            // validation good
            //validation good
            $name_cycle     = $this->request->getvar('name_cycle');
            $number_cycle   = $this->request->getvar('number_cycle');
            $name_school    = $this->request->getvar('name_school');
            $name_session    = $this->request->getvar('name_session');
            
            $user_id        = $this->request->getvar('user_id');

            $cycle_id        = $this->request->getvar('cycle_id');
           

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            # verifier si le cycle existe
            $data_cycle     = $CycleModel->getCycle($name_cycle, $number_cycle, $name_school, $name_session);


            if (sizeof($data_cycle) != 0) {
               
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     =>'ce cycle existe deja',
                ];
               // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Cycle", "", "", "ce cycle existe deja");
                return $this->respond($response);
            } 
            else {
                    $data = [
                    'session_id'  => strtolower($name_session),
                    'code_cycle'  => strtolower($number_cycle),
                    'name_cycle'  => strtolower($name_cycle),
                    'id_user'     => $user_id,
                    'school_id'   => $name_school,
                    'updated_at'  => date("Y-m-d H:m:s"),
                ];

                if ($CycleModel->where('cycle_id', $cycle_id)->set($data)->update() === false) {
                    // echec de modification
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec modification",
                        ];
                    // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Cycle", "", "", "Echec de modification");
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
                   $donnee = $data["session_id"].",".$data["code_cycle"].",".$data["name_cycle"].",".$data["id_user"].",".$data["school_id"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Cycle", "", "", $donnee);

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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Cycle", "", "", "Echec de validation, les champs sont incorrects");
            return $this->respond($response);
        }
    }

    #@-- 3 --> supprimer des cycle
    #- use:
    #-
    public function deletecycle($cycle_id){

        $CycleModel = new CycleModel(); 
        $data = $CycleModel->getCycleById($cycle_id);

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
                'msg'     => "Ce cycle n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Cycle", "", "", "Ce cycle n'existe pas");
            return $this->respond($response);
        }else{
                $tab = [
                    "status_cycle" => 1,
                    "etat_cycle"   =>'inactif',
                    "deleted_at"  => date("Y-m-d H:m:s"),
                ];

            if ($CycleModel->where('cycle_id', $cycle_id)->set($tab)->update() !== false) {

                // suppression reussir
                $response = [
                  "success" => true,
                  "status"  => 200,
                  "code"    => "Success",
                  "title"   => "Réussite",
                  "msg"     => 'Suppression reussir',
                ];

                $donnee = $tab['status_cycle'].",".$tab['etat_cycle']. ",". $tab['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Cycle", "", "", $donnee);
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
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Cycle", "", "", "echec Suppression");
                return $this->respond($response);
                 
            }
        }
    }

    public function allCycle($id_school, $id_session){
        $CycleModel  = new CycleModel();
        $data        = $CycleModel->getAllCycle($id_school, $id_session);
        return $this->respond($data);
    }
}
