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
use App\Controllers\History;
include('History/HistorySession.php');
include('fpdf/fpdf.php');
include('report/FPDF_RECU.php');

class ScolariteController extends ResourcePresenter
{
    use ResponseTrait;

    public function save_inscription(){
        return view('scolarite/save_inscription');
    }
    
    public function save_pension(){
        return view('scolarite/save_pension');
    }

    public function payer_scolarite(){
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
