<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LicenceModel;
use App\Controllers\DateOperation;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\History;
include('helpers/DateOperation.php');
include('History/HistorySession.php');

class LicenceController extends BaseController
{
    use ResponseTrait;

    public function verif_licence($id_school)
    {
        $LicenceModel = new LicenceModel();
        
        $data = $LicenceModel->getLicenceDescBySchool($id_school);
        $DateOperation = new DateOperation();
        
        if(sizeof($data) == 0){
            return false;
        }else{
            $date1_deb = $data[0]['date_debut'];
            $date_fin = $data[0]['date_fin'];
            $licence_id = $data[0]['licence_id'];
            $day = $DateOperation->DateAtNumber(date("Y-m-d"));
            
            if ($day > $date_fin) {
                # code...
                $donnee = [
                    'status_licence' => 1,
                    'etat_licence'   => 'inactif',
                ];
                $LicenceModel->where('licence_id', $licence_id)->set($donnee)->update();
                return false;
            }else{
                return true;
            }
        }
    }

    public function insert_licence(){
        $LicenceModel = new LicenceModel();

        $rules = [
            'date_debut' => [
                'rules'     => 'required'
            ],
            'date_fin'   => [
                'rules'     => 'required'
            ],
            'user_id'    =>[
                'rules'     =>'required'
            ],
            'school_id'  =>[
                'rules'     =>'required'
            ]
        ];

        if ($this->validate($rules)) {
            $DateOperation  = new DateOperation();
            $date_debut     = $DateOperation->DateAtNumber($this->request->getvar('date_debut'));
            $date_fin       = $DateOperation->DateAtNumber($this->request->getvar('date_fin'));
            $user_id        = $this->request->getvar('user_id');
            $school_id      = $this->request->getvar('school_id');

            // session
            $HistorySession = new HistorySession();
            $data_session   = $HistorySession-> getInfoSession();
            $id_user        = $data_session['id_user'];
            $type_user      = $data_session['type_user'];
            $login          = $data_session['login'];
            $password       = $data_session['password'];

            $data = [
                'date_debut'        => $date_debut,
                'date_fin'          => $date_fin,
                'id_user'           => $user_id,
                'status_licence'    => 0,
                'school_id'         => $school_id,
                'etat_licence'      => 'actif',
                'created_at'        => date("Y-m-d H:m:s"),
                'updated_at'        => date("Y-m-d H:m:s"),
            ];
            $LicenceModel->insert($data);
            $response = [
                "success" =>true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                'msg'     => 'Insertion reussir',
            ];
            // history
            $donnee = $data["date_debut"].",".$data["date_fin"].",".$data["id_user"].",".$data["status_licence"].",".$data["etat_licence"].",".$data["created_at"].",".$data["updated_at"];
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Licence", "", "", $donnee);
            return $this->respond($response);

        }else{
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
}
