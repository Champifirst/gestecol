<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\DocumentModel;
use App\Controllers\History;
include('History/HistorySession.php');


class DocumentController extends ResourcePresenter
{

    use ResponseTrait;
    public function save(){
        return view('session/save.php');
    }
    
    public function liste(){
        return view('session/list.php');
    }
    #@-- 1 --> insertion des documents
    #- use:
    #-
      public function insertdocument()
    {

        // extenciation de la classe DocumentModel
        $DocumentModel = new DocumentModel();

        // validation du formulaire 
        $rules = [
            'name_document'           => [
                'rules' => 'required|max_length[35]'
            ],
            'type_document'          => [
                'rules' => 'required'
            ],
            'name_school'       => [
                'rules' => 'required|max_length[35]'
            ],
           'user_id'       => [
                'rules' => 'required|max_length[35]'
            ],
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_document            = $this->request->getvar('name_document');
            $type_document            = $this->request->getvar('type_document');
            $name_school              = $this->request->getvar('name_school');
            $user_id                 = $this->request->getvar('user_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- verifier si le document existe dans la base de donnnees

            $data_doc = $DocumentModel->getDocument($name_document, $type_document,$name_school);

            if (sizeof($data_doc) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'document existe deja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Document", "", "", "Ce document existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name'              => $mame_document,
                    'type_document'     => $type_document,
                    'school_id'         => $name_school,
                    'id_user'           => $id_user,
                    'status_document'   => 0,
                    'etat_document'     => 'actif',
                    'created_at'        => date("Y-m-d H:m:s"),
                    'updated_at'        => date("Y-m-d H:m:s"),
                ];
                if ($DocumentModel->insertdocument($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'insertion reussir',
                    ];
                    // history
                    $donnee = $data["name"].",".$data["type_document"].",".$data["school_id"].",".$data["id_user"].",".$data["status_document"].",".$data["etat_document"].",".$data["created_at"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Document", "", "", $donnee);
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
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Document", "", "", "Echec d'insertion");
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
                "Error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec d'insertion"
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Document", "", "", "Echec d'insertion, les données sont incorrectes ");
            return $this->respond($response);
        } 

    }


    #@-- 2 --> modifications des documents
    #- use:
    #-

    public function updatedocument()
    {
        // extenciation de la classe DocumentModel
        $DocumentModel = new DocumentModel();

        // validation du formulaire 
        $rules = [
            'name_document'           => [
                'rules' => 'required|max_length[35]'
            ],
            'type_document'          => [
                'rules' => 'required'
            ],
            'name_school'       => [
                'rules' => 'required|max_length[35]'
            ],
           'user_id'       => [
                'rules' => 'required|max_length[35]'
            ],
            'document_id'       => [
                'rules' => 'required|max_length[35]'
            ],
        ];

        if ($this->validate($rules)) {
            //validation good
            $name_document            = $this->request->getvar('name_document');
            $type_document            = $this->request->getvar('type_document');
            $name_school              = $this->request->getvar('name_school');
            $user_id                 = $this->request->getvar('user_id');
            $document_id             = $this->request->getvar('document_id');


            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- verifier si le document existe dans la base de donnnees

            $data_doc = $DocumentModel->getDocument($name_document, $type_document,$name_school);

            if (sizeof($data_doc) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'document existe deja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Document", "", "", "Ce document existe déjà");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name'              => $mame_document,
                    'type_document'     => $type_document,
                    'school_id'         => $name_school,
                    'id_user'           => $id_user,
                    'updated_at'        => date("Y-m-d H:m:s"),
        
                ];
                if ($DocumentModel->where('document_id', $document_id)->set($data)->update() === false) {
                    // echec de modification
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de modification",
                        ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Document", "", "", "Echec de modification");
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
                    // history
                    $donnee = $data["name"].",".$data["type_document"].",".$data["school_id"].",".$data["id_user"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Document", "", "", $donnee);
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Document", "", "", "Echec de validation, les données sont incorrectes ");
            return $this->respond($response);
        }
    }


    #@-- 3 --> supprimer des documents
    #- use:
    #-
    public function deletedocument($document_id){

        $DocumentModel = new DocumentModel(); 
        $data = $DocumentModel->getOneDocument($document_id);

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
                'msg'     => "Ce document n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Document", "", "", "Ce document n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_document'    => 1,
          'etat_document'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($DocumentModel->where('document_id', $document_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Document", "", "", "echec Suppression");
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
                $donnee = $data['status_document'].",".$data['etat_document']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Document", "", "", $donnee);
                return $this->respond($response);
            
            }   
                    
        }
    }

    public function allDocument($id_school){
        $DocumentModel  = new DocumentModel();
        $data        = $DocumentModel->getAllDocument($id_school);
        return $this->respond($data);
    }
}