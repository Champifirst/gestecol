<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\TeacherModel;
use App\Models\DocumentModel;
use App\Models\YearModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\TeacherSchoolModel;
use App\Models\TeacherClassModel;
use App\Models\TeacherUnitClassModel;
use App\Models\SalaireModel;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Controllers\History;

include('History/HistorySession.php');
include('fpdf/fpdf.php'); 
include('report/FPDF_LISTING.php');
include('report/FPDF_CERT.php');
include('report/FPDF_RECU.php');

class TeacherController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('teacher/save.php');
    }
    
    public function liste(){
        return view('teacher/list.php');
    }

    public function GiveClass(){
        return view('teacher/giveClass.php');
    }

    public function GiveSubjet(){
        return view('teacher/giveSubjet.php');
    }

    public function importer(){
        return view('teacher/importer.php');
    }

    public function salaire_personnel(){
        return view('teacher/salaire.php');
    }

    public function historiqueSalaire(){
        return view('teacher/historiqueSalaire.php');
    }

    public function listHistoriqueSalaire(){
        // get historique paeiement

    }

    public function payer_salaire(){
        // save salary
        $TeacherClassModel = new TeacherClassModel();
        $TeacherModel = new TeacherModel();
        $SalaireModel = new SalaireModel();
        $rules = [
            'school'        => [ 
                'rules'         => 'required'
            ],
            'personnel'     => [
                'rules'         => 'required'
            ],
            'salaire'       => [
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
            $school     = $this->request->getvar('school');
            $personnel  = $this->request->getvar('personnel');
            $salaire    = $this->request->getvar('salaire');
            $user_id    = $this->request->getvar('user_id');
            $montant_lettre  = $this->request->getvar('montant_lettre');
            $YearModel  = new YearModel();
            $yearActif  = $YearModel->getYearActif();
            $year_id    = $yearActif[0]["year_id"];
            $enseignant = $TeacherModel->getOneTeacherBySchool($school, $personnel);
            $data = [
                'code_payement' => date("Y-m")."-".$enseignant[0]["matricule"],
                'teacher_id'    => $personnel,
                'montant'       => $salaire,
                'montant_lettre'=> $montant_lettre,
                'mode_payement' => "espece",
                'date_payement' => date("Y-m-d"),
                'status_salaire'=> 0,
                'etat_salaire'  => 'actif',
                'id_user'       => $user_id,
                'year_id'       => $year_id,
                'created_at'    => date("Y-m-d H:m:s"),
                'updated_at'    => date("Y-m-d H:m:s")
            ];

            if ($SalaireModel->save($data)) {
                //- print recus de payement
                //- format A4 identique ont garde l'original
                $SchoolModel = new SchoolModel();
                $YearModel = new YearModel();
                $yearActif = $YearModel->getYearActif();
                $year_id = $yearActif[0]["year_id"];
                $start_year = $yearActif[0]["start_year"];
                $tab_start_year = explode('-', $yearActif[0]["start_year"]);

                $school = $SchoolModel->getIDSchool($school);
                $donnee = [
                    "paye_par"          => $school[0]["name"],
                    "mat_school"        => $school[0]["matricule"],
                    "paye_a"            => $enseignant[0]["name"]." ".$enseignant[0]["surname"],
                    "mat"               => $enseignant[0]["matricule"], 
                    "year"              => $tab_start_year[0].' / '.($tab_start_year[0]+1), 
                    "phone"             => $school[0]["phone"],  
                    "date"              => "Bafoussam le ".date("Y-m-d"),
                    "name"              => $enseignant[0]["name"],
                    "surname"           => $enseignant[0]["surname"],
                    "sexe"              => $enseignant[0]["sexe"],
                    "salaire"           => $salaire." Fcfa",
                    "salaire_lettre"    => $montant_lettre,
                    "photo"             => $enseignant[0]["photo"],
                    "banques"           => [
				            "0" => $school[0]["name"],
			        ] 
                ];

                $name_file = $this->printRecus($donnee);

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

    public function findAllPersonnelBySchool($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel  = new SchoolModel();
        $YearModel    = new YearModel();
        $yearActif    = $YearModel->getYearActif();
        $year_id      = $yearActif[0]["year_id"];
        $school       = $SchoolModel->getIDSchool($id_school);

        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $data_final = $TeacherModel->getAllTeacherBySchool($id_school);
        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "Success",
            "title"   => "Réussite",
            "msg"     => 'Opération réussir',
            "data"    => $data_final
        ];
        return $this->respond($response);

    }

    public function attribution_class(){
        $TeacherClassModel = new TeacherClassModel();
        $rules = [
            'name_teacher' => [ 
                'rules'         => 'required'
            ],
            'id_class'     => [
                'rules'         => 'required'
            ],
            'user_id'      => [
                'rules'         => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            $id_teachers  = $this->request->getvar('name_teacher');
            $id_classes   = $this->request->getvar('id_class');
            $user_id      = $this->request->getvar('user_id');
            $YearModel    = new YearModel();
            $yearActif    = $YearModel->getYearActif();
            $year_id      = $yearActif[0]["year_id"];
            $line_error   = "";

            for ($i=0; $i < sizeof($id_classes); $i++) { 
                $id_teacher = $id_teachers[$i];
                $id_class = $id_classes[$i];

                //-- insert teacher class
                if ($id_teacher != "0") {
                    $data = [
                        'class_id'              => $id_class,
                        'teacher_id'            => $id_teacher,
                        'year_id'               => $year_id,
                        'id_user'               => $user_id,
                        'etat_teacher_class'    => 'actif',
                        'status_teacher_class'  => 0,
                        'created_at'            => date("Y-m-d H:m:s"),
                        'updated_at'            => date("Y-m-d H:m:s"),
                    ];
                    $verdic = $TeacherClassModel->save($data);
                    if (!$verdic) {
                        $line_error += "[Erreur: ligne ".($i+1)."]";
                    }
                }
            }

            //validation failed
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "error"   => $line_error,
                "msg"     => "L'opération s'est terminer avec succèss",
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

    //attribution subject 
    public function attribution_subject(){
        $TeacherClassUnitModel = new TeacherUnitClassModel();
        $rules = [
            'name_teacher'      => [ 
                'rules'             => 'required'
            ],
            'id_class'          => [
                'rules'             => 'required'
            ],
            'id_teachingunit'   => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            $id_teachers  = $this->request->getvar('name_teacher');
            $id_classe   = $this->request->getvar('id_class');
            $user_id      = $this->request->getvar('user_id');
            $teaching_ids  = $this->request->getvar('id_teachingunit');
            $YearModel    = new YearModel();
            $yearActif    = $YearModel->getYearActif();
            $year_id      = $yearActif[0]["year_id"];
            $line_error   = "";
            for ($i=0; $i < sizeof($teaching_ids); $i++) { 
                $id_teacher = $id_teachers[$i];
                $teaching_id = $teaching_ids[$i];

                //-- insert teacher class
                if ($id_teacher != "0") {
                    $data = [
                        'teacher_id'            => $id_teacher,
                        'teachingunit_id'       => $teaching_id,
                        'year_id'               => $year_id,
                        'user_id'               => $user_id,
                        'class_id'              => $id_classe,
                        'status_teacher_unit_class'  => 0,
                        'etat_teacher_unit_class'    => 'actif',
                        'created_at'            => date("Y-m-d H:m:s"),
                        'updated_at'            => date("Y-m-d H:m:s"),
                    ];
                    // var_dump($data);
                    $verdic = $TeacherClassUnitModel->save($data);
                    if (!$verdic) {
                        // var_dump($TeacherClassUnitModel->errors());
                        $line_error += "[Erreur: ligne ".($i+1)."]";
                    }
                }

            }

            //validation failed
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "error"   => $line_error,
                "msg"     => "L'opération s'est terminer avec succèss",
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

    // sauvegarder le fichier excel a esporter
    public function uploadFile($path, $image) {
    	if (!is_dir($path)) 
			mkdir($path, 0777, TRUE);
		if ($image->isValid() && ! $image->hasMoved()) {
			$newName = $image->getRandomName();
			$image->move($path, $newName);
			return $path.'/'.$image->getName();
		}
		return "";
	}

    public function impoter_eacher(){
         // validation des regles
         $rules = [
            'user_id'    => [
                'rules'         => 'required'
            ],
            'school_id'  => [
                'rules'         => 'required'
            ],
            'type'       => [
                'rules'         => 'required'
            ]
        ];

        if ($this -> validate($rules)) {
            // validation good
            $user_id        = $this->request->getvar('user_id');
            $school_id      = $this->request->getvar('school_id');
            $type           = $this->request->getvar('type');
            $fichier_excel  = $this->request->getFile('file');

            $path 			= getenv('FILE_EXCEL');
            $json 			= [];
            $file_name 		= $fichier_excel;
            $file_name 		= $this->uploadFile($path, $file_name);
            $arr_file 		= explode('.', $file_name);
            $extension 		= end($arr_file);
            
            if ('csv' != $extension && 'xlsx' != $extension && 'xls' != $extension) {
                $data = [
                    "status"    => 500,
                    "success"   => false,
                    "title"     => "Attention",
                    "alert"     => "warning",
                    'msg'       => "Le fichier sélectionné est au format incorrect. (extention exiger: xls, xlsx, csv)"
                ];

                return $this->respond($data);
            }

            if('csv' == $extension) {
			    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if ('xlsx' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }else if ('xls' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            // lire le fichier
            $spreadsheet 	= $reader->load($file_name);
		    $sheet_data 	= $spreadsheet->getActiveSheet()->toArray();

            if (sizeof($sheet_data[0]) < 11) {
                $data = [
                     "status"    => 500,
                     "success"   => false,
                     "title"     => "Attention",
                     "alert"     => "warning",
                     'msg'       => "Vérifier le nombre de colonnes du fichier, taille demander 11 colonnes"
                 ];
 
                 return $this->respond($data);
            }

            $YearModel = new YearModel();
            $TeacherModel = new TeacherModel();
            $yearActif = $YearModel->getYearActif();
            $year_id = $yearActif[0]["year_id"];

            foreach($sheet_data as $key => $val) {
                if($key != 0 && $val[1] != NULL && $val[2]) {
                    $matricule  = "";
                    $name       = "";
                    $prenom     = "";
                    $diplome    = "";
                    $phone      = "";
                    $salaire    = 0;
                    $email      = "";
                    $password   = "";
                    $login      = "";
                    $sexe       = "";

                    if ($val[1] != NULL) {
                        $matricule = $val[1];
                    }
                    if ($val[2] != NULL) {
                        $name = $val[2];
                    }
                    if ($val[3] != NULL) {
                        $prenom = $val[3];
                    }
                    if ($val[4] != NULL) {
                        if ($val[4] == 'M' || $val[4] == 'F') {
                            $sexe = $val[4];
                        }
                    }
                    if ($val[5] != NULL) {
                        $diplome = $val[5];
                    }
                    if ($val[6] != NULL) {
                        $email = $val[6];
                    }
                    if ($val[7] != NULL) {
                        $contact = $val[7];
                    }
                    if ($val[8] != NULL) {
                        $salaire = $val[8];
                    }
                    if ($val[9] != NULL) {
                        $login = $val[9];
                    }
                    if ($val[10] != NULL) {
                        $password = $val[10];
                    }

                    $enseignant = $TeacherModel->getTeacherExists($school_id, $year_id, $matricule, $name);

                    if (sizeof($enseignant) == 0) { 
                    
                        $data = [
                            'name'          => $name,
                            'matricule'     => $matricule,
                            'surname'       => $prenom,
                            'diplome'       => $diplome,
                            'email'         => $email,
                            'tel'           => $phone,
                            'photo'         => getenv('USER_PROFIL'),
                            'login'         => $login,
                            'password'      => md5($password),
                            'id_user'       => $user_id,
                            'year_id'       => $year_id,
                            'sexe'          => $sexe,
                            'status_teacher'=> 0,
                            'etat_teacher'  => 'actif',
                            'created_at'    => date('Y-m-d H:m:s'),
                            'updated_at'    => date('Y-m-d H:m:s'),
                        ];

                        if ($TeacherModel->save($data)) {
                            // insert teacher_school
                            $teacher_id = $TeacherModel->getId();
                            $TeacherSchoolModel = new TeacherSchoolModel();
                            $data_teacher_school = [
                                'school_id'             => $school_id,
                                'teacher_id'            => $teacher_id,
                                'year_id'               => $year_id,
                                'salaire'               => $salaire,
                                'type_ens'              => $type,
                                'etat_teacher_school'   => 'actif',
                                'status_teacher_school' => 0,
                                'created_at'            => date("Y-m-d H:m:s"),
                                'updated_at'            => date("Y-m-d H:m:s")
                            ];
        
                            $TeacherSchoolModel->save($data_teacher_school);
                        }
                    }
                }
            }

            // success insert
            $response = [
                'success' => true,
                'status'  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                'msg'     => 'Importation réussir',
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
                "msg"     => "Erreur de validation",
            ];

            return $this->respond($response); 
        }
    }

    #@-- 1 --> insertion des enseignants
    #- use:
    #-
    public function insertteacher()
    {
    //extenciation de la classe
        $TeacherModel = new TeacherModel();

        // validation des regles
        $rules = [
            'matricule'   => [ 
                'rules'         => 'required|is_unique[teacher.matricule]'
            ],
            'name'       => [
                'rules'         => 'required'
            ],
            'prenom'     => [
                'rules'         => 'required'
            ],
            'diplome'    => [
                'rules'         => 'required'
            ],
            'phone'      => [
                'rules'         => 'required'
            ],
            'salaire'    => [
                'rules'         => 'required'
            ],
            'user_id'    => [
                'rules'         => 'required'
            ],
            'school_id'  => [
                'rules'         => 'required'
            ],
            'sexe'       => [
                'rules'         => 'required'
            ],
            'type'       => [
                'rules'         => 'required'
            ],
            'login'      => [
                'rules'         => 'required|is_unique[teacher.login]'
            ],
            'password'   => [
                'rules'         => 'required|is_unique[teacher.password]'
            ]
        ];

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];

        if ($this -> validate($rules)) {
            // validation good
            $matricule  = $this->request->getvar('matricule');
            $name       = $this->request->getvar('name');
            $prenom     = $this->request->getvar('prenom');
            $diplome    = $this->request->getvar('diplome');
            $phone      = $this->request->getvar('phone');
            $salaire    = $this->request->getvar('salaire');
            $user_id    = $this->request->getvar('user_id');
            $school_id  = $this->request->getvar('school_id');
            $email      = $this->request->getvar('email');
            $photo      = $this->request->getFile('photo');
            $type       = $this->request->getvar('type');
            $password   = $this->request->getvar('password');
            $login      = $this->request->getvar('login');
            $sexe       = $this->request->getvar('sexe');

            $YearModel = new YearModel();
            $yearActif = $YearModel->getYearActif();
            $year_id = $yearActif[0]["year_id"];
            
            $enseignant = $TeacherModel->getTeacherExists($school_id, $year_id, $matricule, $name);
            if (sizeof($enseignant) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Cet enseignant existe déjà",
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Enseignant", "", "", "Mots de passe differents ");
                return $this->respond($response);
            }

            /*====================== IMPORT PHOTO ======================*/
            $name_photo = $photo->getName();
            // Renaming file before upload
            $temp_photo = explode(".",$name_photo);
            $new_photo_name = round(microtime(true)) . '.' . end($temp_photo);
            $dbHost = getenv('FILE_PHOTO_TEACHER');
            $verdic = $photo->move($dbHost, $new_photo_name);

             if (!$verdic) {
                 // failed insert
                 $response = [
                     "success" => false,
                     "status"  => 500,
                     "code"    => "error",
                     "title"   => "Erreur",
                     'msg'     => 'Echec insertion de la photo',
                 ];
                 return $this->respond($response);
            }else{
                $data = [
                    'name'          => $name,
                    'matricule'     => $matricule,
                    'surname'       => $prenom,
                    'diplome'       => $diplome,
                    'email'         => $email,
                    'tel'           => $phone,
                    'photo'         => $new_photo_name,
                    'login'         => $login,
                    'password'      => md5($password),
                    'id_user'       => $user_id,
                    'year_id'       => $year_id,
                    'sexe'          => $sexe,
                    'status_teacher'=> 0,
                    'etat_teacher'  => 'actif',
                    'created_at'    => date('Y-m-d H:m:s'),
                    'updated_at'    => date('Y-m-d H:m:s'),
                ];

                if ($TeacherModel->save($data)) {
                    // insert teacher_school
                    $teacher_id = $TeacherModel->getId();
                    $TeacherSchoolModel = new TeacherSchoolModel();
                    if ($salaire == "") {
                        $salaire = 0;
                    }
                    $data_teacher_school = [
                        'school_id'             => $school_id,
                        'teacher_id'            => $teacher_id,
                        'year_id'               => $year_id,
                        'salaire'               => $salaire,
                        'type_ens'              => $type,
                        'etat_teacher_school'   => 'actif',
                        'status_teacher_school' => 0,
                        'created_at'            => date("Y-m-d H:m:s"),
                        'updated_at'            => date("Y-m-d H:m:s")
                    ];

                    if ($TeacherSchoolModel->save($data_teacher_school)) {
                        $response = [
                            "success" => true,
                            "status"  => 200,
                            "code"    => "Success",
                            "title"   => "Réussite",
                            "msg"     => 'Insertion réussir',
                        ];
                        return $this->respond($response);
                         // history
                         $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Enseignant", "", "", "Insertion Réussir");
                    }else{
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            "msg"     => 'Echec d\'insertion',
                        ];
                        // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Enseignant", "", "", "Echec d'insertion");
                        return $this->respond($response);
                    }
                    
                }else{
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => 'Echec d\'insertion',
                    ];
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
                "error"   => $this->validator->getErrors(),
                "msg"     => "Erreur de validation",
            ];

            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Enseignant", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
            
    }

    public function GetTeacherSchoolClass($id_school, $id_session, $id_cycle){
        $TeacherModel = new TeacherModel();
        $ClassModel = new ClassModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }
        
        $data_classe = $ClassModel->getAllClassSchoolSessionCycle($id_school, $id_session, $id_cycle);
        
        $data_final = array();
        $enseignants = array();
        // verifier ceux qui ont des salles de classes
        foreach ($data_classe as $row) {
            # selectionner les enseignant de chaque salle
            $teacher = $TeacherClassModel->getTeacherClass($row["class_id"], $year_id);
            $name_teacher = "";
            $id_teacher = "";
            if (sizeof($teacher) != 0) {
                $name_teacher = $teacher[0]["name"]." ".$teacher[0]["surname"];
                $id_teacher = $teacher[0]["teacher_id"];
            }
            $data_final[] = [
                "class_id"       => $row["class_id"],
                "code_class"     => $row["number"],
                "name_class"     => $ClassModel->format_name_class($row["name"]),
                "id_enseignant"  => $id_teacher,
                "name_enseignant"=> $name_teacher,
            ];
            $enseignants = $TeacherModel->getAllTeacherBySchool($id_school);
        }

        $response = [
            "success"       => true,
            "status"        => 200,
            "code"          => "Success",
            "title"         => "Réussite",
            "msg"           => 'Opération réussir',
            "data"          => $data_final,
            "enseginants"   => $enseignants
        ];
        return $this->respond($response);
    }

    public function allTeacher($id_school, $type_ens){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $permanent = "";
        $vaccataire = "";
        if ($type_ens == "enseignant") {
            $permanent = "permanent";
            $vaccataire = "vaccataire";
        }else if ($type_ens == "chauffeur") {
            $permanent = "chauffeur_permanent";
            $vaccataire = "chauffeur_vaccataire";
        }else if ($type_ens == "gardien") {
            $permanent = "gardien_permanent";
            $vaccataire = "gardien_vaccataire";
        }else if ($type_ens == "directeur") {
            $permanent = "directeur_permanent";
            $vaccataire = "directeur_vaccataire";
        }else if ($type_ens == "entretien") {
            $permanent = "entretien_permanent";
            $vaccataire = "entretien_vaccataire";
        }

        $data = array();
        if ($type_ens == "0") {
            $data = $TeacherModel->getAllTeacherBySchool($id_school);
        }else{
            $data = $TeacherModel->getTeacherBySchool($id_school, $vaccataire, $permanent);
        }

        $data_final = array();
        foreach ($data as $key) {
            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final[] = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe
            ];
        }

        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "Success",
            "title"   => "Réussite",
            "msg"     => 'Opération réussir',
            "data"    => $data_final
        ];
        return $this->respond($response);
    }

    #@-- 2 --> modification des enseignants
    #- use:
    #-

    public function updateteacher()
    {
        //extenciation de la classe
        $TeacherModel = new TeacherModel();

        // validation des regles
        $rules = [
            'name'      => [ 
                'rules' => 'required|max_length[35]'
            ],
            'telephone'       => [
                'rules' =>'required|max_length[9]'
            ],
            'email'     => [
                'rules' =>'required'
            ],
            'login'     => [
                'rules' =>'required|max_length[10]'
            ],
            'school'  => [
                'rules' =>'required'
            ],
            'password'  => [
                'rules' =>'required|max_length[10]'
            ],
            'confirm_password'  => [
                'rules' =>'required|max_length[10]'
            ],
            'user_id'  => [
                'rules' =>'required|max_length[10]'
            ],
            'teacher_id'  => [
                'rules' =>'required|max_length[10]'
            ]
        ];

        // session
        $HistorySession = new HistorySession();
        $data_session = $HistorySession-> getInfoSession();
        $id_user   = $data_session['id_user'];
        $type_user = $data_session['type_user'];
        $login     = $data_session['login'];
        $password  = $data_session['password'];

        if ($this -> validate($rules)) {
            // validation good

            $name               = $this->request->getvar('name');
            $login              = $this->request->getvar('login');
            $phone              = $this->request->getvar('telephone');
            $email              = $this->request->getvar('email');
            $school             = $this->request->getvar('school');
            $password           = $this->request->getvar('password');
            $confirm_password   = $this->request->getvar('confirm_password');
            $user_id            = $this->request->getvar('user_id');
            $teacher_id         = $this->request->getvar('teacher_id');

            if ($name == NULL || $login == NULL || $phone == NULL || $email == NULL || $school == NULL || $password == NULL || $confirm_password == NULL) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Tous les champs sont obligatoires",
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Tous les champs sont obligatoires ");
                return $this->respond($response);
            } 

            if ($password != $confirm_password) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => "Mots de passe différents",
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Mots de passe differents ");
                return $this->respond($response);
            }else{

                $password_bd = $TeacherModel->getPassword($password);

                if (sizeof($password_bd) != 0) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "mot de passe existe",
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Ce mot de passe existe ");
                    return $this->respond($response);

                }else{

                   $data_teacher = $TeacherModel->getTeacher($name, $login,$phone,$email,$school);

                    if (sizeof($data_teacher) != 0) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            "msg"     => 'enseignant existe deja, modifier vos parametres',
                        ];
                        // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Cet enseignant existe deja, modifier vos parametres ");
                        return $this->respond($response);
                    }else{

                        $data = [
                            'name'              => strtolower($name),
                            'login'             => strtolower($login),
                            'phone'             => $phone,
                            'email'             => strtolower($email),
                            'password'          => md5($password),
                            'school_id'         => $school,
                            'id_user'           => $user_id,
                            'updated_at'        => date("Y-m-d H:m:s"),
                        ];
                        if ($TeacherModel->where('teacher_id', $teacher_id)->set($data)->update() === false) {

                            // echec modification
                            $response = [
                                "success" => false,
                                "status"  => 500,
                                "code"    => "error",
                                "title"   => "Erreur",
                                'msg'     => 'echec de modification',
                            ];
                            // history
                            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Echec de modification");
                            return $this->respond($response);
                        }else{
                            //  modification reussir

                            $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'modification reussir',
                                ];
                            // history
                            $donnee = $data["name"].",".$data["login"].",".$data["phone"].",".$data["email"].",".$data["password"].",".$data["school_id"].",".$data["id_user"].",".$data["updated_at"];

                            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Enseignant", "", "", $donnee);
                            return $this->respond($response);

                        }
                    }   
                }   
            }   
        }else{
            // validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "Error"     => $this->validator->getErrors(),
                "msg"   => "Echec de validation",
            ];

            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Enseignant", "", "", "Echec de validation ");
            return $this->respond($response);  
        }

    }

    #@-- 3 --> supprimer des enseignants
    #- use:
    #-
    public function deleteteacher($id_teacher){

        $TeacherModel = new TeacherModel(); 
        $data = $TeacherModel->getTeacherById($id_teacher);

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
                'msg'     => "Cet enseignant n'existe pas",
            ];
           // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Enseignant", "", "", "Cet enseignant n'existe pas");
            return $this->respond($response);
        }else{ 
  
        $data = [
          'status_teacher'    => 1,
          'etat_teacher'      => 'inactif',
          'deleted_at'         => date("Y-m-d H:m:s"),
        ];
            if ($TeacherModel->where('teacher_id', $id_teacher)->set($data)->update() === false) {
                  // echec de suppression
                  $response = [
                      "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => "Echec de suppression",
                    ];
                  
                 //history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Enseignant", "", "", "echec Suppression");
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
                $donnee = $data['status_teacher'].",".$data['etat_teacher']. ",". $data['deleted_at'];
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Reussite", "Enseignant", "", "", $donnee);
                return $this->respond($response);
            
            }   
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

    public function printRecus($data){

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
		$fpdf->content($data, 0, "SALAIRE");
		// // Right
		$fpdf->content($data, 146, "SALAIRE");

        //-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Recu_paiement_salaire_'.str_replace(" ", "", $data['name'])."_".str_replace(" ", "", $data['surname']).'_'.(str_replace(" / ", "", $data["year"])).'.pdf';
		$fpdf->Output($name_file,'F');

        return $name_file;
    }

    public function print_listSchool($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $permanent = "permanent";
        $vaccataire = "vaccataire";
        $data = $TeacherModel->getTeacherBySchool($id_school, $vaccataire, $permanent);
        $gar_enseig = sizeof($TeacherModel->getTeacherBySchoolSexe($id_school, "M", $vaccataire, $permanent));
        $fille_enseig = sizeof($TeacherModel->getTeacherBySchoolSexe($id_school, "F", $vaccataire, $permanent));
        $data_final = array();
        foreach ($data as $key) {
            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final[] = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe']
            ];
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
		// //-- footer
        $msg = "Liste des enseignants ";
		$fpdf->footer_listing(38, $msg); 
		//-- listing des enseignants
        $title = "LISTE DES ENSEIGNANTS ";
        $fpdf->listing_enseignant($title, $data_final, $gar_enseig, $fille_enseig, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], $msg);
		/***********************/
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Liste_enseignant_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function PrintListTeacherVaccataire($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $data = $TeacherModel->getAllTeacherBySchoolTypeEng($id_school, "vaccataire");
        $gar_enseig = sizeof($TeacherModel->getAllTeacherBySchoolTypeEngSexe($id_school, "vaccataire", "M"));
        $fille_enseig = sizeof($TeacherModel->getAllTeacherBySchoolTypeEngSexe($id_school, "vaccataire", "F"));
        $data_final = array();
        foreach ($data as $key) {
            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final[] = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe']
            ];
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
		// //-- footer
        $msg = "Liste des enseignants vaccataireS";
		$fpdf->footer_listing(38, $msg); 
		//-- listing des enseignants
        $title = "LISTE DES ENSEIGNANTS VACCATAIRE";
        $fpdf->listing_enseignant($title, $data_final, $gar_enseig, $fille_enseig, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], $msg);
		/***********************/
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Liste_enseignant_vaccataire_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function PrintListTeacherPermanent($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $data = $TeacherModel->getAllTeacherBySchoolTypeEng($id_school, "permanent");
        $gar_enseig = sizeof($TeacherModel->getAllTeacherBySchoolTypeEngSexe($id_school, "permanent", "M"));
        $fille_enseig = sizeof($TeacherModel->getAllTeacherBySchoolTypeEngSexe($id_school, "permanent", "F"));
        $data_final = array();
        foreach ($data as $key) {
            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final[] = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe']
            ];
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
		// //-- footer
        $msg = "Liste des enseignants permanent";
		$fpdf->footer_listing(38, $msg); 
		//-- listing des enseignants
        $title = "LISTE DES ENSEIGNANTS PERMANENTS";
        $fpdf->listing_enseignant($title, $data_final, $gar_enseig, $fille_enseig, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], $msg);
		/***********************/
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Liste_enseignant_vaccataire_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function PrintContrat($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $permanent = "permanent";
        $vaccataire = "vaccataire";
        $data = $TeacherModel->getTeacherBySchool($id_school, $vaccataire, $permanent);

        $fpdf  = new FPDF_CERT('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 

        foreach ($data as $key) {
            $fpdf->AddPage();
            //-- fil
		    $fpdf->Filigramme("School");
            //-- entete
		    $fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);

            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe'],
                'poste'         => "Enseignant"
            ];
            //-- 
            $fpdf->body_contrat_travail($data_final, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['responsable'], $key['photo']);
        }
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Contrat_travail_enseignant_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function printFicheDecharge($id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $permanent = "permanent";
        $vaccataire = "vaccataire";
        $data = $TeacherModel->getTeacherBySchool($id_school, $vaccataire, $permanent);
        $gar_enseig = sizeof($TeacherModel->getTeacherBySchoolSexe($id_school, "M", $vaccataire, $permanent));
        $fille_enseig = sizeof($TeacherModel->getTeacherBySchoolSexe($id_school, "F", $vaccataire, $permanent));
        $data_final = array();
        foreach ($data as $key) {
            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final[] = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe']
            ];
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);
		// //-- footer
        $msg = "Fiche de décharge";
		$fpdf->footer_listing(38, $msg); 
		//-- listing des enseignants
        $title = "FICHE DE DECHARGE "; 
        $fpdf->fiche_decharge_enseignant($data_final, $gar_enseig, $fille_enseig, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule'], $msg);
		/***********************/
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Fiche_decharge_enseignant_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
    
    public function printOneContrat($teacher_id, $type_ens, $id_school){
        $TeacherModel = new TeacherModel();
        $SchoolModel = new SchoolModel();
        $YearModel = new YearModel();
        $TeacherClassModel = new TeacherClassModel();
        $ClassModel = new ClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);

        $school = $SchoolModel->getIDSchool($id_school);
        if (sizeof($school) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Cette école n'existe pas",
            ];
            return $this->respond($response); 
        }

        $type_personnel = "";
        if ($type_ens == "enseignant") {
            $type_personnel = "ENSEIGNANT";
        }else if ($type_ens == "chauffeur") {
            $type_personnel = "CHAUFFEUR";
        }else if ($type_ens == "gardien") {
            $type_personnel = "GARDIEN";
        }else if ($type_ens == "directeur") {
            $type_personnel = "DIRECTEUR";
        }else if ($type_ens == "entretien") {
            $type_personnel = "AGENT D'ENTRETIEN";
        }

        $data = $TeacherModel->getOneTeacherBySchool($id_school, $teacher_id);

        $fpdf  = new FPDF_CERT('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 

        foreach ($data as $key) {
            $fpdf->AddPage();
            //-- fil
		    $fpdf->Filigramme("School");
            //-- entete
		    $fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['phone'], $school[0]['matricule']);

            $id_teacher = $key['teacher_id'];
            $data_class = $TeacherClassModel->getClassOneTeacher($id_teacher, $year_id);
            $classe = '';
            $type = '';
            $salaire = '';
            $sum = 0;
            $i=0;
            foreach ($data_class as $row) {
                if (strlen($classe) != 0) {
                    $classe = $classe.', '.$ClassModel->format_name_class($row['name']);
                    $type = $type.', '.$row['type_ens'];
                    $salaire = $salaire.', '.$row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }else {
                    $classe = $ClassModel->format_name_class($row['name']);
                    $type = $row['type_ens'];
                    $salaire = $row['salaire'].'fcfa';
                    $sum = $sum+$row['salaire'];
                }
                $i++;
            }
            $chaine_salaire = $salaire;
            if ($i > 1) {
                $chaine_salaire = $chaine_salaire.': '.$sum.'fcfa';
            }
            $data_final = [
                'teacher_id'    => $key['teacher_id'],
                'matricule'     => $key['matricule'],
                'name'          => $key['name'],
                'surname'       => $key['surname'],
                'diplome'       => $key['diplome'],
                'contact'       => $key['tel'],
                'photo'         => $key['photo'],
                'type_ens'      => $type,
                'salaire'       => $chaine_salaire,
                'classe'        => $classe,
                'sexe'          => $key['sexe'],
                'poste'         => $type_personnel
            ];
            //-- 
            $fpdf->body_contrat_travail($data_final, $tab_start_year[0].' / '.($tab_start_year[0]+1), $school[0]['name'], $school[0]['responsable'], $key['photo']);
        }
		
		//-- sortie
        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Contrat_travail_enseignant_'.$key['name'].'_'.$school[0]['name'].'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function printFichePaie(){

    }


}
    

