<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\SessionModel;
use App\Controllers\History;
include('History/HistorySession.php');

class SessionController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('session/save.php');
    }
    
    public function liste(){
        return view('session/list.php');
    }

    public function insertsession()
    {
        // validation du formulaire 
        $rules = [
            'name_session'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_session'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_session     = $this->request->getvar('name_session');
            $number_session   = $this->request->getvar('number_session');
            $name_school      = $this->request->getvar('name_school');
            
            $user_id          = $this->request->getvar('user_id');    
            $SessionModel     = new SessionModel();
            $data_session_    = $SessionModel->getSession($name_session, $number_session, $name_school);

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            if (sizeof($data_session_) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La session existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Session", "", "", "La session existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'code_session'  => strtolower($number_session),
                    'name_session'  => strtolower($name_session),
                    'id_user'       => $user_id,
                    'school_id'     => $name_school,
                    'status_session'=> 0,
                    'etat_session'  => 'actif',
                    'created_at'    => date("Y-m-d H:m:s"),
                    'updated_at'    => date("Y-m-d H:m:s"),
                ];
                if ($SessionModel->save($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'Insertion reussir',
                    ];
                    // history
                    $donnee = $data["code_session"].",".$data["name_session"].",".$data["id_user"].",".$data["school_id"].",".$data["id_user"].",".$data["status_session"].",".$data["etat_session"].",".$data["created_at"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Session", "", "", $donnee);
                    return $this->respond($response);

                }
                else{
                    // echec d'insertion
                    
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'Echec d\'nsertion',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Session", "", "", "Echec insertion");
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
                'msg'     => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Session", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }

    //modification

     public function updatesession()
    {
        // validation du formulaire 
        $rules = [
            'name_session'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_session'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'session_id'               => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_session     = $this->request->getvar('name_session');
            $number_session   = $this->request->getvar('number_session');
            $name_school      = $this->request->getvar('name_school');
            
            $user_id        = $this->request->getvar('user_id');    
            $session_id      = $this->request->getvar('session_id');
            $SessionModel     = new SessionModel();
            $data_session     = $SessionModel->getSession($name_session, $number_session, $name_school);

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            if (sizeof($data_session) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La session existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Session", "", "", "La session existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'code_session'  => strtolower($number_session),
                    'name_session'  => strtolower($name_session),
                    'id_user'       => $user_id,
                    'school_id'     => $name_school,
                    'updated_at'    => date("Y-m-d H:m:s"),
                ];
                if ($SessionModel->where('session_id', $session_id)->set($data)->update() !== false) {

                    // modification reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'modification reussir',
                    ];
                    // history
                    $donnee = $data["code_session"].",".$data["name_session"].",".$data["id_user"].",".$data["school_id"].",".$data["id_user"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Session", "", "", $donnee);
                    return $this->respond($response);

                }
                else{
                    // echec de modification
                    
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'echec de modification',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Session", "", "", "Echec de modification");
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
                "Error"     => $this->validator->getErrors(),
                "msg"   => "Echec de validation",
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Session", "", "", "Echec de validation, les champs sont incorrects ");
            return $this->respond($response); 
        }
    }

    #@-- 3 --> supprimer des series
    #- use:
    #-
    public function deletesession($id_session){

        $SessionModel = new SessionModel(); 
        $data = $SessionModel->getSessionById($id_session);

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
                'msg'     => "Cette session n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Session", "", "", "Cette session n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_session'    => 1,
          'etat_session'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($SessionModel->where('session_id', $session_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Session", "", "", "echec Suppression");
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
                $donnee = $data['status_session'].",".$data['etat_session']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Session", "", "", $donnee);
                return $this->respond($response);
            
            }   
                    
        }
    }

    public function allSession($id_school){
        $SessionModel  = new SessionModel();
        $data          = $SessionModel->getAllSession($id_school);
        return $this->respond($data);
    }

}
