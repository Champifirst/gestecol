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

include('History/HistorySession.php');
include('fpdf/fpdf.php');
include('report/FPDF_CERT.php');

class CertificatController extends ResourcePresenter
{
    use ResponseTrait;


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
        $student_list = $StudentModel->getStudentByClassYear($id_class, $year_id);
        $name_folder = getenv('FILE_PRINT_DOC');
        
        $fpdf  = new FPDF_CERT('P', 'mm', 'A4');  
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
                'parent' 	    => $row["sexe"].' '.$row["surnameParent"],
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

    public function print_certificat_class_one($student_id, $ecole_id, $session_id, $cycle_id, $id_class){
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
        
        // $qrCode = $this->generate_qrcode($class[0]["number"], $name_class, date("Y-m-d H:m:s"), $ecole[0]['name'], $ecole[0]['logo']);
        $student_list = $StudentModel->getOneStudentByClassYear($student_id, $id_class, $year_id);
        $name_folder = getenv('FILE_PRINT_DOC');
        
        $fpdf  = new FPDF_CERT('P', 'mm', 'A4');  
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
                'parent' 	    => $row["sexe"].' '.$row["surnameParent"],
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
        $name_file = $name_folder.'/Certificat_eleve_'.$student_list[0]['matricule'].'_'.$student_list[0]['name'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
}
