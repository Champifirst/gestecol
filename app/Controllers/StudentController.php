<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\StudentModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\CycleModel;
use App\Models\YearModel;
use App\Models\ParentModel;
use App\Models\StudentClassModel;
use App\Models\StudentCycleModel;
use App\Models\StudentSchoolModel;
use App\Models\StudentSessionModel;
use App\Models\TeacherClassModel;
use App\Models\InscriptionModel;
use App\Controllers\History;
use App\Controllers\fpdf;

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

set_time_limit(480);

include('History/HistorySession.php');
include('fpdf/fpdf.php');
include('report/FPDF_LISTING.php');

class StudentController extends ResourcePresenter
{
    use ResponseTrait;

    public function save(){
        return view('student/save.php');
    }
    
    public function liste(){
        return view('student/list.php');
    }

    public function change_photo(){
        return view('student/change_photo.php');
    }

    public function importer_liste(){
        return view('student/importer_liste.php');
    }

    public function liste_student_school($id_school){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];

        $student_list = $StudentModel->getStudentBySchoolYear($id_school, $year_id);
        if (sizeof($student_list) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Echec",
                "data"    => array(),
                "msg"     => 'Désoler auccun élève trouver'
            ];
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "data"    => $student_list,
                "msg"     => "Opération réussir"
            ];
            return $this->respond($response);
        }
        
    }

    public function get_one_student($id_student, $id_session, $id_cycle, $id_class, $id_school){
        $StudentModel   = new StudentModel();
        $SchoolModel    = new SchoolModel();
        $ClassModel     = new ClassModel();
        $SessionModel   = new SessionModel();
        $CycleModel     = new CycleModel();

        $student  = $StudentModel->getOneStudent($id_student);
        $session  = $SessionModel->getSessionById($id_session);
        $cycle    = $CycleModel->getCycleById($id_cycle);
        $class    = $ClassModel->getClassById($id_class);
        $school   = $SchoolModel->getIDSchool($id_school);

        if (sizeof($student) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Echec",
                "data"    => array(),
                "msg"     => 'Erreur cet élève n\'existe pas'
            ];
            return $this->respond($response);
        }else if (sizeof($session) == 0 || sizeof($cycle) == 0 || sizeof($class) == 0 || sizeof($class) == 0) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Echec",
                "data"    => array(),
                "msg"     => 'Une erreur est survenue'
            ];
            return $this->respond($response);
        }

        $data_final = [
            "student_id"        => $student[0]["student_id"],
            "name"              => $student[0]["name"],
            "surname"           => $student[0]["surname"],
            "date_birth"        => $student[0]["birth_place"],
            "place_birth"       => $student[0]["date_of_birth"],
            "sexe"              => $student[0]["sexe"],
            "picture"           => $student[0]["photo"],
            "id_parent"         => $student[0]["parent_id"],
            "name_parent"       => $student[0]["name_parent"],
            "surname_parent"    => $student[0]["surnameParent"],
            "email_parent"      => $student[0]["emailParent"],
            "parent_occupation" => $student[0]["professionParent"],
            "tel_parent"        => $student[0]["contactParent"],
            "adresse_parent"    => $student[0]["adresseParent"],
            "id_session"        => $session[0]["session_id"],
            "name_session"      => $session[0]["name_session"],
            "id_school"         => $school[0]["school_id"],
            "name_school"       => $school[0]["name"],
            "id_cycle"          => $cycle[0]["cycle_id"],
            "name_cycle"        => $cycle[0]["name_cycle"],
            "id_class"          => $class[0]["class_id"],
            "name_class"        => $class[0]["name"]
        ];

        $response = [
            "success" => true,
            "status"  => 200,
            "code"    => "success",
            "title"   => "Réussite",
            "data"    => $data_final,
            "msg"     => "Opération réussir"
        ];
        return $this->respond($response);
    }

    public function get_one_student_return_data($id_student, $id_session, $id_cycle, $id_class, $id_school){
        $StudentModel   = new StudentModel();
        $SchoolModel    = new SchoolModel();
        $ClassModel     = new ClassModel();
        $SessionModel   = new SessionModel();
        $CycleModel     = new CycleModel();

        $student  = $StudentModel->getOneStudent($id_student);
        $session  = $SessionModel->getSessionById($id_session);
        $cycle    = $CycleModel->getCycleById($id_cycle);
        $class    = $ClassModel->getClassById($id_class);
        $school   = $SchoolModel->getIDSchool($id_school);

        if (sizeof($student) == 0) {
            return array();
        }

        $data_final = [
            "student_id"        => $student[0]["student_id"],
            "matricule"         => $student[0]["matricule"],
            "name"              => $student[0]["name"],
            "surname"           => $student[0]["surname"],
            "date_birth"        => $student[0]["birth_place"],
            "place_birth"       => $student[0]["date_of_birth"],
            "sexe"              => $student[0]["sexe"],
            "photo"             => $student[0]["photo"],
            "id_parent"         => $student[0]["parent_id"],
            "name_parent"       => $student[0]["name_parent"],
            "surname_parent"    => $student[0]["surnameParent"],
            "email_parent"      => $student[0]["emailParent"],
            "parent_occupation" => $student[0]["professionParent"],
            "tel_parent"        => $student[0]["contactParent"],
            "adresse_parent"    => $student[0]["adresseParent"],
            "id_session"        => $session[0]["session_id"],
            "name_session"      => $session[0]["name_session"],
            "id_school"         => $school[0]["school_id"],
            "name_school"       => $school[0]["name"],
            "id_cycle"          => $cycle[0]["cycle_id"],
            "name_cycle"        => $cycle[0]["name_cycle"],
            "id_class"          => $class[0]["class_id"],
            "name_class"        => $class[0]["name"]
        ];

        return $data_final;
    }

    public function insert_photo(){
        $rules = [
            'name_school'   => [
                'rules'         => 'required'
            ],
            'name_session'  => [
                'rules'         => 'required'
            ],
            'name_cycle'    => [
                'rules'         => 'required'
            ],
            'name_classe'   => [
                'rules'         => 'required'
            ],
            'student_id'    => [
                'rules'         => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            
            $name_school     = $this->request->getvar('name_school');
            $name_session    = $this->request->getvar('name_session');
            $name_cycle      = $this->request->getvar('name_cycle');
            $name_classe     = $this->request->getvar('name_classe');
            $student_id      = $this->request->getvar('student_id');
            $photo_student   = $this->request->getFileMultiple('photo_student');
            $StudentModel    = new StudentModel();
            $YearModel       = new YearModel();
            $yearActif       = $YearModel->getYearActif();
            $year_id         = $yearActif[0]['year_id'];

            for ($j=0; $j < sizeof($photo_student); $j++) { 
                $one_photo = $photo_student[$j];
                $id_etu    = $student_id[$j];
                $name_file = $one_photo->getName();
                if ($name_file != "") {
                    /*====================== IMPORT PHOTO ======================*/
                    $temp_photo = explode(".",$name_file);
                    $new_photo_name = $id_etu.'_'.round(microtime(true)) . '.' . end($temp_photo);
                    $dbHost = getenv('FILE_PHOTO_STUDENT');
                    if (end($temp_photo) =="jpg" || end($temp_photo) =="JPG" || end($temp_photo) == "png" || end($temp_photo) == "PNG" || end($temp_photo) == "jpeg" || end($temp_photo) == "JPEG") {
                        
                        $verdic = $one_photo->move($dbHost, $new_photo_name);
                        if ($verdic) {
                            $data_student = [
                                "photo"      => $new_photo_name,
                                "updated_at" => date("Y-m-d H:m:s")
                            ]; 

                            $StudentModel->where('student_id', $id_etu)->set($data_student)->update();
                        }
                    }else{
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Echec",
                            "msg"     => 'Erreur a la ligne '.($j+1).', le fichier sélectionner n\'est pas une image'
                        ];
                        return $this->respond($response);
                    }
                    
                }

            }

            // insertion reussir
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'La modification a bien été éffectuer',
            ];
            return $this->respond($response);

        }else {
            //validation failed
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                'error'   => $this->validator->getErrors(),
                'msg'     => "Echec de validation",
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

    public function import_student(){
        $StudentModel = new StudentModel();

        // validation du formulaire 
        $rules = [
            'name_school'       => [
                'rules'             => 'required'
            ],
            'session'           => [
                'rules'             => 'required'
            ],
            'cycle'             => [
                'rules'             => 'required'
            ],
            'classe'            => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            $name_school    = $this->request->getvar('name_school');
            $session        = $this->request->getvar('session');
            $cycle          = $this->request->getvar('cycle');
            $user_id        = $this->request->getvar('user_id');
            $classe         = $this->request->getvar('classe');
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

            if (sizeof($sheet_data[0]) < 13) {
                $data = [
                     "status"    => 500,
                     "success"   => false,
                     "title"     => "Attention",
                     "alert"     => "warning",
                     'msg'       => "Vérifier le nombre de colonnes du fichier, taille demander 13 colonnes"
                 ];
 
                 return $this->respond($data);
             }

            $SchoolModel            = new SchoolModel();
            $ClassModel             = new ClassModel();
            $SessionModel           = new SessionModel();
            $CycleModel             = new CycleModel();
            $YearModel              = new YearModel();
            $ParentModel            = new ParentModel();
            $StudentClassModel      = new StudentClassModel();
            $StudentCycleModel      = new StudentCycleModel();
            $StudentSchoolModel     = new StudentSchoolModel();
            $StudentSessionModel    = new StudentSessionModel();
            $InscriptionModel       = new InscriptionModel();

            $data_school            = $SchoolModel->findAllSchoolByidSchool($name_school);
            $data_session           = $SessionModel->getSessionById($session);
            $data_cycle             = $CycleModel->getCycleById($cycle);
            $data_classe            = $ClassModel->getClassById($classe);

            if (sizeof($data_school) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette école n\'existe pas',
                ];
                return $this->respond($response);
            }
            if (sizeof($data_session) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette session n\'existe pas',
                ];
                return $this->respond($response);
            }
            if (sizeof($data_cycle) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Ce cycle n\'existe pas',
                ];
                return $this->respond($response);
            }
            if (sizeof($data_classe) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette classe n\'existe pas',
                ];
                return $this->respond($response);
            }

            foreach($sheet_data as $key => $val) {
                if($key != 0 && $val[1] != NULL && $val[2]) {
                    $nom = "";
                    $prenom = "";
                    $date_naiss = "";
                    $lieu_naiss = "";
                    $sexe = "";
                    $name_parent = "";
                    $prenom_parent = "";
                    $email = "";
                    $profession ="";
                    $contact = "";
                    $adresse ="";
                    $inscription = "";

                    if ($val[1] != NULL) {
                        $nom = $val[1];
                    }
                    if ($val[2] != NULL) {
                        $prenom = $val[2];
                    }
                    if ($val[3] != NULL) {
                        $date_naiss = $val[3];
                    }
                    if ($val[4] != NULL) {
                        $lieu_naiss = $val[4];
                    }
                    if ($val[5] != NULL) {
                        if ($val[4] == 'M' || $val[4] == 'F') {
                            $sexe = $val[5];
                        }
                    }
                    if ($val[6] != NULL) {
                        $name_parent = $val[6];
                    }
                    if ($val[7] != NULL) {
                        $prenom_parent = $val[7];
                    }
                    if ($val[8] != NULL) {
                        $email = $val[8];
                    }
                    if ($val[9] != NULL) {
                        $profession = $val[9];
                    }
                    if ($val[10] != NULL) {
                        $contact = $val[10];
                    }
                    if ($val[11] != NULL) {
                        $adresse = $val[11];
                    }
                    if ($val[12] != NULL) {
                        $inscription = $val[12];
                    }

                    $data_student = $StudentModel->getStudentExist($nom, $prenom, $date_naiss, $lieu_naiss);
                    if (sizeof($data_student) == 0) {
                        $student = $StudentModel->findAll(); 
                        // generate matricule
                        $matricule = "CM-".$data_school[0]["code"]."-".date("Y")."-".$data_classe[0]['number']."-".(sizeof($student)+1);
                        
                        $yearActif = $YearModel->getYearActif();

                        if (sizeof($yearActif) == 0) {
                            $response = [
                                "success" => false,
                                "status"  => 500,
                                "code"    => "error",
                                "title"   => "Erreur",
                                'msg'     => 'L\'année académique est mal programmer',
                            ];
                            return $this->respond($response);
                        }

                        // prepare parent
                        $data_parent = [
                            'name_parent'       => $name_parent,
                            'surnameParent'     => $prenom_parent,
                            'emailParent'       => $email,
                            'professionParent'  => $profession,
                            'contactParent'     => $contact,
                            'id_user'           => $user_id,
                            'adresseParent'     => $adresse,
                            'etat_parent'       => 'actif',
                            'status_parent'     => 0,
                            'created_at'        => date("Y-m-d H:m:s"),
                            'updated_at'        => date("Y-m-d H:m:s")
                        ];  

                        if ($ParentModel->save($data_parent)) {
                            // getId parent
                            $parent_id = $ParentModel->getId();
                            
                            $data = [
                                'surname'       => $prenom,
                                'name'          => $nom,
                                'birth_place'   => $lieu_naiss,
                                'date_of_birth' => $date_naiss,
                                'photo'         => getenv('USER_PROFIL'),
                                'nationality'   => 'camerounais',
                                'sexe'          => $sexe,
                                'id_user'       => $user_id,
                                'matricule'     => $matricule,
                                'status_student'=> 0,
                                'etat_student'  => 'actif',
                                'parent_id'     => $parent_id,
                                'year_id'       => $yearActif[0]['year_id'],
                                'created_at'    => date("Y-m-d H:m:s"),
                                'updated_at'    => date("Y-m-d H:m:s"),
                            ];
                    
                            if ($StudentModel->save($data)) {
                                
                                // getId student
                                $student_id = $ParentModel->getId();
                                
                                // if exist -- student_class 
                                $student_class = $StudentClassModel->getStudentClassExist($student_id, $classe, $yearActif[0]['year_id']);
                                if (sizeof($student_class) == 0) {
                                
                                    // insert student_class
                                    $data_student_class = [
                                        'class_id'          => $classe,
                                        'student_id'        => $student_id,
                                        'year_id'           => $yearActif[0]['year_id'],
                                        'id_user'           => $user_id,
                                        'status_stu_class'  => 0,
                                        'etat_stu_class'    => 'actif',
                                        'created_at'        => date("Y-m-d H:m:s"),
                                        'updated_at'        => date("Y-m-d H:m:s"),
                                    ];

                                    if ($StudentClassModel->save($data_student_class)) {
                                        // if exist student-cycle
                                        $student_cycle = $StudentCycleModel->getStudentCycleExist($student_id, $cycle, $yearActif[0]['year_id']);
                                        if (sizeof($student_cycle) == 0) {
                                             // insert cycle
                                            $data_student_cycle = [
                                                'cycle_id'          => $cycle,
                                                'student_id'        => $student_id,
                                                'year_id'           => $yearActif[0]['year_id'],
                                                'id_user'           => $user_id,
                                                'status_stu_cycle'  => 0,
                                                'etat_stu_cycle'    => 'actif',
                                                'created_at'        => date("Y-m-d H:m:s"),
                                                'updated_at'        => date("Y-m-d H:m:s"),
                                            ];
                                            if ($StudentCycleModel->save($data_student_cycle)) {
                                                // if exist student-school
                                                $student_school = $StudentSchoolModel->getStudentSchoolExist($student_id, $name_school, $yearActif[0]['year_id']);
                                                if (sizeof($student_school) == 0) {
                                                    // insert student school
                                                    $data_student_school = [
                                                        'school_id'         => $name_school,
                                                        'student_id'        => $student_id,
                                                        'year_id'           => $yearActif[0]['year_id'],
                                                        'id_user'           => $user_id,
                                                        'status_stu_scho'   => 0,
                                                        'etat_stu_scho'     => 'actif',
                                                        'created_at'        => date("Y-m-d H:m:s"),
                                                        'updated_at'        => date("Y-m-d H:m:s"),
                                                    ];

                                                    if ($StudentSchoolModel->save($data_student_school)) {
                                                        // if exist student-session
                                                        $student_session = $StudentSessionModel->getStudentSessionExist($student_id, $session, $yearActif[0]['year_id']);
                                                        if (sizeof($student_session) == 0) {
                                                            // insert student session
                                                            $data_student_session = [
                                                                'session_id'        => $session,
                                                                'student_id'        => $student_id,
                                                                'year_id'           => $yearActif[0]['year_id'],
                                                                'id_user'           => $user_id,
                                                                'status_stu_sess'   => 0,
                                                                'etat_stu_sess'     => 'actif',
                                                                'created_at'        => date("Y-m-d H:m:s"),
                                                                'updated_at'        => date("Y-m-d H:m:s"),
                                                            ];

                                                            if ($StudentSessionModel->save($data_student_session)) {
                                                                 // insert inscription
                                                                if ($inscription != "" && $inscription != 0 && $inscription != NULL ) {
                                                                    $data_inscript = [
                                                                        'id_user'       =>  $user_id,
                                                                        'class_id'      =>  $classe,
                                                                        'student_id'    =>  $student_id,
                                                                        'amount'        =>  $inscription,
                                                                        'status_ins'    =>  0,
                                                                        'etat_ins'      =>  'actif',
                                                                        'created_at'    =>  date("Y-m-d H:m:s"),
                                                                        'updated_at'    =>  date("Y-m-d H:m:s")
                                                                    ];
                                                                    $InscriptionModel->save($data_inscript);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
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

        }else {
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

    #@-- 1 --> insertion des eleves
    #- use:
    #-
    public function insertstudent()
    {
       $StudentModel = new StudentModel();
       $HistorySession = new HistorySession();

        // validation du formulaire 
        $rules = [
            'name'              => [
                'rules'             => 'required|max_length[35]'
            ],
            'date'              => [
                'rules'             => 'required'
            ],
            'placeBirth'        => [
                'rules'             => 'required'
            ],
            'sexe'              => [
                'rules'             => 'required'
            ],
            'nameParent'        => [
                'rules'             => 'required'
            ],
            'phone'             => [
                'rules'             => 'required'
            ],
            'name_school'       => [
                'rules'             => 'required'
            ],
            'session'           => [
                'rules'             => 'required'
            ],
            'cycle'             => [
                'rules'             => 'required'
            ],
            'classe'            => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

         // session
         $data_session = $HistorySession-> getInfoSession();
         $id_user   = $data_session['id_user'];
         $type_user = $data_session['type_user'];
         $login     = $data_session['login'];
         $password  = $data_session['password'];

        if ($this->validate($rules)) {
            $name                   = $this->request->getvar('name');
            $surName                = $this->request->getvar('surName');
            $date                   = $this->request->getvar('date');
            $placeBirth             = $this->request->getvar('placeBirth');
            $sexe                   = $this->request->getvar('sexe');
            $nameParent             = $this->request->getvar('nameParent');
            $surnameParent          = $this->request->getvar('surnameParent');
            $email_parent           = $this->request->getvar('email_parent');
            $profession             = $this->request->getvar('profession');
            $phone                  = $this->request->getvar('phone');
            $adresse_parent         = $this->request->getvar('adresse_parent');
            $name_school            = $this->request->getvar('name_school');
            $session                = $this->request->getvar('session');
            $cycle                  = $this->request->getvar('cycle');
            $classe                 = $this->request->getvar('classe');
            $logo                   = $this->request->getFile('logo');
            $user_id                = $this->request->getvar('user_id');
            $inscription            = $this->request->getvar('inscription');
        
            $SchoolModel            = new SchoolModel();
            $ClassModel             = new ClassModel();
            $SessionModel           = new SessionModel();
            $CycleModel             = new CycleModel();
            $YearModel              = new YearModel();
            $ParentModel            = new ParentModel();
            $StudentClassModel      = new StudentClassModel();
            $StudentCycleModel      = new StudentCycleModel();
            $StudentSchoolModel     = new StudentSchoolModel();
            $StudentSessionModel    = new StudentSessionModel();
            $InscriptionModel       = new InscriptionModel();
            
            $data_school            = $SchoolModel->findAllSchoolByidSchool($name_school);
            $data_session           = $SessionModel->getSessionById($session);
            $data_cycle             = $CycleModel->getCycleById($cycle);
            $data_classe            = $ClassModel->getClassById($classe);

            $data_student = $StudentModel->getStudentExist($name, $surName, $date, $placeBirth);

            if (sizeof($data_school) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette école n\'existe pas',
                ];

                // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Cette école n'existe pas ");
                return $this->respond($response);
            }
            if (sizeof($data_session) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette session n\'existe pas',
                ];
                // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Cette session n'existe pas ");
                return $this->respond($response);
            }
            if (sizeof($data_cycle) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Ce cycle n\'existe pas',
                ];
                // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Ce cycle n'existe pas ");
                return $this->respond($response);
            }
            if (sizeof($data_classe) == 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cette classe n\'existe pas',
                ];
                // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Cette classe n'existe pas ");
                return $this->respond($response);
            }
            
            if (sizeof($data_student) != 0) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Cet élève existe déjà',
                ];
                // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Cet école élève existe déjà ");
                return $this->respond($response);

            } else {
                /*====================== IMPORT PHOTO ======================*/
                $name_logo = $logo->getName();
                // Renaming file before upload
                $temp_logo = explode(".",$name_logo);
                $new_logo_name = round(microtime(true)) . '.' . end($temp_logo);
                $dbHost = getenv('FILE_PHOTO_STUDENT');
                $verdic = $logo->move($dbHost, $new_logo_name);

                if (!$verdic) {
                    // failed insert
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        'msg'     => 'echec d\'insertion de la photo',
                    ];
                   // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "echec d'insertion de la photo ");
                    return $this->respond($response);

                }else{
                    $student = $StudentModel->findAll(); 
                    // generate matricule
                    $matricule = "CM-".$data_school[0]["code"]."-".date("Y")."-".$data_classe[0]['number']."-".(sizeof($student)+1);
                    $yearActif = $YearModel->getYearActif();

                    if (sizeof($yearActif) == 0) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => 'L\'année académique est mal programmer',
                        ];
                        // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "L'année académique est mal programmer ");
                        return $this->respond($response);
                    }

                    // prepare parent
                    $data_parent = [
                        'name_parent'       => $nameParent,
                        'surnameParent'     => $surnameParent,
                        'emailParent'       => $email_parent,
                        'professionParent'  => $profession,
                        'contactParent'     => $phone,
                        'id_user'           => $user_id,
                        'adresseParent'     => $adresse_parent,
                        'etat_parent'       => 'actif',
                        'status_parent'     => 0,
                        'created_at'        => date("Y-m-d H:m:s"),
                        'updated_at'        => date("Y-m-d H:m:s")
                    ];  

                    if (!$ParentModel->save($data_parent)) {
                        // failed insert
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => 'Echec d\'insertion',
                        ];
                        // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Echec d'insertion");
                        return $this->respond($response);

                    }else{

                        // getId parent
                        $parent_id = $ParentModel->getId();
                        
                        $data = [
                            'surname'       => $surName,
                            'name'          => $name,
                            'birth_place'   => $placeBirth,
                            'date_of_birth' => $date,
                            'photo'         => $new_logo_name,
                            'nationality'   => 'camerounais',
                            'sexe'          => $sexe,
                            'id_user'       => $user_id,
                            'matricule'     => $matricule,
                            'status_student'=> 0,
                            'etat_student'  => 'actif',
                            'parent_id'     => $parent_id,
                            'year_id'       => $yearActif[0]['year_id'],
                            'created_at'    => date("Y-m-d H:m:s"),
                            'updated_at'    => date("Y-m-d H:m:s"),
                        ];
                        if ($StudentModel->save($data)) {
                            
                            // getId student
                            $student_id = $ParentModel->getId();
                            
                            // if exist -- student_class 
                            $student_class = $StudentClassModel->getStudentClassExist($student_id, $classe, $yearActif[0]['year_id']);
                            if (sizeof($student_class) != 0) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Cet élève est déjà inscrire dans cette classe',
                                ];
                                return $this->respond($response);
                            }
                            // insert student_class
                            $data_student_class = [
                                'class_id'          => $classe,
                                'student_id'        => $student_id,
                                'year_id'           => $yearActif[0]['year_id'],
                                'id_user'           => $id_user,
                                'status_stu_class'  => 0,
                                'etat_stu_class'    => 'actif',
                                'created_at'        => date("Y-m-d H:m:s"),
                                'updated_at'        => date("Y-m-d H:m:s"),
                            ];

                            if (!$StudentClassModel->save($data_student_class)) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Nous n\'avons pas pu affecter cet élève à cette classe',
                                ];
                                return $this->respond($response);
                            }
                            // if exist student-cycle
                            $student_cycle = $StudentCycleModel->getStudentCycleExist($student_id, $cycle, $yearActif[0]['year_id']);
                            if (sizeof($student_cycle) != 0) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Cet élève est déjà inscrire dans ce cycle',
                                ];
                                return $this->respond($response);
                            }
                            // insert cycle
                            $data_student_cycle = [
                                'cycle_id'          => $cycle,
                                'student_id'        => $student_id,
                                'year_id'           => $yearActif[0]['year_id'],
                                'id_user'           => $id_user,
                                'status_stu_cycle'  => 0,
                                'etat_stu_cycle'    => 'actif',
                                'created_at'        => date("Y-m-d H:m:s"),
                                'updated_at'        => date("Y-m-d H:m:s"),
                            ];

                            if (!$StudentCycleModel->save($data_student_cycle)) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Nous n\'avons pas pu affecter cet élève à ce cycle',
                                ];
                                return $this->respond($response);
                            }

                            // if exist student-school
                            $student_school = $StudentSchoolModel->getStudentSchoolExist($student_id, $name_school, $yearActif[0]['year_id']);
                            if (sizeof($student_school) != 0) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Cet élève est déjà inscrire dans cette école',
                                ];
                                return $this->respond($response);
                            }
                            // insert student school
                            $data_student_school = [
                                'school_id'         => $name_school,
                                'student_id'        => $student_id,
                                'year_id'           => $yearActif[0]['year_id'],
                                'id_user'           => $id_user,
                                'status_stu_scho'   => 0,
                                'etat_stu_scho'     => 'actif',
                                'created_at'        => date("Y-m-d H:m:s"),
                                'updated_at'        => date("Y-m-d H:m:s"),
                            ];

                            if (!$StudentSchoolModel->save($data_student_school)) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Nous n\'avons pas pu affecter cet élève à cett école',
                                ];
                                return $this->respond($response);
                            }

                             // if exist student-session
                             $student_session = $StudentSessionModel->getStudentSessionExist($student_id, $session, $yearActif[0]['year_id']);
                             if (sizeof($student_session) != 0) {
                                 $response = [
                                     "success" => false,
                                     "status"  => 500,
                                     "code"    => "error",
                                     "title"   => "Erreur",
                                     'msg'     => 'Cet élève est déjà inscrire dans cette session',
                                 ];
                                 return $this->respond($response);
                             }
                             // insert student session
                             $data_student_session = [
                                 'session_id'        => $session,
                                 'student_id'        => $student_id,
                                 'year_id'           => $yearActif[0]['year_id'],
                                 'id_user'           => $id_user,
                                 'status_stu_sess'   => 0,
                                 'etat_stu_sess'     => 'actif',
                                 'created_at'        => date("Y-m-d H:m:s"),
                                 'updated_at'        => date("Y-m-d H:m:s"),
                             ];
 
                            if (!$StudentSessionModel->save($data_student_session)) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    'msg'     => 'Nous n\'avons pas pu affecter cet élève à cette session',
                                ];
                                return $this->respond($response);
                            }

                            // insert inscription
                            if ($inscription != "" && $inscription != 0 && $inscription != NULL ) {
                                $data_inscript = [
                                    'id_user'       =>  $id_user,
                                    'class_id'      =>  $classe,
                                    'student_id'    =>  $student_id,
                                    'amount'        =>  $inscription,
                                    'status_ins'    =>  0,
                                    'etat_ins'      =>  'actif',
                                    'created_at'    =>  date("Y-m-d H:m:s"),
                                    'updated_at'    =>  date("Y-m-d H:m:s")
                                ];
                                $InscriptionModel->save($data_inscript);
                            }
                             
                            // success insert
                            $response = [
                                'success' => true,
                                'status'  => 200,
                                "code"    => "success",
                                "title"   => "Réussite",
                                'msg'     => 'Insertion reussir',
                            ];
                           // history
                        $donnee = $data["surname"].",".$data["name"].",".$data["birth_place"].",".$data["date_of_birth"].",".$data["photo"].",".$data["nationality"].",".$data["sexe"].",".$data["id_user"].",".$data["matricule"].",".$data["status_student"].",".$data["etat_student"].",".$name_school.",".$classe.",".$session.",".$parent_id.",".$cycle.",".$yearActif[0]['year_id'].",".$data["created_at"].",".$data["updated_at"];

                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Réussite", "Eleve", "", "", $donnee);
                        return $this->respond($response);
                        }
                        else{
                            // failed insert
                            $response = [
                                "success" => false,
                                "status"  => 500,
                                "code"    => "error",
                                "title"   => "Erreur",
                                'msg'     => 'Echec insertion',
                            ];
                            // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Echec insertion");
                        return $this->respond($response);
                        }
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
                "msg"     => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "insertion", "Echec", "Eleve", "", "", "Echec de validation ");
            return $this->respond($response); 
        }
    }

    public function liste_student($id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];

        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id);

        return $this->respond($student_list);

    }

    public function delete_user($id_student){
        $StudentModel   = new StudentModel();
        $HistorySession = new HistorySession();

        // session
        $data_session   = $HistorySession->getInfoSession();
        $id_user        = $data_session['id_user'];
        $type_user      = $data_session['type_user'];
        $login          = $data_session['login'];
        $password       = $data_session['password'];

        $data = [
            "etat_student"   => "inactif",
            "status_student" => 1,
            "deleted_at"     => date("Y-m-d H:m:s")
        ];

        if ($StudentModel->where('student_id', $id_student)->set($data)->update() === false) {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => 'Echec de suppression de l\'élève',
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Elève", "", "", "Echec de suppression de l'élève ");
            return $this->respond($response);
        }else{
            $response = [
                "success" => true,
                "status"  => 200,
                "code"    => "success",
                "title"   => "Réussite",
                "msg"     => 'Opération de suppression réussite réussite',
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Suppression", "Echec", "Elève", "", "", "Echec de suppression de l'élève ");
            return $this->respond($response);
        }
    }

    #@-- 1 --> modification des eleves
    #- use:
    #-

    public function updatestudent()
    {
       $StudentModel = new StudentModel();
       $HistorySession = new HistorySession();

        // validation du formulaire 
        $rules = [
            'idStudent'         => [
                'rules'             => 'required'
            ],
            'name'              => [
                'rules'             => 'required|max_length[35]'
            ],
            'date'              => [
                'rules'             => 'required'
            ],
            'placeBirth'        => [
                'rules'             => 'required'
            ],
            'sexe'              => [
                'rules'             => 'required'
            ],
            'nameParent'        => [
                'rules'             => 'required'
            ],
            'idParent'          => [
                'rules'             => 'required'
            ],
            'phone'             => [
                'rules'             => 'required'
            ],
            'name_school'       => [
                'rules'             => 'required'
            ],
            'session'           => [
                'rules'             => 'required'
            ],
            'cycle'             => [
                'rules'             => 'required'
            ],
            'classe'            => [
                'rules'             => 'required'
            ],
            'user_id'           => [
                'rules'             => 'required'
            ]
        ];

        // session
        $data_session   = $HistorySession->getInfoSession();
        $id_user        = $data_session['id_user'];
        $type_user      = $data_session['type_user'];
        $login          = $data_session['login'];
        $password       = $data_session['password'];

        if ($this->validate($rules)) {
            $student_id     = $this->request->getvar('idStudent');
            $name           = $this->request->getvar('name');
            $surName        = $this->request->getvar('surName');
            $date           = $this->request->getvar('date');
            $placeBirth     = $this->request->getvar('placeBirth');
            $sexe           = $this->request->getvar('sexe');
            $nameParent     = $this->request->getvar('nameParent');
            $surnameParent  = $this->request->getvar('surnameParent');
            $email_parent   = $this->request->getvar('email_parent');
            $profession     = $this->request->getvar('profession');
            $phone          = $this->request->getvar('phone');
            $adresse_parent = $this->request->getvar('adresse_parent');
            $name_school    = $this->request->getvar('name_school');
            $session        = $this->request->getvar('session');
            $cycle          = $this->request->getvar('cycle');
            $classe         = $this->request->getvar('classe');
            $logo           = $this->request->getFile('logo');
            $user_id        = $this->request->getvar('user_id');
            $idParent       = $this->request->getvar('idParent');
        
            $SchoolModel    = new SchoolModel();
            $ClassModel     = new ClassModel();
            $SessionModel   = new SessionModel();
            $CycleModel     = new CycleModel();
            $YearModel      = new YearModel();
            $ParentModel    = new ParentModel();
            
            $data_school    = $SchoolModel->findAllSchoolByidSchool($name_school);
            $data_session   = $SessionModel->getSessionById($session);
            $data_cycle     = $CycleModel->getCycleById($cycle);
            $data_classe    = $ClassModel->getClassById($classe);
           
            $yearActif      = $YearModel->getYearActif();
            $id_year        = $yearActif[0]["year_id"];

            //-- update parent
            $data_parent = [
                'name_parent'       => $nameParent,
                'surnameParent'     => $surnameParent,
                'emailParent'       => $email_parent,
                'professionParent'  => $profession,
                'contactParent'     => $phone,
                'adresseParent'     => $adresse_parent,
                'updated_at'        => date("Y-m-d H:m:s")
            ];

            if ($ParentModel->where('parent_id', $idParent)->set($data_parent)->update() === false) {
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    "msg"     => 'Echec de modification des informations du parent',
                ];
                // history
                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Parent", "", "", "Echec de modification des informations du parent ");
                return $this->respond($response);
            }else{
                $student_get = $StudentModel->getOneStudent($student_id);
                $file_name = $student_get[0]["photo"];
                if ($logo != NULL) {
                    /*====================== IMPORT PHOTO ======================*/
                    $name_logo = $logo->getName();
                    // Renaming file before upload
                    $temp_logo = explode(".",$name_logo);
                    $new_logo_name = round(microtime(true)) . '.' . end($temp_logo);
                    $dbHost = getenv('FILE_PHOTO_STUDENT');
                    $verdic = $logo->move($dbHost, $new_logo_name);
                    
                    if (!$verdic) {
                        // failed insert
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => 'Echec d\'importation de la photo',
                        ];
                       // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Eleve", "", "", "echec d'importation de la photo");
                        return $this->respond($response);
                    }else {
                        $file_name = $new_logo_name;
                    }
                    
                }
                // update student
                $data_student = [
                    'surname'       => $surName,
                    'name'          => $name,
                    'birth_place'   => $placeBirth,
                    'date_of_birth' => $date,
                    'photo'         => $file_name,
                    'nationality'   => 'camerounais',
                    'sexe'          => $sexe,
                    'id_user'       => $user_id,
                    'status_student'=> 0,
                    'etat_student'  => 'actif',
                    'updated_at'    => date("Y-m-d H:m:s"),
                ];
                if ($StudentModel->where('student_id', $student_id)->set($data_student)->update() === false) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => 'Echec de modification des informations de l\'élève',
                    ];
                    // history
                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Elève", "", "", "Echec de modification des informations de l'élève ");
                    return $this->respond($response);
                }else{
                    //-- update student school
                    $data_student_school = [
                        "school_id"  => $name_school,
                        'updated_at' => date("Y-m-d H:m:s")
                    ];
                    if (!$SchoolModel->update_student_school($data_student_school, $student_id, $id_year)) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            "msg"     => 'Echec de modification des informations de l\'école',
                        ];
                        // history
                        $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Ecole-Eleve", "", "", "Echec de modification des informations de l'école ");
                        return $this->respond($response);
                    }else {
                        //-- update student session
                        $data_student_session = [
                            "session_id" => $session,
                            'updated_at' => date("Y-m-d H:m:s")
                        ];
                        if (!$SchoolModel->update_student_session($data_student_session, $student_id, $id_year)) {
                            $response = [
                                "success" => false,
                                "status"  => 500,
                                "code"    => "error",
                                "title"   => "Erreur",
                                "msg"     => 'Echec de modification des informations de l\'école',
                            ];
                            // history
                            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Session-eleve", "", "", "Echec de modification des informations de l'école ");
                            return $this->respond($response);
                        }else {
                            //-- update student cycle
                            $data_student_cycle = [
                                "cycle_id"   => $cycle,
                                'updated_at' => date("Y-m-d H:m:s")
                            ];
                            if (!$SchoolModel->update_student_cycle($data_student_cycle, $student_id, $id_year)) {
                                $response = [
                                    "success" => false,
                                    "status"  => 500,
                                    "code"    => "error",
                                    "title"   => "Erreur",
                                    "msg"     => 'Echec de modification des informations de l\'école',
                                ];
                                // history
                                $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Cycle-eleve", "", "", "Echec de modification des informations de l'école ");
                                return $this->respond($response);
                            }else {
                                // update student class
                                $data_student_class = [
                                    "class_id"   => $classe,
                                    'updated_at' => date("Y-m-d H:m:s")
                                ];
                                if (!$SchoolModel->update_student_class($data_student_class, $student_id, $id_year)) {
                                    $response = [
                                        "success" => false,
                                        "status"  => 500,
                                        "code"    => "error",
                                        "title"   => "Erreur",
                                        "msg"     => 'Echec de modification des informations de l\'école',
                                    ];
                                    // history
                                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Class-eleve", "", "", "Echec de modification des informations de l'école ");
                                    return $this->respond($response);
                                }else {
                                    $data_info = $this->get_one_student_return_data($student_id, $session, $cycle, $classe, $name_school);
                                    $response = [
                                        "success" => true,
                                        "status"  => 200,
                                        "code"    => "Success",
                                        "title"   => "Réussite",
                                        "msg"     => 'Modification réussir',
                                        "data"    => $data_info
                                    ];
                                   // history
                                    $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Réussite", "Eleve", "", "", "Modification réussir");
                                    return $this->respond($response);
                                }
                            }

                        }
                    }
                }
            }
        }else {
            $response = [
                "success" => false,
                "status"  => 500,
                "code"    => "error",
                "title"   => "Erreur",
                "msg"     => "Echec de validation des champs, informations manquantes",
                "error"   => $this->validator->getErrors(),
            ];
            // history
            $HistorySession->ReadOperation($id_user, $login, $type_user, "", "Modification", "Echec", "Eleve", "", "", "Echec de validation ");
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

    public function print_list_class($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id, "non");
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('L', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
        $title = "LISTE DES ELEVES DE LA CLASSE DE ".strtoupper($name_class);
		$fpdf->header_p(($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Liste des élèves du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves
		$fpdf->listing($title, $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Liste_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_list_redouble($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexe($id_class, $year_id, "M", "oui"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexe($id_class, $year_id, "F", "oui"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYearRedouble($id_class, $year_id, "oui");
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('L', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
        $title = "LISTE DES ELEVES REDOUBLANTS DE LA CLASSE DE ".strtoupper($name_class);
		$fpdf->header_p($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Liste des élèves du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves
		$fpdf->listing($title, $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Liste_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_list_new($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeNew($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeNew($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYearNew($id_class, $year_id);
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('L', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
        $title = "LISTE DES NOUVEAUX ELEVES DE LA CLASSE DE ".strtoupper($name_class);
		$fpdf->header_p($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Liste des élèves du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves
		$fpdf->listing($title, $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Liste_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[1]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
    
    public function print_fiche_pres($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id);
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
        $title = "FICHE DE PRÉSENCE DES ÉLÈVES DU ".strtoupper($name_class);
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Fiche de présence des élèves du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing_portrait(38, $msg); 
		//-- listing des eleves
		$fpdf->fiche_presence($title, $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Fiche_presence_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_fiche_decharge($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id);
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Fiche de décharge des élèves du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves
		$fpdf->fiche_decharge($eleves, $garcon, $fille, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $name_class, $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Fiche_decharge_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_fiche_inscrit($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAllInscrit($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAllInscrit($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYearInscrit($id_class, $year_id);
        
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
                'amount' 	    => $row["total"],
                'date' 	        => $row["date"],
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Liste des élèves inscrits du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves inscrit
		$fpdf->listing_inscrit("", $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Liste_inscription_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
    
    public function print_fiche_not_inscrit($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAllInscritNot($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAllInscritNot($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYearAllInscritNot($id_class, $year_id);
       
        $name_folder = getenv('FILE_PRINT_DOC');
        $i = 0;
        $eleves = array();
        foreach ($student_list as $row) {
            $eleves[] = array(
                'num' 			=> $i+1,
                'mat' 			=> strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],
                'amount' 	    => "",
                'date' 	        => "",
            );
            $i++;
        }

        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1); 
		$fpdf->AddPage();
        //-- fil
		$fpdf->Filigramme("School");
		//-- entete
		$fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
		//-- footer
        $msg = "Liste des élèves non inscrits du ".$cycle[0]['name_cycle'].", session ".$session[0]['name_session']." de la classe du ".$name_class;
		$fpdf->footer_listing(38, $msg); 
		//-- listing des eleves inscrit
		$fpdf->listing_not_inscrit("", $name_class, $session[0]['name_session'], $cycle[0]['name_cycle'], $eleves, $garcon, $fille, $chaine_ensg, ($tab_start_year[0].' / '.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule'], $msg);
		// /***********************/
		
		//-- sortie
        $name_file = $name_folder.'/Liste_not_inscription_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_certificat_class($ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
        $TeacherClassModel = new TeacherClassModel();
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]["year_id"];
        $start_year = $yearActif[0]["start_year"];
        $tab_start_year = explode('-', $yearActif[0]["start_year"]);
        $ecole = $SchoolModel->getIDSchool($ecole_id);
        $session = $SessionModel->getSessionById($session_id);
        $cycle = $CycleModel->getCycleById($cycle_id);
        $class = $ClassModel->getOneClass($id_class);
        $name_class = $ClassModel->format_name_class($class[0]['name']);
        $garcon = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "M"));
        $fille = sizeof($StudentModel->getStudentByClassYearSexeAll($id_class, $year_id, "F"));
        $enseignant = $TeacherClassModel->getTeacherClass($id_class, $year_id);
        $chaine_ensg = '';
        foreach ($enseignant as $line) {
            if (strlen($chaine_ensg) == 0) {
                $chaine_ensg = $line['name'];
            }else {
                $chaine_ensg = $chaine_ensg.', '.$chaine_ensg;
            }
        }
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id, "non");
        $name_folder = getenv('FILE_PRINT_DOC');
        
        $fpdf  = new FPDF_LISTING('P', 'mm', 'A4');  
		$fpdf->AliasNbPages(); 
		$fpdf->SetAutoPageBreak(1, 1);

        foreach ($student_list as $row) {
            $fpdf->AddPage();
            //-- fil
            $fpdf->Filigramme("School");
            //-- entete
		    $fpdf->header_portrait($tab_start_year[0].' / '.($tab_start_year[0]+1), $ecole[0]['name'], $ecole[0]['phone'], $ecole[0]['matricule']);
            
            $eleves = array(
                'matricule'     => strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'parent' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'phone' 	    => $row["contactParent"],
                'redouble' 	    => $row["redouble"],

                'dateNaiss' 	=> $row["date_of_birth"],
                'lieuNaiss' 	=> $row["birth_place"],
                'pere' 			=> $row["name_parent"],
                'annee'			=> ($tab_start_year[0].'_'.($tab_start_year[0]+1)),
                'section'		=> $session[0]['name_session'],
                'cycle'			=> $cycle[0]['name_cycle'],
                'nomdirecteur'  => $ecole[0]['responsable']
            );

            //-- listing des eleves
            $fpdf->body_certifica($eleves, ($tab_start_year[0].'_'.($tab_start_year[0]+1)), $ecole[0]['name'], $ecole[0]['responsable']);
        }
        
		//-- sortie
        $name_file = $name_folder.'/Certificat_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
}
