<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;


class HistoryController extends ResourcePresenter
{
    
    use ResponseTrait;

    public function liste(){
        return view('history/list.php');
    }
    
    public function listeMyHistory(){
        $session = session();
        $name_file = $session->get('name_file');
        // open file
        $file = fopen($name_file, "r");
        if ($file) {
            $data = array();
            $i = 0;
            while (($ligne = fgets($file)) !== false) {
                if ($i != 0) {
                    $explode = explode(";", $ligne);
                    $row = [
                        "num"        => ($i+1),
                        "date_heure" => $explode[3],
                        "action"     => $explode[4],
                        "entiter"    => $explode[6],
                        "status"     => $explode[5],
                        "client"     => $explode[8],
                    ];

                    $data[] = $row;
                }
                $i++;
            }

            return $this->respond($data);
        }else {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'msg'     => "Désoler nous n'arrivons pas à accéder à votre historique de session",
            ];
            return $this->respond($response);
        }
    }
    
}
