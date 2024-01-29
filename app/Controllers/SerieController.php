<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\SerieModel;
use App\Controllers\History;
include('History/HistorySession.php');

class SerieController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('serie/save.php');
    }
    
    public function liste(){
        return view('serie/list.php');
    }

    public function insertserie()
    {
        // validation du formulaire 
        $rules = [
            'name_serie'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_serie'          => [
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
            $name_serie     = $this->request->getvar('name_serie');
            $number_serie   = $this->request->getvar('number_serie');
            $name_school    = $this->request->getvar('name_school');
            
            $user_id        = $this->request->getvar('user_id');
            $SerieModel     = new SerieModel();
            $data_serie     = $SerieModel->getSerie($name_serie, $number_serie, $name_school);


             // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            if (sizeof($data_serie) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'La serie existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Serie", "", "", "La serie existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'code_serie'    => strtolower($number_serie),
                    'name_serie'    => strtolower($name_serie),
                    'id_user'       => $user_id,
                    'school_id'     => $name_school,
                    'status_serie'  => 0,
                    'etat_serie'    => 'actif',
                    'created_at'    => date("Y-m-d H:m:s"),
                    'updated_at'    => date("Y-m-d H:m:s"),
                ];
                if ($SerieModel->save($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'insertion reussir',
                    ];
                    // history
                    $donnee = $data["code_serie"].",".$data["name_serie"].",".$data["id_user"].",".$data["school_id"].",".$data["id_user"].",".$data["status_serie"].",".$data["etat_serie"].",".$data["created_at"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Serie", "", "", $donnee);
                    return $this->respond($response);

                }
                else{
                    // echec d'insertion
                    
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'Echec insertion',
                    ];
                   // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Serie", "", "", "Echec insertion");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Serie", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }

    // modification des series

    public function updateserie()
    {
        // validation du formulaire 
        $rules = [
            'name_serie'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'number_serie'          => [
                'rules'     => 'required'
            ],
            'name_school'           => [
                'rules'     => 'required|max_length[35]'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'serie_id'               => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_serie     = $this->request->getvar('name_serie');
            $number_serie   = $this->request->getvar('number_serie');
            $name_school    = $this->request->getvar('name_school');
            
            $user_id        = $this->request->getvar('user_id');
            $serie_id        = $this->request->getvar('serie_id');
            $SerieModel     = new SerieModel();
            $data_session   = $SerieModel->getSerie($name_serie, $number_serie, $name_school);


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
                    "msg"     => 'La serie existe déjà',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Serie", "", "", "La serie existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'code_serie'    => strtolower($number_serie),
                    'name_serie'    => strtolower($name_serie),
                    'id_user'       => $user_id,
                    'school_id'     => $name_school,
                    'updated_at'    => date("Y-m-d H:m:s"),
                ];
                if ($SerieModel->where('serie_id', $serie_id)->set($data)->update() !== false) {

                    // modification reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'modification reussir',
                    ];
                    // history
                    $donnee = $data["code_serie"].",".$data["name_serie"].",".$data["id_user"].",".$data["school_id"].",".$data["id_user"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Serie", "", "", $donnee);
                    return $this->respond($response);

                }
                else{
                    // echec de modification
                    
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     =>'Echec de modification',
                    ];
                   // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Serie", "", "", "Echec de modification");
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Serie", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }

     #@-- 3 --> supprimer des series
    #- use:
    #-
    public function deleteserie($serie_id){

        $SerieModel = new SerieModel(); 
        $data = $SerieModel->getOneSerie($serie_id);

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
                'msg'     => "Cette serie n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Serie", "", "", "Cette serie n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_serie'    => 1,
          'etat_serie'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($SerieModel->where('serie_id', $serie_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Serie", "", "", "echec Suppression");
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
                $donnee = $data['status_serie'].",".$data['etat_serie']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Serie", "", "", $donnee);
                return $this->respond($response);
            
            }   
                    
        }
    }

    public function allSerie($id_school){
        $SerieModel  = new SerieModel();
        $data        = $SerieModel->getAllSerie($id_school);
        return $this->respond($data);
    }

}

