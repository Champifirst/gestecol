<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\SchoolModel;
use App\Controllers\History;
use App\Controllers\LicenceController;

class SchoolController extends ResourcePresenter
{
    use ResponseTrait;
    #@-- 1 --> insertion des ecoles
    #- use:
    #-
    // liste des ecoles
    public function school_filter($id_school){
        $SchoolModel = new SchoolModel();
        $data = array();
        if ($id_school == 0) {
            $data = $SchoolModel->findSchoolSearch();
        }else{
            $data = $SchoolModel->findAllSchoolByidSchool($id_school);
        }

        $data_final = array();
        foreach ($data as $row) {
            $data_final[] = array(
                "id"    => $row['school_id'], 
                "text"  => strtoupper($row['name'])." | CODE: ".strtoupper($row['code'])
            );
        }
           
        return $this->respond($data_final);
    }


    public function liste_school($id_school){
        $SchoolModel = new SchoolModel();
        $LicenceController  = new LicenceController();

        $data = array();
        if ($id_school == 0) {
            $data = $SchoolModel->findAllSchool();
            // verifier la licence de toute les ecoles
            for ($i=0; $i < sizeof($data); $i++) { 
                $licence = $LicenceController->verif_licence($data[$i]["school_id"]);
                if (!$licence) {
                    $data[$i]["motif"] = 'error';
                }else{
                    $data[$i]["motif"] = 'success';
                }
            }
        }else{
            $data = $SchoolModel->findAllSchoolByidSchool($id_school);
            // verifier la licence d'une seule ecole
            $licence = $LicenceController->verif_licence($id_school);
            
            if (!$licence) {
                $data[0]["motif"] = 'error';
            }else{
                $data[0]["motif"] = 'success';
            }
        }
        
        return $this->respond($data);
    }

    public function insertschool()
    {
        $SchoolModel = new SchoolModel();

        // validation du formulaire 
        $rules = [
            'name_school'       => [
                'rules'                 => 'required|max_length[35]'
            ],
            'coded_school'      => [
                'rules'                 => 'required|max_length[15]'
            ],
            'color1'            => [
                'rules'                 => 'required'
            ],
            'color2'            => [
                'rules'                 => 'required'
            ],
            'create_at_school'  => [
                'rules'                 => 'required'
            ],
            'responsable'       => [
                'rules'                 => 'required'
            ],
            'email'             => [
                'rules'                 => 'required'
            ],
            'phone1'            => [
                'rules'                 => 'required'
            ],
            'phone2'            => [
                'rules'                 => 'required'
            ],
            'user_id'           => [
                'rules'                 => 'required'
            ],
            'matricule'         => [
                'rules'                 => 'required'
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
            $name_school        = $this->request->getvar('name_school');
            $coded_school       = $this->request->getvar('coded_school');
            $color1             = $this->request->getvar('color1');
            $color2             = $this->request->getvar('color2');
            $create_at_school   = $this->request->getvar('create_at_school');
            $responsable        = $this->request->getvar('responsable');
            $email              = $this->request->getvar('email');
            $phone1             = $this->request->getvar('phone1');
            $phone2             = $this->request->getvar('phone2');
            $user_id            = $this->request->getvar('user_id');
            $logo               = $this->request->getFile('logo');
            $matricule          = $this->request->getvar('matricule');

            //-- if school exists
            $data_school = $SchoolModel->getSchool($name_school, $coded_school,$create_at_school,$responsable,$email);

            if (sizeof($data_school) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'ecole existe deja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Ecole", "", "", "L'ecole existe déjà");
                return $this->respond($response);
            } else {
                /*====================== IMPORT PHOTO ======================*/
                $name_logo = $logo->getName();
                // Renaming file before upload
                $temp_logo = explode(".",$name_logo);
                $new_logo_name = round(microtime(true)) . '.' . end($temp_logo);
                $dbHost = getenv('FILE_LOGO_SCHOOL');
                $verdic = $logo->move($dbHost, $new_logo_name);

                if (!$verdic) {
                    // failed insert
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => 'echec insertion de l\'image ',
                    ];
                    // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Ecole", "", "", "echec d'insertion de l'image");
                return $this->respond($response);
                }else{

                    $data = [
                        'matricule'     => $matricule,
                        'name'          => $name_school,
                        'logo'          => $new_logo_name,
                        'creation_date' => $create_at_school,
                        'couleur'       => $color1.",".$color2,
                        'code'          => $coded_school,
                        'responsable'   => $responsable,
                        'email'         => $email,
                        'phone'         => $phone1.",".$phone2,
                        'id_user'       => $user_id,
                        'status_school' => 0,
                        'etat_school'   => "actif",
                        'created_at'    => date("Y-m-d H:m:s"),
                        'updated_at'    => date("Y-m-d H:m:s")
                    ];

                    if ($SchoolModel->insertschool($data)) {
                        // success insert
                        $response = [
                            'success' => true,
                            'status'  => 200,
                            "code"    => "success",
                            "title"   => "Réussite",
                            'msg'     => 'insertion reussir',
                        ];
                       
                       // history
                    $donnee = $data["name"].",".$data["logo"].",".$data["creation_date"].",".$data["couleur"].",".$data["code"].",".$data["responsable"].",".$data["email"].",".$data["phone"].",".$data["id_user"].",".$data["status_school"].",".$data["etat_school"].",".$data["created_at"].",".$data["updated_at"];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Ecole", "", "", $donnee);
                    return $this->respond($response);

                    }
                    else{
                        // failed insert
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => 'echec insertion',
                        ];
                       $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Ecole", "", "", "echec insertion");
                    return $this->respond($response);
                    }
                }
            }
        }
        else {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "L'opération a échouer",
                "error"   => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Ecole", "", "", "L'opération a échouer ");
            return $this->respond($response);
        } 

    }


    #@-- 2 --> modifications des ecoles
    #- use:
    #-

    public function updateschool()
    {
        
        // extenciation de la classe SchoolModel
        $SchoolModel = new SchoolModel();

        // validation du formulaire 
        $rules = [
            'name_school'       => [
                'rules'                 => 'required|max_length[35]'
            ],
            'coded_school'      => [
                'rules'                 => 'required|max_length[15]'
            ],
            'color1'            => [
                'rules'                 => 'required'
            ],
            'color2'            => [
                'rules'                 => 'required'
            ],
            'create_at_school'  => [
                'rules'                 => 'required'
            ],
            'responsable'       => [
                'rules'                 => 'required'
            ],
            'email'             => [
                'rules'                 => 'required'
            ],
            'phone1'            => [
                'rules'                 => 'required'
            ],
            'phone2'            => [
                'rules'                 => 'required'
            ],
            'user_id'           => [
                'rules'                 => 'required'
            ],
            'school_id'           => [
                'rules'                 => 'required'
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
            $name_school        = $this->request->getvar('name_school');
            $coded_school       = $this->request->getvar('coded_school');
            $color1             = $this->request->getvar('color1');
            $color2             = $this->request->getvar('color2');
            $create_at_school   = $this->request->getvar('create_at_school');
            $responsable        = $this->request->getvar('responsable');
            $email              = $this->request->getvar('email');
            $phone1             = $this->request->getvar('phone1');
            $phone2             = $this->request->getvar('phone2');
            $user_id            = $this->request->getvar('user_id');
            $logo               = $this->request->getFile('logo');
            $school_id          = $this->request->getvar('school_id');
            $color = $color1.",".$color2;
            $phone = $phone1.",".$phone2;

            /*====================== IMPORT PHOTO ======================*/
            $name_logo = $logo->getName();
            // Renaming file before upload
            $temp_logo = explode(".",$name_logo);
            $new_logo_name = round(microtime(true)) . '.' . end($temp_logo);
            $dbHost = getenv('FILE_LOGO_SCHOOL');
            $verdic = $logo->move($dbHost, $new_logo_name);

            if (!$verdic) {
                // failed insert
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     => 'echec insertion de l\'image ',
                ];
                    // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Ecole", "", "", "echec d'insertion de l'image");
                return $this->respond($response);

            }else{
            //-- if school exists
            $data_school = $SchoolModel->getUpdateSchool($name_school, $coded_school,$create_at_school,$responsable,$email, $new_logo_name, $color, $phone);

                if (sizeof($data_school) != 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => 'ecole existe deja',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Ecole", "", "", "L'ecole existe déjà");
                    return $this->respond($response);
                } else {

                        $data = [
                            'name'          => $name_school,
                            'logo'          => $new_logo_name,
                            'creation_date' => $create_at_school,
                            'couleur'       => $color,
                            'code'          => $coded_school,
                            'responsable'   => $responsable,
                            'email'         => $email,
                            'phone'         => $phone,
                            'id_user'       => $user_id,
                            'updated_at'    => date("Y-m-d H:m:s"),
                        ];

                        if ($SchoolModel->where('school_id', $school_id)->set($data)->update() === false) {
                            // echec de modification
                            $response = [
                                "success" => false,
                                "status"  => 500,
                                "code"    => "error",
                                "title"   => "Erreur",
                                'msg'     => 'echec modification ',
                            ];
                            // history
                            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Ecole", "", "", "echec de modification");
                            return $this->respond($response);
                        }else
                            {
                             // modification reussir
                            $response = [
                               "success"  =>true,
                                "status"  => 200,
                                "code"    => "success",
                                "title"   => "Réussite",
                                'msg'     => 'modification reussir',
                            ];
                        // history
                        $donnee = $data["name"].",".$data["logo"].",".$data["creation_date"].",".$data["couleur"].",".$data["code"].",".$data["responsable"].",".$data["email"].",".$data["phone"].",".$data["id_user"].",".$data["updated_at"];
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Ecole", "", "", $donnee);
                        return $this->respond($response);

                        }
                    }

            }
                
        }

        else{
            //echec de modification
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "echec de validation",
                "error"   => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Ecole", "", "", "Echec validation, les champs sont incorrectes ");
            return $this->respond($response);
        }
    }


    #@-- 3 --> supprimer des ecoles
    #- use:
    #-
    public function deleteschool($school_id){

        $SchoolModel = new SchoolModel();  
        $data = $SchoolModel->getIDSchool($school_id);

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
                'msg'     => "Cette ecole n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Ecole", "", "", "Cette ecole n'existe pas");
            return $this->respond($response);
        }else{
        
            $data = [
              'status_school'    => 1,
              'etat_school'      => 'inactif',
              'deleted_at'       => date("Y-m-d H:m:s"),
            ];
            if ($SchoolModel->where('school_id', $school_id)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'echec Suppression',
                ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Ecole", "", "", "echec de suppression");
            return $this->respond($response);
            }else{
                   // suppression reussir
                  $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    "msg"     => 'Suppression reussir',
                ];
                $donnee = $data['status_school'].",".$data['etat_school']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Ecole", "", "", $donnee);
                return $this->respond($response);
            }
        }
    }

    public function selectschool($id_school){
    
    $SchoolModel = new SchoolModel();  
    $school = $SchoolModel->getIDSchool($id_school);

    if (sizeof($school) == 0) {
        $response = [
            "success" => false,
            "status"  => 500,
            "code"    => "success",
            "title"   => "Réussite",
            "msg"     => 'Vous avez accès a tous les établissements',
        ];
        return $this->respond($response);
    }else {
        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "Success",
            "title"   => "Réussite",
            "msg"     => 'Opération reussir',
            "data"    => $school[0]
        ];
        return $this->respond($response);
    }

    }
}
