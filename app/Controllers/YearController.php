<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourcePresenter;
use App\Controllers\BaseController;
use App\Models\YearModel;
use App\Models\DocumentModel;
use App\Controllers\History;
include('History/HistorySession.php');

class YearController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('year/save.php');
    }
    
    public function liste(){
        return view('year/list.php');
    }

    // get Year
    public function getYearWord($date){
        $array_date = explode("-", $date);
        if (sizeof($array_date) == 1) {
            $array_date = explode("/", $date);
        }

        $year = "";
        for ($i=0; $i < sizeof($array_date); $i++) { 
            if (strlen($array_date[$i]) == 4) {
                $year = $array_date[$i];
                break;
            }
        }
        return $year;
    }

    public function insertyear()
    {
        // validation du formulaire 
        $rules = [
            'date_start'        => [
                'rules'     => 'required'
            ],
            'date_end'          => [
                'rules'     => 'required'
            ],
            'user_id'           => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $date_start     = $this->request->getvar('date_start');
            $date_end       = $this->request->getvar('date_end');
            $user_id        = $this->request->getvar('user_id');

            $YearModel      = new YearModel();
            $data_year      = $YearModel->getYear($date_start, $date_end);
            
            $number_date_start = intval($this->getYearWord($date_start));
            $number_date_end = intval($this->getYearWord($date_end));

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            if ($number_date_start >= $number_date_end) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Mauvaise programmation de l\'année: '.$this->getYearWord($date_start)." / ".$this->getYearWord($date_end),
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Année", "", "", "$number_date_start >= $number_date_end");
                return $this->respond($response);
            }

            if (sizeof($data_year) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette année existe déja',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Année", "", "", "non trouver");
                return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name_year'    => "Année Scolaire ".$this->getYearWord($date_start)." / ".$this->getYearWord($date_end),
                    'start_year'   => $date_start,
                    'end_year'     => $date_end,
                    'status_year'  => 0,
                    'id_user'      => $user_id,
                    'etat_year'    => 'inactif',
                    'created_at'   => date("Y-m-d H:m:s"),
                    'updated_at'   => date("Y-m-d H:m:s")
                ];

                if ($YearModel->save($data)) {

                    // insertion reussir
                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Reussite",
                        "msg"     => 'insertion reussir',
                    ];
                    // history
                    $plus = $data['name_year'].",". $data['start_year'].",". $data['end_year'].",". $data['status_year'].",". $data['etat_year'].",". $data['created_at'].",". $data['updated_at'];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Année", "", "", $plus);

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
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Année", "", "", "Echec d'insertion");
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
                "error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec de validation"
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Année", "", "", "Echec de validation");
            return $this->respond($response);
        }
    }

    public function yearActif(){
        $YearModel  = new YearModel();

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];


        $data = $YearModel->getYearActif();
        if (sizeof($data) != 1) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'msg'     => "Année scolaire mal programmée",
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Année active", "Echec", "Année", "", "", "Année mal programmée");
            return $this->respond($response);
        }else if(sizeof($data) == 1){
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "Success",
                "title"   => "Reussite",
                "msg"     => 'reussite',
                "data"    => $data[0],
            ];
            // history
            $plus = $data[0]['name_year'].",". $data[0]['start_year'].",". $data[0]['end_year'].",". $data[0]['status_year'].",". $data[0]['etat_year'].",". $data[0]['created_at'].",". $data[0]['updated_at'];
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Année active", "Reussite", "Année", "", "", $plus);
            return $this->respond($response);
        }
    }

    public function allYear(){
        $YearModel = new YearModel();

        $data = $YearModel->getAllYear();
        
        return $this->respond($data);
    }

    public function deleteyear($id_year){
        $YearModel = new YearModel();
        $data = $YearModel->getOneYear($id_year);

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
                'msg'     => "Cette Année n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Année", "", "", "Cette Année n'existe pas");
            return $this->respond($response);
        }else{
            if ($data[0]['etat_year'] == 'actif') {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     => "Attention cette Année est en cour",
                ];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Année", "", "", "Attention cette Année est en cour");
                return $this->respond($response);
            }

            $tab = [
                "status_year" => 1,
                "deleted_at"  => date("Y-m-d H:m:s"),
            ];

            if ($YearModel->where('year_id', $id_year)->set($tab)->update() === false) {
                // echec de suppression
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'echec Suppression',
                ];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Année", "", "", "echec Suppression");
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
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Année", "", "", "suppression reussir");
                return $this->respond($response);
            }
        }
    }

    public function activeyear($id_year){
        $YearModel = new YearModel();
        $data = $YearModel->getOneYear($id_year);

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
                'msg'     => "Cette Année n'existe pas",
            ];
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Echec", "Année", "", "", "Cette Année n'existe pas");
            return $this->respond($response);
        }else{
            if ($data[0]['etat_year'] == 'actif') {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "warning",
                    "title"   => "Alerte",
                    'msg'     => "Cette Année est déjà active",
                ];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Echec", "Année", "", "", "Cette Année est déjà active");
                return $this->respond($response);

            }else if($data[0]['etat_year'] == 'inactif'){
                $data = $YearModel->getYearActif();
                if (sizeof($data) != 1) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Mauvaise programmation de l'année scolarie",
                    ];
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Echec", "Année", "", "", "Mauvaise programmation de l'année scolarie");
                    return $this->respond($response);

                }else{
                    $data[0]['etat_year'] = 'inactif';
                    if ($YearModel->where('year_id', $data[0]['year_id'])->set($data[0])->update() === false) {
                        $response = [
                          "success" => false,
                          "status"  => 500,
                          "code"    => "error",
                          "title"   => "Erreur",
                          "msg"     => 'Oousp une erreur est survenue',
                      ];
                       $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Echec", "Année", "", "", "Oousp une erreur est survenue, reessayez");
                    return $this->respond($response);
                    }else{
                        $tab = [
                            "etat_year" => 'actif',
                        ];
                        if ($YearModel->where('year_id', $id_year)->set($tab)->update() === false) {
                            $response = [
                              "success" => false,
                              "status"  => 500,
                              "code"    => "error",
                              "title"   => "Erreur",
                              "msg"     => 'Echec d\'activation',
                          ];
                          $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Echec", "Année", "", "", "Echec d'activation");
                          return $this->respond($response);
                        }else{
                            $response = [
                              "success" => true,
                              "status"  => 200,
                              "code"    => "Success",
                              "title"   => "Réussite",
                              "msg"     => 'Activation reussir',
                          ];
                           $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Activation", "Reussite", "Année", "", "", "Activation reussir");
                          return $this->respond($response);
                        }
                    }
                }
            }
        }
    }

    public function oneYear($id_year){
        $YearModel = new YearModel();
        $data = $YearModel->getOneYear($id_year);

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
                'msg'     => "Cette année n'existe pas",
            ];
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Année", "", "", "Cette année n'existe pas");
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "Success",
                "title"   => "Réussite",
                "msg"     => 'Opération reussir',
                "data"    => $data[0]
            ];
             $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Reussite", "Année", "", "", "Opération de Selection reussir");
            return $this->respond($response);
        }
    }
    
    public function updateyear()
    {

        // validation du formulaire 
        $rules = [
            'date_start'    => [
                'rules'         => 'required'
            ],
            'date_end'      => [
                'rules'         => 'required'
            ],
            'year_id'       => [
                'rules'         => 'required'
            ],
            'id_user'       => [
                'rules'         => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $date_start     = $this->request->getvar('date_start');
            $date_end       = $this->request->getvar('date_end');
            $year_id        = $this->request->getvar('year_id');
            $id_user        = $this->request->getvar('id_user');

            $YearModel      = new YearModel();
            $data_year      = $YearModel->getYear($date_start, $date_end);
            
            $number_date_start = intval($this->getYearWord($date_start));
            $number_date_end = intval($this->getYearWord($date_end));

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];


            if ($number_date_start >= $number_date_end) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Mauvaise programmation de l\'année: '.$this->getYearWord($date_start)." / ".$this->getYearWord($date_end),
                ];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Année", "", "", "Mauvaise programmation de l'année: ".$this->getYearWord($date_start)." / ".$this->getYearWord($date_end),);
                return $this->respond($response);
            }

            if (sizeof($data_year) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette année existe déja',
                ];
                 $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Année", "", "", "Cette année existe déja");
            return $this->respond($response);
            } 
            else {
                
                $data = [
                    'name_year'    => "Année Scolaire ".$this->getYearWord($date_start)." / ".$this->getYearWord($date_end),
                    'start_year'   => $date_start,
                    'end_year'     => $date_end,
                    'id_user'      => $id_user,
                    'updated_at'   => date("Y-m-d H:m:s")
                ];

                if ($YearModel->where('year_id', $year_id)->set($data)->update() !== false) {

                    $response = [
                        "success" => true,
                        "status"  => 200,
                        "code"    => "Success",
                        "title"   => "Réussite",
                        "msg"     => 'Modification réussir',
                        "data"    => $data
                    ];
                   // history
                $donnee = $data["name_year"].$data["start_year"].$data["end_year"].$data["id_user"].$data["updated_at"];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Année", "", "", $donnee);
                return $this->respond($response);
                }
                else{
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => 'Echec de la Modification',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Année", "", "", "Echec de la Modification");
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
                "Error"   => $this->validator->getErrors(),
                "msg"     =>"Echec de modification, les données sont incorrectes",
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Année", "", "", "Echec de modification, les données sont incorrectes");
            return $this->respond($response);
        }
    }
}
