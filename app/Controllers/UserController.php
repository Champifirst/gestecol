<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\FonctionnalityUserModel;
use App\Models\FonctionnalityModel;
use App\Controllers\enums;

include('helpers/Fonctionnality.php');
include('enums/Enums.php');

class UserController extends BaseController
{
    use ResponseTrait;

    public function listAllUser(){
        $UserModel = new UserModel();
        return $this->respond($UserModel->listAllUser());
    }

    public function listUserActif(){
        $UserModel = new UserModel();
        return $this->respond($UserModel->listUserActif());
    }   

    public function listUserInactif(){
        $UserModel = new UserModel();
        return $this->respond($UserModel->listUserInactif());
    }

    public function listUserDelete(){
        $UserModel = new UserModel();
        return $this->respond($UserModel->listUserDelete());
    }


    public function listUserNotDelete(){
        $UserModel = new UserModel();
        return $this->respond($UserModel->listUserNotDelete());
    }

    public function addUser()
    {

        $rules = [
            'login'             => [
                    'rules'         => 'required|min_length[3]|trim' 
            ],
            'password'          => [
                    'rules'         => 'required|min_length[3]|trim'
            ],
            'confirm_password'  => [
                'rules'             => 'required|matches[password]|trim'
            ],
            'type_user'         => [
                'rules'             => 'required|min_length[4]|trim'
            ]
        ];

        if ($this->validate($rules)) {
            // validation good
            $login = $this->request->getvar('login');
            $password = $this->request->getvar('password');
            $confirm_password = $this->request->getvar('confirm_password');
            $type_user = strtolower($this->request->getvar('type_user'));

            // $login = "demeto";
            // $password = "demeto";
            // $confirm_password = "demeto";
            // $type_user = "admin";

            $UserModel = new UserModel();

            if ($password != $confirm_password) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Mot de passe différents",
                ];
    
                return $this->respond($response);
            }

            if ($login == NULL || $password == NULL || $confirm_password == NULL || $type_user == NULL) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Tous les champs sont obligatoires",
                ];
    
                return $this->respond($response);
            }

            $Enumcy = new Enumcy();
            $enumUser =  $Enumcy->enumUser();
            $exist = 0;
            for ($i=0; $i < sizeof($enumUser); $i++) { 
                if ($enumUser[$i] == $type_user) {
                    $exist++;
                }
            }
            if ($exist == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec d'insertion, ce type de d'utilisateur n'existe pas",
                ];
    
                return $this->respond($response);
            }

            $user = $UserModel->getUserByLoginPassword(strtolower($login), md5($password));
            if (sizeof($user) == 0) {
                $row = [
                    'login'         => strtolower($login),
                    'password'      => md5($password),
                    'type_user'     => $type_user,
                    'etat_user'     => "actif",
                    'status_user'   => 0,
                    'created_at'    => date("Y-m-d H:m:s"),
                    'updated_at'    => date("Y-m-d H:m:s")
                ];
                $verdic = $UserModel->save($row);
                if (!$verdic) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Echec d'insertion",
                    ];
        
                    return $this->respond($response);
                }else{
                    $response = [
                        "success" => true,
                        "status"  => 400,
                        "title"   => "Réussite",
                        "code"    => "success",
                        "msg"     => "Insertion Réussir",
                    ];
        
                    return $this->respond($response);
                }
            }else {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec cet utilisateur existe déjà",
                ];
    
                return $this->respond($response);
            }
        }else{
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => $this->validator->getErrors(),
            ];
            return $this->respond($response);
        }
    }

    public function attachUserFonct($id_user, $coded){

        $UserModel = new UserModel();
        $FonctionnalityUserModel = new FonctionnalityUserModel();
        $FonctionnalityModel = new FonctionnalityModel();

        if ($id_user == NULL || $coded == NULL) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec la fonctionnalité et l'utilsateur sont obligatoires",
            ];

            return $this->respond($response);
        }

        $user = $UserModel->getUserById($id_user);
        if (sizeof($user) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec d'isertion cette utilisateur n'existe pas",
            ];

            return $this->respond($response);
        }

        $fonct = $FonctionnalityModel->getFoncByCoded($coded);

        if (sizeof($fonct) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec d'isertion cette fonctionnalité n'existe pas",
            ];

            return $this->respond($response);
        }else if (sizeof($fonct) > 0) {
            $fonct_user = $FonctionnalityUserModel->getFoncUserByIdUserIdFonc($id_user, $fonct[0]['coded']);
            if (sizeof($fonct_user) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec d'isertion cet utilisateur possede déjà fonctionnalité ",
                ];
    
                return $this->respond($response);
            }else{
                // verifier que auccune de ces fonctiionnalités ne possede celle si comme enfant
                foreach ($fonct_user as $fonctUser_row) {
                    $data_child = $FonctionnalityUserModel->getChildFonct($fonctUser_row["coded"], $coded);
                    if (sizeof($data_child) != 0) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            "msg"     => "Echec d'isertion cet utilisateur possède déjà une fonctionnalité dont celle ci hérite ",
                        ];
            
                        return $this->respond($response);
                    }
                }
            }
            // insertion
            $dataInsert = [
                'id_user'               => $id_user,
                'id_fonctionnality'     => $coded,
                'status_fonct_user'     => "actif",
                'etat_fonct_user'       =>  0,
                'created_at'            => date("Y-m-d H:m:s"),
                'updated_at'            => date("Y-m-d H:m:s")
            ];
            $verdic = $FonctionnalityUserModel->save($dataInsert);

            if ($verdic) {

                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "success",
                    "title"   => "Réussite",
                    "msg"     => "Opération Réussir",
                ];
                return $this->respond($response);
                
            }else {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec de l'opération",
                ];
    
                return $this->respond($response);
            }
        }
    }
}
