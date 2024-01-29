<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\helpers;
use App\Controllers\enums;
use App\Models\FonctionnalityModel;

include('helpers/Fonctionnality.php');
include('enums/Enums.php');

class FonctionnlityController extends BaseController
{
    use ResponseTrait;

    public function defaultFonctionnality()
    {
        $SHareHelper = new SHareHelper();
        $FonctionnalityModel = new FonctionnalityModel;
        $data = $SHareHelper->fonctionnality();
        
        $exists = 0;
        foreach ($data as $key => $row) {
            $fonct = $FonctionnalityModel->getFoncByCoded($row["coded"]);
            if (sizeof($fonct) == 0) {
                $Enumcy = new Enumcy();
                $enumFonct =  $Enumcy->enumFonctionnality();
                $test = 0;
                for ($i=0; $i < sizeof($data); $i++) { 
                    for ($j=0; $j < sizeof($enumFonct); $j++) { 
                        if ($data[$i]['type_fonct'] == $enumFonct[$j]) { 
                            $test++;
                        }
                    }
                }
                if ($test == 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Echec d'insertion, ce type de fonctionnalité n'existe pas",
                    ];
        
                    return $this->respond($response);
                }
                $verdic = $FonctionnalityModel->save($row);
                if (!$verdic) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Echec d'isertion",
                    ];
        
                    return $this->respond($response);
                }
            }else {
                $exists++;
            }
        }

        if ($exists != 0) {
            $response = [
                "success" => true,
                "status"  => 400,
                "title"   => "Réussite",
                "code"    => "success",
                "msg"     => "Insertion Réussir, certaines fonctionnalités était déjà présentes",
            ];

            return $this->respond($response);
        }else if ($exists == 0) {
            $response = [
                "success" => true,
                "status"  => 400,
                "title"   => "Réussite",
                "code"    => "success",
                "msg"     => "Insertion Réussir, certaines fonctionnalités était déjà présentes",
            ];

            return $this->respond($response);
        }
    }

    public function atachDefaultFonct(){
        $SHareHelper = new SHareHelper();
        $FonctionnalityModel = new FonctionnalityModel;
        $data = $SHareHelper->attachFonctionnality();

        foreach ($data as $key) {
            if (!isset($key['parent']) || !isset($key['child'])) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Les deux codes des fonctionnalités sont obligatoires",
                ];
    
                return $this->respond($response);
            }

            $fonct_parent = $key['parent'];
            $fonct_child = $key['child'];

            if (strlen($fonct_parent) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "La fonctionnalité du parent n'existe pas",
                ];
                return $this->respond($response);
            }

            if (strlen($fonct_child) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "La fonctionnalité de l'enfant n'existe pas",
                ];
    
                return $this->respond($response);
            }

            if ($fonct_parent != "parent") {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "La fonctionnalité parente est mal definir",
                ];

                return $this->respond($response);
            }

            if ($fonct_child != "child") {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "La fonctionnalité enfant est mal definir",
                ];
    
                return $this->respond($response);
            }
        }
    
        
        if ($fonct_parent[0]['array_fonct'] != "") {
            // if exists
            $array_fonct = explode(",", $fonct_parent[0]['array_fonct']);
            $verif = 0;
            for ($e=0; $e < sizeof($array_fonct); $e++) { 
                if ($array_fonct[$e] == $coded_child) {
                    $verif++;
                }
            }
            if ($verif != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec cette relation existe déjà",
                ];
            }else{
                $fonct_parent[0]['array_fonct'] = $fonct_parent[0]['array_fonct'].",".$coded_child;
            }
            
        }else if ($fonct_parent[0]['array_fonct'] == "") {
            $fonct_parent[0]['array_fonct'] = $coded_child;
        }
        
        if ($FonctionnalityModel->where('id_fonctionnlity', $fonct_parent[0]['id_fonctionnlity'])->set($fonct_parent[0])->update() === false) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec de l'opération",
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
    }

    public function addFonctionnality(){
        $FonctionnalityModel = new FonctionnalityModel;

        $rules = [
            'coded'      => [
                    'rules'     => 'required|min_length[4]' 
            ],
            'name'        => [
                    'rules'     => 'required|min_length[4]'
            ],
            'type_fonct'  => [
                'rules'         => 'required|min_length[4]'
            ]
            
        ];

        if ($this->validate($rules)) {
            // validation good
            $name       = $this->request->getvar('name');
            $coded      = strtolower($this->request->getvar('coded'));
            $type_fonct = strtolower($this->request->getvar('type_fonct'));
            $array_fonct = strtolower($this->request->getvar('array_fonct'));
            $FonctionnalityModel = new FonctionnalityModel;

            if ($name == NULL || $coded == NULL || $type_fonct == NULL) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec d'insertion",
                ];
    
                return $this->respond($response);
            }

            $Enumcy = new Enumcy();
            $enumFonct =  $Enumcy->enumFonctionnality();
            $exist = 0;
            for ($i=0; $i < sizeof($enumFonct); $i++) { 
                if ($enumFonct[$i] == $type_fonct) {
                    $exist++;
                }
            }
            if ($exist == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec d'insertion, ce type de fonctionnalité n'existe pas",
                ];
    
                return $this->respond($response);
            }

            
            $fonct = $FonctionnalityModel->getFoncByCoded($coded);
            if (sizeof($fonct) == 0) {
                $row = [
                    'coded'         => $coded,
                    'name'          => $name,
                    'status_fonc'   => 0,
                    'array_fonct'   => $array_fonct,
                    'type_fonct'    => $type_fonct,
                    'etat_fonc'     => 'actif',
                    'created_at'    => date("Y-m-d H:m:s"),
                    'updated_at'    => date("Y-m-d H:m:s")
                ];
                $verdic = $FonctionnalityModel->save($row);
                if (!$verdic) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Echec d'isertion",
                    ];
        
                    return $this->respond($response);
                }else{
                    $response = [
                        "success" => true,
                        "status"  => 400,
                        "title"   => "Réussite",
                        "code"    => "success",
                        "msg"     => "Insertion Réussir, certaines fonctionnalités était déjà présentes",
                    ];
        
                    return $this->respond($response);
                }
            }else {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec d'isertion cette fonctionnalité existe déjà",
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

    public function atachFonct($coded_parent, $coded_child){
        
        $FonctionnalityModel = new FonctionnalityModel;

        if ($coded_parent == NULL || $coded_child == NULL) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Les deux codes des fonctionnalités sont obligatoires",
            ];

            return $this->respond($response);
        }

        $fonct_parent = $FonctionnalityModel->getFoncByCoded($coded_parent);
        $fonct_child = $FonctionnalityModel->getFoncByCoded($coded_child);
        if (sizeof($fonct_parent) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "La fonctionnalité du parent n'existe pas",
            ];
            return $this->respond($response);
        }

        if (sizeof($fonct_child) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "La fonctionnalité de l'enfant n'existe pas",
            ];

            return $this->respond($response);
        }

        if ($fonct_parent[0]['type_fonct'] != "parent") {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "La fonctionnalité parente est mal definir",
            ];

            return $this->respond($response);
        }

        if ($fonct_child[0]['type_fonct'] != "child") {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "La fonctionnalité enfant est mal definir",
            ];

            return $this->respond($response);
        }

        if ($fonct_parent[0]['array_fonct'] != "") {
            // if exists
            $array_fonct = explode(",", $fonct_parent[0]['array_fonct']);
            $verif = 0;
            for ($e=0; $e < sizeof($array_fonct); $e++) { 
                if ($array_fonct[$e] == $coded_child) {
                    $verif++;
                }
            }
            if ($verif != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Echec cette relation existe déjà",
                ];
            }else{
                $fonct_parent[0]['array_fonct'] = $fonct_parent[0]['array_fonct'].",".$coded_child;
            }
            
        }else if ($fonct_parent[0]['array_fonct'] == "") {
            $fonct_parent[0]['array_fonct'] = $coded_child;
        }
        
        if ($FonctionnalityModel->where('id_fonctionnlity', $fonct_parent[0]['id_fonctionnlity'])->set($fonct_parent[0])->update() === false) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec de l'opération",
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
    }

}
