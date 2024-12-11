<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourcePresenter;
use App\Controllers\BaseController;
use App\Models\YearModel;
use App\Models\StudentModel;
use App\Models\PaymentModel;
use App\Models\SessionModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\BourseModel; 
use App\Models\BourseStudentModel; 
use App\Controllers\History;
use App\Models\CycleModel;
use App\Models\MontantScolariteModel;
use App\Models\StudentCycleModel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

include('History/HistorySession.php');
include('fpdf/fpdf.php');
include('report/FPDF_RECU.php');
include('report/FPDF_LISTING.php');

class ScolariteController extends ResourcePresenter
{
    use ResponseTrait;

    public function historique_paiement(){
        return view('scolarite/historique_paiement');
    }

    public function save_bourse(){
        return view('scolarite/save_bourse');
    }

    public function save_inscription(){
        return view('scolarite/save_inscription');
    }
    
    public function save_pension(){
        return view('scolarite/save_pension');
    }

    public function statistique_scolarite(){
        return view('scolarite/StatistiquePayement');
    }

    public function montant_scolarite(){
        return view('scolarite/montantMcolarite');
    }

    public function updatePaiement()
    {
        $PaymentModel = new PaymentModel();

        // validation du formulaire 
        $rules = [
            'inscription'           => [
                'rules' => 'required'
            ],
            'montant_lettre'          => [
                'rules' => 'required'
            ],
            'idPaiement'          => [
                'rules' => 'required'
            ],
        ];

        if ($this->validate($rules)) {

            $inscription      = $this->request->getvar('inscription');
            $montant_lettre   = $this->request->getvar('montant_lettre');
            $idPaiement       = $this->request->getvar('idPaiement');
            
            $data = [
                'montant'              => $inscription,
                'montant_lettre'        => $montant_lettre,
                'updated_at'        => date("Y-m-d H:m:s")
            ];

            if ($PaymentModel->where('payment_id', $idPaiement)->set($data)->update() === false) {
                // echec de modification
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     => "Echec de modification",
                    ];
                return $this->respond($response);

            }else{
                    // modification reussir
                $response = [
                    'success' => true,
                    'status'  => 200,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     => "modification reussir",
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
                "Error"   =>$this->validator->getErrors(),
                "msg"     =>"Echec de validation"
            ];
            return $this->respond($response);
        }
    }

    public function getOne($id){
        $PaymentModel = new PaymentModel();
        $dataPayement = $PaymentModel->getAllPaymentById($id);
        return $this->respond($dataPayement);
    }
    
    public function deletePaiement($id_paiement){
        $PaymentModel = new PaymentModel();

        $data = [
            "etat_payment"   => "inactif",
            "status_payment" => 1,
            "deleted_at"     => date("Y-m-d H:m:s")
        ];

        if ($PaymentModel->where('payment_id', $id_paiement)->set($data)->update() === false) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Echec de suppression',
            ];
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération de suppression réussite réussite',
            ];
            return $this->respond($response);
        }
    }

    public function allPaiement($id_class, $id_cycle){
        $YearModel      = new YearModel();
        $yearActif      = $YearModel->getYearActif();
        $year_id        = $yearActif[0]["year_id"];

        $PaymentModel = new PaymentModel();
        $dataPayement = $PaymentModel->getAllPaymentByClass($year_id, $id_class);
        return $this->respond($dataPayement);
    }

    public function recap_payement($name_school, $name_session, $name_cycle, $name_classe, $student_id){
        $YearModel      = new YearModel();
        $yearActif      = $YearModel->getYearActif();
        $year_id        = $yearActif[0]["year_id"];
        $BourseModel = new BourseModel();
        $BourseStudentModel = new BourseStudentModel();
        $montant_bourse = 0;
        // check bourse
        $data_bourse = $BourseStudentModel->AllBourseStudent($name_session, $name_cycle, $name_classe, $year_id, $student_id);
        foreach ($data_bourse as $bourse) {
            $montant_bourse += $bourse["amount"];
        }
        // total versement
        $PaymentModel = new PaymentModel();
        $montant_payement = 0;
        $dataPayement = $PaymentModel->getAllPaymentStudent($name_school, $year_id, $name_classe, $name_session, $student_id);
        foreach ($dataPayement as $payement) {
            $montant_payement += $payement["montant"];
        }
        // scolarite
        $MontantScolariteModel = new MontantScolariteModel();
        $data_montant = $MontantScolariteModel->getMontantScolarClass($year_id, $name_classe, $name_school);
        $montant_scolar = 0;
        if(sizeof($data_montant) != 0){
            $montant_scolar = $data_montant[0] ["montant"];
        }
        
        $data = [
            "montant_bourse" => $montant_bourse,
            "montant_payement" => $montant_payement,
            "montant_scolar" => $montant_scolar
        ];
        return $this->respond($data);
    }

    public function list_bourse(){
        $BourseModel = new BourseModel();

        $YearModel      = new YearModel();
        $yearActif      = $YearModel->getYearActif();
        $year_id        = $yearActif[0]["year_id"];

        $list_bourse = $BourseModel->getAllBourses($year_id);
        return $this->respond($list_bourse);
        
    }

    public function enregistrer_bourse(){
        $BourseModel = new BourseModel();
        
        $rules = [
            'nomBourse'   => [ 
                'rules'         => 'required'
            ],
            'montant_bourse'       => [
                'rules'         => 'required'
            ],
            'description_bourse'   => [
                'rules'         => 'required'
            ]
        ];
        
        if ($this->validate($rules)) {
            $nomBourse    = $this->request->getvar('nomBourse');
            $montant_bourse        = $this->request->getvar('montant_bourse');
            $description_bourse = $this->request->getvar('description_bourse');
            $YearModel      = new YearModel();
            $yearActif      = $YearModel->getYearActif();
            $year_id        = $yearActif[0]["year_id"];

            $data = [
                'name'          => $nomBourse,
                'description'   =>$description_bourse,
                'amount'        =>$montant_bourse,
                'status'        =>0,
                'year_id'       => $year_id,
                'etat'          => 'actif',
                'created_at'    => date("Y-m-d H:m:s"),
                'updated_at'    => date("Y-m-d H:m:s")
            ];

            if ($BourseModel->save($data)) {
                
                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    "msg"     => 'L\'enregistrement c\'est terminer avec succèss',
                ];
                return $this->respond($response);
            }else{
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'L\'enregistrement à échouer',
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
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur informations invalides",
            ];

            return $this->respond($response); 
        }
    }


    public function allClassMontant($id_school, $id_session, $id_cycle){

        $ClassModel   = new ClassModel();
        $SessionModel = new SessionModel();
        $CycleModel   = new CycleModel();
        $YearModel    = new YearModel();
        $SchoolModel  = new SchoolModel();

        $school       = $SchoolModel->findAllSchoolByidSchool($id_school);
        $session      = $SessionModel->getSessionById($id_session);
        $cycle        = $CycleModel->getCycleById($id_cycle);

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password']; 

        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette école n\'existe pas',
            ];
           //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "cette ecole n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($session) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Cette session n\'existe pas',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "cette session n'existe pas");
            return $this->respond($response);
        }
        if (sizeof($cycle) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Ce cycle n\'existe pas',
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "ce cycle n'existe pas");
            return $this->respond($response);
        }

        $data_class = $ClassModel->getAllClassSchoolSessionCycle($id_school, $id_session, $id_cycle);

        if (sizeof($data_class) == 0) {
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération reussir',
                "data"    => array(),
            ];
            //history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", "Opération reussir");
            return $this->respond($response);
        }
        //-- restaure data
        $data_organize = array();
        $MontantScolariteModel = new MontantScolariteModel();
        $YearModel      = new YearModel();
        $yearActif      = $YearModel->getYearActif();
        $year_id        = $yearActif[0]["year_id"];
        foreach ($data_class as $row) {
            // select montant
            $data_montant_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $row["class_id"], $school[0]["school_id"]);
            $row['name'] = $ClassModel->format_name_class($row['name']);
            if (sizeof($data_montant_scolar) != 0) {
                $row['montant'] = $data_montant_scolar[0]["montant"];
            }else{
                $row['montant'] = 0;
            }
            $data_organize[] = $row;
        }

        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "success",
            "title"   => "Réussite",
            "msg"     => 'Opération reussir',
            "data"    => $data_organize,
        ];
        //history
        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Selection", "Echec", "Classe", "", "", 'Opération reussir');
        return $this->respond($response);
    }

    public function payer_montant_scolarite(){
        $SchoolModel  = new SchoolModel();
        $YearModel    = new YearModel();
        $SessionModel = new SessionModel();
        $CycleModel   = new CycleModel();
        $MontantScolariteModel = new MontantScolariteModel();

        $rules = [
            'name_school'           => [ 
                'rules'                 => 'required'
            ],
            'name_session'          => [
                'rules'                 => 'required'
            ],
            'user_id'               => [
                'rules'                 => 'required'
            ],
            'class_id'              => [
                'rules'                 => 'required'
            ],
            'name_cycle'            => [
                'rules'                 => 'required'
            ],
            'montant_scolarite'     => [
                'rules'                 => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            $id_school    = $this->request->getvar('name_school');
            $id_section   = $this->request->getvar('name_session');
            $id_cycle     = $this->request->getvar('name_cycle');
            $tab_id_classe= $this->request->getvar('class_id[]');
            $montants     = $this->request->getvar('montant_scolarite[]');
            $user_id      = $this->request->getvar('user_id');

            $school       = $SchoolModel->getIDSchool($id_school);
            $session      = $SessionModel->getSessionById($id_section);
            $cycle        = $CycleModel->getCycleById($id_cycle);

            if (sizeof($school) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Désoler nous n'avons pas pu trouver cet établissement",
                ];
                return $this->respond($response); 
            }
            if (sizeof($session) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Désoler nous n'avons pas pu trouver cette section",
                ];
                return $this->respond($response); 
            }
            if (sizeof($cycle) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Désoler nous n'avons pas pu trouver ce cycle",
                ];
                return $this->respond($response); 
            }
            $YearModel      = new YearModel();
            $yearActif      = $YearModel->getYearActif();
            $year_id        = $yearActif[0]["year_id"];
            //-- insert Montant scolarite
            if (sizeof($tab_id_classe) != 0) {
                for ($i=0; $i < sizeof($tab_id_classe); $i++) {
                
                    if ($montants[$i] == "") {
                        $montants[$i] = 0;
                    }
                    $data_montant_scolarite = [
                        "school_id"                 => $id_school,
                        "montant"                   => $montants[$i],
                        "class_id"                  => $tab_id_classe[$i],
                        "year_id"                   => $year_id,
                        "id_user"                   => $user_id,
                        "etat_montant_scolarite"    => "actif",
                        "status_montant_scolarite"  => 0,
                        "created_at"                => date("Y-m-d H:m:s"),
                        "updated_at"                => date("Y-m-d H:m:s"),
                        "deleted_at"                => date("Y-m-d H:m:s")
                    ];
                    // verifier si cela existe deja
                    $data_montant_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $tab_id_classe[$i], $id_school); 
                    if (sizeof($data_montant_scolar) == 0) {
                        #insert
                        $MontantScolariteModel->save($data_montant_scolarite);
                    }else if (sizeof($data_montant_scolar) != 0 && sizeof($data_montant_scolar) == 1) {
                        # update
                        $MontantScolariteModel->where('montant_scolarite_id', $data_montant_scolar[0]["montant_scolarite_id"])->set($data_montant_scolarite)->update();
                    }else{
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            "msg"     => "Une erreur est survenue lors de l'opération",
                        ];
        
                        return $this->respond($response); 
                    }
                }
                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    "msg"     => 'Insertion réussir',
                ];
                return $this->respond($response);
            }else{
                //validation failed
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Vous avez soumis des montant vides",
                ];

                return $this->respond($response); 
            }

            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "Success",
                "title"   => "Réussite",
                "msg"     => 'L\'enregistrement c\'est terminer avec succèss',
            ];
            return $this->respond($response);

        }else{
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur informations invalides",
            ];

            return $this->respond($response); 
        }

    }

    public function print_stat_scolarite(){
        $StudentModel = new StudentModel();
        $SchoolModel  = new SchoolModel();
        $PaymentModel = new PaymentModel();
        $YearModel    = new YearModel();
        $SessionModel = new SessionModel();
        $ClassModel   = new ClassModel();
        $CycleModel   = new CycleModel();
         $BourseStudentModel = new BourseStudentModel();

        $rules = [
            'name_school'   => [ 
                'rules'         => 'required'
            ],
        ];

        if ($this->validate($rules)) {
            $id_school    = $this->request->getvar('name_school');
            $id_section   = $this->request->getvar('name_session');
            $id_cycle     = $this->request->getvar('name_cycle');
            $id_classe    = $this->request->getvar('name_classe');
            $type_liste   = $this->request->getvar('type_liste');
            $montant_max  = $this->request->getvar('montant_max');
            
            $school       = $SchoolModel->getIDSchool($id_school);
            $YearModel      = new YearModel();
            $yearActif      = $YearModel->getYearActif();
            $year_id        = $yearActif[0]["year_id"];

            if($id_section == -1 && $id_cycle != -1 && $id_classe != -1){
                $student_listNew = $StudentModel->getStudentBySectionYear($year_id);
                // calcuer le montant de pension versé par chaque enfant
                $garcon = 0;
                $fille = 0;
                $student_list = array();
                $montant_verser_total = 0;
                foreach ($student_listNew as $student) {
                    if ($student["sexe"] == "M") {
                        $garcon++;
                    }else if ($student["sexe"] == "F") {
                        $fille++;
                    }

                    $montant_total = 0;
                    // select all payement
                    $all_payement = $PaymentModel->getAllPaymentStudentBySession($year_id, $student["student_id"]);
                    foreach ($all_payement as $payement) {
                        $montant_total += $payement["montant"]; 
                    }
                    $ClassModel      = new ClassModel();
                    $data_class      = $ClassModel->getClassStudentYear($student["student_id"], $year_id);
                    $id_classe       = 0;
                    if(sizeof($data_class) != 0){
                        $id_classe = $data_class[0]["class_id"];
                    }
                    $MontantScolariteModel = new MontantScolariteModel();

                    $data_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $id_classe, $id_school);
                    $montant_scolar = 0;
                    if(sizeof($data_scolar) != 0){
                        $montant_scolar = $data_scolar[0]["montant"];
                    }
                    //-- bourse eleves
                    $montant_bourse = 0; 
                    // check bourse
                    // $data_bourse = $BourseStudentModel->AllBourseStudent($id_section, $id_cycle, $id_classe, $year_id, $student["student_id"]);
                    $data_bourse = $BourseStudentModel->getStudentBourses($student["student_id"], $year_id);
                    foreach ($data_bourse as $bourse) {
                        $montant_bourse += $bourse["amount"];
                    }
                    // var_dump($montant_bourse);
                    // add montant
                    $student["montant_verser"] = $montant_total;
                    $student["montant_scolar"] = $montant_scolar;
                    $student["reduction_bourse"] = $montant_bourse;
                    
                    $montant_verser_total += $montant_total;
                    $student_list[] = $student; 
                }

                // for($i=0;$i< sizeof($student_list);$i++){
                //     if($student_list[$i]['reduction_bourse'] !== 0){
                //         var_dump($student_list); 
                //     } else{
                //         echo 'pas de bourse';
                //     }
                // }

                //-- print liste
                $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
                $fpdf->AliasNbPages(); 
                $fpdf->SetAutoPageBreak(1, 1); 
                $fpdf->AddPage();
                //-- fil
                $fpdf->Filigramme("School");
                //-- entete
                $tab_start_year = explode('-', $yearActif[0]["start_year"]);
                $title = "STATISTIQUE DE SCOLARITE DES ELEVES DE LA CLASSE DE ";
                $fpdf->header_portrait(($tab_start_year[0].' / '.($tab_start_year[0]+1)), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
                //-- footer
                $msg = "Statistique de scolaité , session  de la classe du ";
                $fpdf->footer_listing_stat_pay(38, "dsdsd");
                $MontantScolariteModel = new MontantScolariteModel();
                //$montant_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $class[0]["class_id"], $id_school); 
                $scolarite_attendu = 0;
                /*if (sizeof($montant_scolar) != 0) {
                    $scolarite_attendu = $montant_scolar[0]["montant"];
                }*/
                //-- listing statistique
                $fpdf->listing_stat_payement2($student_list, $garcon, $fille, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $montant_verser_total, $scolarite_attendu, "classe", $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], "insolvable_solvable");
                // /***********************/
                
                //-- sortie
                $name_folder = getenv('FILE_PRINT_DOC');
                $name_file = $name_folder.'/Liste_eleves_'.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
                $fpdf->Output($name_file,'F');

                $response = [
                    'success'   => true,
                    'status'    => 200,
                    'name_file' => $name_file,
                ];
                return $this->respond($response);

            }else if($id_section != -1 && $id_cycle == -1 && $id_classe != -1){
                
            }else if($id_section != -1 && $id_cycle != -1 && $id_classe == -1){
                
            }else if($id_section != -1 && $id_cycle != -1 && $id_classe != -1){
                $session      = $SessionModel->getSessionById($id_section);
                $cycle        = $CycleModel->getCycleById($id_cycle);
                $class        = $ClassModel->getIDClass($id_classe);

                if (sizeof($school) == 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Désoler nous n'avons pas pu trouver cet établissement",
                    ];
                    return $this->respond($response); 
                }
                if (sizeof($session) == 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Désoler nous n'avons pas pu trouver cette section",
                    ];
                    return $this->respond($response); 
                }
                if (sizeof($cycle) == 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Désoler nous n'avons pas pu trouver ce cycle",
                    ];
                    return $this->respond($response); 
                }
                if (sizeof($class) == 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "Désoler nous n'avons pas pu trouver cette classe",
                    ];
                    return $this->respond($response); 
                }
                
                $MontantScolariteModel = new MontantScolariteModel();
                $montant_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $class[0]["class_id"], $id_school); 
                $scolarite_attendu = 0;
                if (sizeof($montant_scolar) != 0) {
                    $scolarite_attendu = $montant_scolar[0]["montant"];
                }

                // find All students in class
                $student_listNew = $StudentModel->getStudentByClassYear($id_classe, $year_id);
                // calcuer le montant de pension versé par chaque enfant
                $garcon = 0;
                $fille = 0;
                $student_list = array();
                $montant_verser_total = 0;
                foreach ($student_listNew as $student) {
                    if ($student["sexe"] == "M") {
                        $garcon++;
                    }else if ($student["sexe"] == "F") {
                        $fille++;
                    }

                    $montant_total = 0;
                    // select all payement
                    $all_payement = $PaymentModel->getAllPaymentStudent($id_school, $year_id, $id_classe, $id_section, $student["student_id"]);
                    
                    foreach ($all_payement as $payement) {
                        $montant_total += $payement["montant"];
                    }
                     //-- bourse eleves
                    $montant_bourse = 0;
                    // check bourse
                    $data_bourse = $BourseStudentModel->AllBourseStudent($id_section, $id_cycle, $id_classe, $year_id, $student["student_id"]);
                    foreach ($data_bourse as $bourse) {
                        $montant_bourse += $bourse["amount"];
                    }
                    $student["reduction_bourse"] = $montant_bourse;
                    $student["montant_scolar"] = $scolarite_attendu;

                    if($montant_max != "" && $montant_max > 0 && ($montant_total >= $montant_max)){
                        // add montant
                        $student["montant_verser"] = $montant_total;
                        
                        $montant_verser_total += $montant_total;
                        $student_list[] = $student; 
                    }else if($montant_max != "" && $montant_max < 0 && ($montant_total < ($montant_max*(-1)))){
                        // add montant
                        $student["montant_verser"] = $montant_total;
                        
                        $montant_verser_total += $montant_total;
                        $student_list[] = $student; 
                    }else if($montant_max == ""){
                        // add montant
                        $student["montant_verser"] = $montant_total;
                        
                        $montant_verser_total += $montant_total;
                        $student_list[] = $student; 
                    }

                    
                }

                //var_dump($montant_verser_total);
                //-- select scolarite
                
                //-- print liste
                $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
                $fpdf->AliasNbPages(); 
                $fpdf->SetAutoPageBreak(1, 1); 
                $fpdf->AddPage();
                //-- fil
                $fpdf->Filigramme("School");
                //-- entete
                $tab_start_year = explode('-', $yearActif[0]["start_year"]);
                $title = "STATISTIQUE DE SCOLARITE DES ELEVES DE LA CLASSE DE ".strtoupper($ClassModel->format_name_class($class[0]["name"]));
                $fpdf->header_portrait(($tab_start_year[0].' / '.($tab_start_year[0]+1)), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
                //-- footer
                $msg = "Statistique de scolaité ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$ClassModel->format_name_class($class[0]["name"]);
                $fpdf->footer_listing_stat_pay(38, $ClassModel->format_name_class($class[0]["name"]));
                
                //-- listing statistique
                $fpdf->listing_stat_payement($student_list, $garcon, $fille, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $montant_verser_total, $scolarite_attendu, $ClassModel->format_name_class($class[0]["name"]), $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], $type_liste);
                // /***********************/
                
                //-- sortie
                $name_folder = getenv('FILE_PRINT_DOC');
                $name_file = $name_folder.'/Liste_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$ClassModel->format_name_class($class[0]["name"]).'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
                $fpdf->Output($name_file,'F');

                $response = [
                    'success'   => true,
                    'status'    => 200,
                    'name_file' => $name_file,
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
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur informations invalides",
            ];

            return $this->respond($response); 
        }

    }

    public function payer_scolarite(){
        $StudentModel = new StudentModel();
        $SchoolModel  = new SchoolModel();
        $PaymentModel = new PaymentModel();
        $YearModel    = new YearModel();
        $SessionModel = new SessionModel();
        $ClassModel   = new ClassModel();
        $StudentCycleModel   = new StudentCycleModel();
        
        $MontantScolariteModel   = new MontantScolariteModel();
        
        $rules = [
            'name_school'   => [ 
                'rules'         => 'required'
            ],
            'student'       => [
                'rules'         => 'required'
            ],
            'inscription'   => [
                'rules'         => 'required'
            ],
            'user_id'       => [
                'rules'         => 'required'
            ],
            'montant_lettre'=> [
                'rules'         => 'required'
            ]
        ];
        
        if ($this->validate($rules)) {
            $id_school      = $this->request->getvar('name_school');
            $id_student     = $this->request->getvar('student');
            $inscription    = $this->request->getvar('inscription');
            $user_id        = $this->request->getvar('user_id');
            $montant_lettre = $this->request->getvar('montant_lettre');
            $YearModel      = new YearModel();
            $yearActif      = $YearModel->getYearActif();
            $year_id        = $yearActif[0]["year_id"];

            $student        = $StudentModel->getOneStudent($id_student);
            $school         = $SchoolModel->getIDSchool($id_school);
            $session        = $SessionModel->getSessionStudentYear($id_student, $year_id);
            $class          = $ClassModel->getClassStudentYear($id_student, $year_id);
            $cycle          = $StudentCycleModel->getStudentCycleById($id_student, $year_id);

            // verifier que l'eleve n'a pas encore tous solder
            $montant_scolar = $MontantScolariteModel->getMontantScolarClass($year_id, $class[0]["class_id"], $id_school); 
            $all_payement = $PaymentModel->getAllPaymentStudent($id_school, $year_id, $class[0]["class_id"], $session[0]["session_id"], $id_student);

            $BourseModel = new BourseModel();
            $BourseStudentModel = new BourseStudentModel();
            $montant_bourse = 0;
            // check bourse
            $data_bourse = $BourseStudentModel->AllBourseStudent($session[0]["session_id"], $cycle[0]["cycle_id"], $class[0]["class_id"], $year_id, $id_student);
            foreach ($data_bourse as $bourse) {
                $montant_bourse += $bourse["amount"];
            }

            $scolarite_attendu = 0;
            $scolarite_payer = 0;
            if (sizeof($montant_scolar) != 0) {
                $scolarite_attendu = $montant_scolar[0]["montant"];
            }
            if (sizeof($all_payement) != 0) {
                foreach ($all_payement as $payement) {
                    $scolarite_payer += $payement["montant"];
                }
            }

            $scolarite_payer = $scolarite_payer + $montant_bourse;

            if (($scolarite_payer != 0 && $scolarite_attendu != 0) && ($scolarite_payer > $scolarite_attendu)) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Le montant de la scoalarité est atteind',
                ];
                return $this->respond($response);
            }

            if (($scolarite_payer != 0 && $scolarite_attendu != 0) && ($scolarite_payer+$inscription > $scolarite_attendu)) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Le montant que vous venez d'entrer est élevé",
                ];
                return $this->respond($response);
            }

            $data = [
                'montant'       => $inscription,
                'montant_lettre'=> $montant_lettre,
                'mode_payment'  => "espece",
                'motif_payment' => "scolarite",
                'status_payment'=> 0,
                'etat_payment'  => 'actif',
                'id_user'       => $user_id,
                'year_id'       => $year_id,
                'school_id'     => $school[0]["school_id"],
                'student_id'    => $id_student,
                'class_id'      => $class[0]["class_id"],
                'session_id'    => $session[0]["session_id"],
                'created_at'    => date("Y-m-d H:m:s"),
                'updated_at'    => date("Y-m-d H:m:s")
            ];

            if ($PaymentModel->save($data)) {
                //- print recus inscription
                //- format A4 identique ont garde l'original
                $year_id = $yearActif[0]["year_id"];
                $start_year = $yearActif[0]["start_year"];
                $tab_start_year = explode('-', $yearActif[0]["start_year"]);

                $donnee = [
                    "paye_par"          => $student[0]["name"]." ".$student[0]["surname"],
                    "mat_school"        => $school[0]["matricule"],
                    "paye_a"            => $school[0]["name"],
                    "mat"               => $student[0]["matricule"], 
                    "year"              => $tab_start_year[0].' / '.($tab_start_year[0]+1), 
                    "phone"             => $school[0]["phone"],  
                    "date"              => "Bafoussam le ".date("Y-m-d"),
                    "name"              => $student[0]["name"],
                    "surname"           => $student[0]["surname"],
                    "sexe"              => $student[0]["sexe"],
                    "salaire"           => $inscription." Fcfa",
                    "salaire_lettre"    => $montant_lettre,
                    "photo"             => $student[0]["photo"],
                    "banques"           => [
				            "0" => $school[0]["name"],
			        ] 
                ];

                $name_file = $this->printRecus($donnee, "SCOLARITE");

                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    "msg"     => 'L\'enregistrement c\'est terminer avec succèss',
                    "name_file"=> $name_file,
                ];
                return $this->respond($response);
            }else{
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'L\'enregistrement à échouer',
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
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur informations invalides",
            ];

            return $this->respond($response); 
        }
    }

    public function payer_inscription(){
        $StudentModel = new StudentModel();
        $SchoolModel  = new SchoolModel();
        $PaymentModel = new PaymentModel();
        $YearModel    = new YearModel();
        $SessionModel = new SessionModel();
        $ClassModel   = new ClassModel();
        
        $rules = [
            'school'        => [ 
                'rules'         => 'required'
            ],
            'student'       => [
                'rules'         => 'required'
            ],
            'inscription'   => [
                'rules'         => 'required'
            ],
            'user_id'       => [
                'rules'         => 'required'
            ],
            'montant_lettre'=> [
                'rules'         => 'required'
            ]
        ];
        
        if ($this->validate($rules)) {
            $id_school      = $this->request->getvar('school');
            $id_student     = $this->request->getvar('student');
            $inscription    = $this->request->getvar('inscription');
            $user_id        = $this->request->getvar('user_id');
            $montant_lettre = $this->request->getvar('montant_lettre');
            $YearModel      = new YearModel();
            $yearActif      = $YearModel->getYearActif();
            $year_id        = $yearActif[0]["year_id"];

            $student        = $StudentModel->getOneStudent($id_student);
            $school         = $SchoolModel->getIDSchool($id_school);
            $session        = $SessionModel->getSessionStudentYear($id_student, $year_id);
            $class          = $ClassModel->getClassStudentYear($id_student, $year_id);
    
            $data = [
                'montant'       => $inscription,
                'montant_lettre'=> $montant_lettre,
                'mode_payment'  => "espece",
                'motif_payment' => "inscription",
                'status_payment'=> 0,
                'etat_payment'  => 'actif',
                'id_user'       => $user_id,
                'year_id'       => $year_id,
                'school_id'     => $school[0]["school_id"],
                'student_id'    => $id_student,
                'class_id'      => $class[0]["class_id"],
                'session_id'    => $session[0]["session_id"],
                'created_at'    => date("Y-m-d H:m:s"),
                'updated_at'    => date("Y-m-d H:m:s")
            ];

            if ($PaymentModel->save($data)) {
                //- print recus inscription
                //- format A4 identique ont garde l'original
                $year_id = $yearActif[0]["year_id"];
                $start_year = $yearActif[0]["start_year"];
                $tab_start_year = explode('-', $yearActif[0]["start_year"]);

                $donnee = [
                    "paye_par"          => $student[0]["name"]." ".$student[0]["surname"],
                    "mat_school"        => $school[0]["matricule"],
                    "paye_a"            => $school[0]["name"],
                    "mat"               => $student[0]["matricule"], 
                    "year"              => $tab_start_year[0].' / '.($tab_start_year[0]+1), 
                    "phone"             => $school[0]["phone"],  
                    "date"              => "Bafoussam le ".date("Y-m-d"),
                    "name"              => $student[0]["name"],
                    "surname"           => $student[0]["surname"],
                    "sexe"              => $student[0]["sexe"],
                    "salaire"           => $inscription." Fcfa",
                    "salaire_lettre"    => $montant_lettre,
                    "photo"             => $student[0]["photo"],
                    "banques"           => [
				            "0" => $school[0]["name"],
			        ] 
                ];

                $name_file = $this->printRecus($donnee, "l'INSCRIPTION");

                $response = [
                    "success" => true,
                    "status"  => 200,
                    "code"    => "Success",
                    "title"   => "Réussite",
                    "msg"     => 'L\'enregistrement c\'est terminer avec succèss',
                    "name_file"=> $name_file,
                ];
                return $this->respond($response);
            }else{
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'L\'enregistrement à échouer',
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
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur informations invalides",
            ];

            return $this->respond($response); 
        }
    }

    ############################################
    #               REPORTING                  #
    ############################################

    /*
    |-------------------------------------------------------------------
    | Generate QR Code
    |-------------------------------------------------------------------
    |
    | @param $data   QR Content
    |
    */
    function generate_qrcode($mat, $nom, $date, $school, $logo)
    {

        /* QR Code File Directory Initialize */
        $dir = getenv('PICTURE_QRCODE');
        $dir_logo = getenv('FILE_LOGO_SCHOOL').'/'.$logo;
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* Load QR Code Library */
        $writer = new PngWriter();

        // Create QR code
        $qrCode = QrCode::create($mat . ' | ' . $nom . ' | ' . $date . ' | '.$school.' ' . md5($mat) . '' . rand(1, 1000))
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic logo
        $logo = Logo::create(FCPATH . $dir_logo)
            ->setResizeToWidth(20);

        // Create generic label
        $label = Label::create($mat . ' | ' . $nom . ' | ' . $date . ' | ICAB ' . md5($mat) . '' . rand(1, 1000))
            ->setTextColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode, $logo, $label);

        $save_name  = FCPATH .$dir.'/'. $mat . '.png';

        $result->saveToFile($save_name);
        $dataUri = $result->getDataUri();


        return $dir.'/'. $mat . '.png';
    }

    public function printRecus($data, $titre){

        $fpdf  = new FPDF_RECU('L', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");

        // Left
		$fpdf->header_recu($data, 0);
		//Right
		$fpdf->header_recu($data, 146);
		//-- footer
		// Left
		$fpdf->footer_listing($data['date'], 0, 1); 
		// right
		$fpdf->footer_listing($data['date'], 146, 2); 
		//-- listing operations
		// Left
		$fpdf->contentScolarite($data, 0, $titre);
		// // Right
		$fpdf->contentScolarite($data, 146, $titre);

        //-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Recu_paiement_salaire_'.str_replace(" ", "", $data['name'])."_".str_replace(" ", "", $data['surname']).'_'.(str_replace(" / ", "", $data["year"])).'.pdf';
		$fpdf->Output($name_file,'F');

        return $name_file;
    }

    

}
