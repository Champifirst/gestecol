<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\PaymentModel;
use App\Controllers\History;
include('History/HistorySession.php');

class PaymentController extends ResourcePresenter
{
    use ResponseTrait;


    public function save(){
        return view('payment/save.php');
    }
    
    public function liste(){
        return view('payment/list.php');
    }

    #@-- 1 --> insertion des payments
    #- use:
    #-
    public function insertpayment()
    {
    //extanciation de la PaymentModel
        $PaymentModel = new PaymentModel;

        // validation du formulaire 
        $rules = [
            'montant'       => [
                'rules'             => 'required'
            ],
            'mode_payment'  => [
                'rules'             => 'required'
            ],
            'motif_payment' => [
                'rules'             => 'required'
            ],
            'name_year'     => [
                'rules'             => 'required'
            ],
            'name_school'   => [
                'rules'             => 'required'
            ],
            'name_session'  => [
                'rules'             => 'required'
            ],
            'name_class'    => [
                'rules'             => 'required'
            ],
            'name_student'  => [
                'rules'             => 'required'
            ],
            'user_id'       => [
                'rules'             => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $montant                        = $this->request->getvar('montant');
            $mode_payment                   = $this->request->getvar('mode_payment');
            $motif_payment                  = $this->request->getvar('motif_payment');
            $name_year                      = $this->request->getvar('name_year');
            $name_school                    = $this->request->getvar('name_school');
            $name_session                   = $this->request->getvar('name_session');
            $name_class                     = $this->request->getvar('name_class');
            $name_student                   = $this->request->getvar('name_student');
            
            $user_id                        = $this->request->getvar('user_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- verifier si le payment existe dans la base de donnnees
            $data_payment     = $PaymentModel->getPayment($montant,$mode_payment,$motif_payment,$name_year,$name_school, $name_session, $name_class, $name_student);

            if (sizeof($data_payment) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Le payment existe déjà',
                ];

            // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Payment", "", "", "Ce payment existe déjà");
                return $this->respond($response);
            }else{
                $data = [
                        'montant'                       => $montant,
                        'mode_payment'                  => $mode_payment,
                        'motif_payment'                 => $motif_payment,
                        'session_id'                    => strtolower($name_session),
                        'student_id'                    => strtolower($name_student),
                        'class_id'                      => strtolower($name_class),
                        'school_id'                     => strtolower($name_school),
                        'year_id'                       => $name_year,
                        'id_user'                       => $user_id,
                        'status_payment'                => 0,
                        'etat_payment'                  => 'actif',
                        'created_at'                    => date("Y-m-d H:m:s"),
                        'updated_at'                    => date("Y-m-d H:m:s"),
                    ];

                    if ($PaymentModel->insertpayment($data)) {

                    // insertion reussir
                    $response = [
                            "success" => true,
                            "status"  => 200,
                            "code"    => "Success",
                            "title"   => "Reussite",
                            "msg"     => 'insertion reussir',
                    ];
                    // history
                    $donnee = $data["montant"].",".$data["mode_payment"].",".$data["motif_payment"].",".$data["session_id"].",".$data["student_id"].",".$data["class_id"].",".$data["school_id"].",".$data["year_id"].",".$data["id_user"].",".$data["status_payment"].",".$data["etat_payment"].",".$data["created_at"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Payment", "", "", $donnee);
                    return $this->respond($response);

                }else{
                    // echec d'insertion
                        
                    $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     =>'echec insertion',
                    ];

                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Payment", "", "", "Echec d'insertion");
                    return $this->respond($response);
                }
            }
            
        } else {
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'msg'     =>$this->validator->getErrors(),
            ];

            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Payment", "", "", "Echec de validation ");
            return $this->respond($response);
        }
    }


    #@-- 2 --> modifications des paiements
    #- use:
    #-

    public function updatepayment()
    {
        // extenciation de la classe PaymentModel
        $PaymentModel = new PaymentModel();

            // validation du formulaire 
        $rules = [
            'montant'           => [
                'rules' => 'required'
            ],
            'mode_payment'          => [
                'rules' => 'required'
            ],
            'motif_payment'          => [
                'rules' => 'required'
            ],
            'name_year'          => [
                'rules' => 'required'
            ],
            'name_school'          => [
                'rules' => 'required'
            ],
            'name_session'          => [
                'rules' => 'required'
            ],
            'name_class'          => [
                'rules' => 'required'
            ],
            'name_student'          => [
                'rules' => 'required'
            ],
            'user_id'               => [
                'rules'     => 'required'
            ],
            'payment_id'               => [
                'rules'     => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good
            $montant                        = $this->request->getvar('montant');
            $mode_payment                   = $this->request->getvar('mode_payment');
            $motif_payment                  = $this->request->getvar('motif_payment');
            $name_year                      = $this->request->getvar('name_year');
            $name_school                    = $this->request->getvar('name_school');
            $name_session                   = $this->request->getvar('name_session');
            $name_class                     = $this->request->getvar('name_class');
            $name_student                   = $this->request->getvar('name_student');
            
            $user_id                        = $this->request->getvar('user_id');
            $payment_id                        = $this->request->getvar('payment_id');

            // session
            $HistorySession = new HistorySession();
            $data_session = $HistorySession-> getInfoSession();
            $id_user   = $data_session['id_user'];
            $type_user = $data_session['type_user'];
            $login     = $data_session['login'];
            $password  = $data_session['password'];

            //-- verifier si le payment existe dans la base de donnnees
            $data_payment     = $PaymentModel->getPayment($montant,$mode_payment,$motif_payment,$name_year,$name_school, $name_session, $name_class, $name_student);

            if (sizeof($data_payment) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Le payment existe déjà',
                ];

            // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Payment", "", "", "Ce payment existe déjà");
                return $this->respond($response);
            }    
            else{
                $data = [
                        'montant'                       => $montant,
                        'mode_payment'                  => $mode_payment,
                        'motif_payment'                 => $motif_payment,
                        'session_id'                    => strtolower($name_session),
                        'student_id'                    => strtolower($name_student),
                        'class_id'                      => strtolower($name_class),
                        'school_id'                     => strtolower($name_school),
                        'year_id'                       => $name_year,
                        'id_user'                       => $user_id,
                        'updated_at'                    => date("Y-m-d H:m:s"),
                    ];

                if ($PaymentModel->where('payment_id', $payment_id)->set($data)->update() === false) {
                    // echec de modification
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de modification",
                        ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Payment", "", "", "Echec de modification");
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
                    $donnee = $data["montant"].",".$data["mode_payment"].",".$data["motif_payment"].",".$data["session_id"].",".$data["student_id"].",".$data["class_id"].",".$data["school_id"].",".$data["year_id"].",".$data["id_user"].",".$data["updated_at"];

                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Payment", "", "", $donnee);
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
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Payment", "", "", "Echec de validation, les données sont incorrectes ");
            return $this->respond($response);
        }
    }
    // tous les payments par ecole

      public function allpayment($id_school,$id_student,$id_class,$id_year,$id_session){
        $PaymentModel = new PaymentModel();
        $payment = array();

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];


        if (($id_school == 0) && ($id_year== 0) && ($id_class== 0) && ($id_session== 0) &&($id_student== 0)){
            $payment = $PaymentModel->getAllPayment();
        }
        elseif (($id_year== 0) && ($id_class== 0) && ($id_session== 0) &&($id_student== 0)){
            $payment = $PaymentModel->getPaymentBySchool($id_school);
        }
           
        elseif (($id_class== 0) && ($id_session== 0) && ($id_student== 0)){
            $payment = $PaymentModel->getAllPaymentYearSchool($id_school, $id_year);
        }
        elseif (($id_class== 0) && ($id_student== 0)){
            $payment = $PaymentModel->getAllPaymentSession($id_school, $id_year, $id_session);
        }

        elseif (($id_session== 0) && ($id_student== 0)){
            $payment = $PaymentModel->getAllPaymentClass($id_school, $id_year, $id_class);
        }
        else{
            $payment = $PaymentModel->getAllPaymentStudent($id_school, $id_year, $id_class, $session, $id_student);
            }

        if (sizeof($payment) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Aucun payment trouvé',
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Payment", "", "", "Aucun payment trouvé");
            return $this->respond($response);
        }else{
            return $this->respond($payment);
        }
    }

}


