<?php

namespace App\Controllers;

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
use App\Controllers\fpdf;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

include('fpdf/fpdf.php');
include('report/FPDF_CARTE.php');

class PrintCarteControl extends ResourcePresenter
{
    use ResponseTrait;

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

    ############################################
    #               REPORTING                  #
    ############################################

    /*
    |-------------------------------------------------------------------
    | decoupage du nom
    |-------------------------------------------------------------------
    |
    | @param null   
    |
    */

    public function cut_out_the_name($chaine)
    {
        $data = array();
        $mot  = "";

        for ($i = 0; $i < strlen($chaine); $i++) {
            if ($chaine[$i] != " "  &&  $chaine[$i] != "/" && $chaine[$i] != "-") {
                $mot = $mot . "" . $chaine[$i];
            } else {
                if ($chaine[$i] == "-") {
                    $mot = $mot . "-";
                }

                $data[] = array(
                    'mot' => $mot
                );

                $mot = "";
            }
        }

        $final  = "";
        $taille = sizeof($data);

        for ($i = 0; $i < $taille; $i++) {
            $mot_array = $data[$i]["mot"];
            if (strlen($final) <= 21 && (strlen($mot_array) + (strlen($final))) < 21) {
                $final = $final . " " . $mot_array;
            } else {
                if ((strlen($mot_array) + (strlen($final))) >= 21) {
                    $reste = 21 - (strlen($final));

                    if ($reste > 0) {
                        $final = $final . " ";
                        for ($j = 0; $j < 1; $j++) {
                            $final = $final . "" . $mot_array[$j];
                        }
                        $final = $final . ".";
                    }
                    break;
                }
            }
        }

        return $final;
    }

    

    /************************************************************************************************************
     * 			IMPRESSION DES CARTES SCOLAIRE 2023   															*
     * 																											*
     * **********************************************************************************************************/

    public function print_carte_class($ecole_id, $session_id, $cycle_id, $id_class)
    {
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
        $folder_avart = getenv('FILE_PHOTO_STUDENT');
        $i = 0;
        $listing = array();
        foreach ($student_list as $row) {
            $listing[] = array(
                'num' 			=> $i+1,
                'matricule'     => strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'date_naiss' 	=> strtoupper($row["date_of_birth"]),
                'lieu_naiss' 	=> strtoupper($row["birth_place"]),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'session'       => $session[0]['name_session'],
                'cycle'         => $cycle[0]['name_cycle'],
                'tuteur' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'telephone' 	=> $row["contactParent"],
                'avatar' 	    => $folder_avart.'/'.$row["photo"],
                'school' 	    => $ecole[0]['name'],
                'logo' 	        => $ecole[0]['logo'],
                'code_school' 	=> $ecole[0]['code']
                
            );
            $i++;
        }

        //-- start pdf
        $fpdf = new PDF_CARTE();
        $fpdf->SetAutoPageBreak(1, 1);

        $postx = 9;
        $x = $postx;
        $posty = 3;
        $y = $posty;
        $j = 1;

        foreach ($listing as $prod) {
            $path = $prod['avatar'];

            if (file_exists($path)) {
                $name  = strlen($prod['nom']);
                $name1 = $prod['nom'];
                if ($name <= 18) {
                    $name1 = $name1;
                } else {
                    $rest = '';
                    for ($i = 0; $i <= 25; $i++) {
                        if (strlen($name) < 25) {
                            $rest = $name1;
                        } else {
                            $rest = $rest . $name1[$i];
                        }
                    }
                }

                if ($j % 10 == 1) {
                    $fpdf->AddPage();
                    $postx =  $x;
                    $posty =  $y;
                } elseif ($j % 5 == 1) {
                    $posty = $y;
                    $postx += 10 + 95;
                } else {
                    $posty += 55 + 3;
                }

                $name = $prod['nom'];
                // $lenght_name = strlen($prod['nom']);
                $prod['nom'] = $this->cut_out_the_name($name . "/");

                $name = $prod['lieu_naiss'];
                $lenght_name = strlen($prod['lieu_naiss']);
                if ($lenght_name <= 12) {
                    $prod['lieu_naiss'] = $name;
                } else {
                    $prod['lieu_naiss'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . "..";
                }

                $name = $prod['cycle'];
                $lenght_name = strlen($prod['cycle']);
                if ($lenght_name <= 21) {
                    $prod['cycle'] = $name;
                } else {
                    $prod['cycle'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . $name[11] . $name[12] . $name[13] . $name[14] . $name[15] . $name[16] . $name[17] . $name[18] . $name[19] . $name[20] . $name[21] . $name[22] . '..';
                }


                // reduction du nom du tuteur
                $name = $prod['tuteur'];
                $lenght_name_tuteur = strlen($prod['tuteur']);
                if ($lenght_name_tuteur <= 21) {
                    $prod['tuteur'] = $name;
                } else {
                    $prod['tuteur'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . $name[11] . $name[12] . $name[13] . $name[14] . $name[15] . $name[16] . $name[17] . $name[18] . $name[19] . $name[20];
                }
                
                $fpdf->Image(getenv('BG_CARTE'), $postx, $posty, 86, 52);

                $fpdf->Image(getenv('LOGO_CIRCLE'), $postx + 18 - 10 - 5, $posty + 2, 7 + 4, 7 + 4);
                $fpdf->Image(getenv('LOGO_CIRCLE'), $postx + 44 + 27 + 9 - 5 - 3, $posty + 2, 7 + 4, 7 + 4);
                $fpdf->SetFont('times', 'B', '9');
                $fpdf->SetXY($postx, $posty + 1);
                $fpdf->Cell(86, 5, utf8_decode('RÉPUBLIQUE DU CAMEROUN'), 0, 0, 'C');
                $fpdf->SetXY($postx, $posty + 5);
                $fpdf->SetFont('times', '', '7');
                $fpdf->Cell(86, 5, utf8_decode('Paix - Travail - Patrie'), 0, 0, 'C');
                $fpdf->SetFont('times', 'B', '11');
                $fpdf->SetXY($postx, $posty + 11);
                $fpdf->Cell(86 + 5, 5, utf8_decode("CARTE D'ÉLÈVE"), 0, 0, 'C');
                $fpdf->Image(getenv('CARDRE_PHOTO'), $postx + 2 + 5 - 4, $posty + 19 - 1, 20, 22);
                $fpdf->Image($prod['avatar'], $postx + 3 + 5 - 4, $posty + 20 - 1, 18.2, 26.2 - 3 - 2.9);

                // logo de la paix
                $fpdf->Image(getenv('CAMEROUN'), $postx + 2, $posty + 20 + 20.5, 8, 6);
                // qrCode
                // $chemin = $this->generate_qrcode($prod['matricule'], $prod['nom'], $prod['date_naiss'], $prod['school'], $prod['logo']);
                $fpdf->Image("images.png", $postx + 15, $posty + 19 + 21.5, 9, 7);

                $fpdf->SetFont('times', '', '8');
                $fpdf->SetXY($postx + 25 + 1, $posty + 17 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Matricule  :    " . $prod['matricule']), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 21.5 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Nom(s)     :    " . $prod['nom']), 0, 0, 'L');
                // $fpdf->Cell(86,6,utf8_decode(),0,0,'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 26 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Sexe         :    " . strtoupper($prod['sexe']). "       Section : " . strtoupper($prod['session'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 30.5 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Cycle        :    " . strtoupper($prod['cycle']) . "       Classe : " . strtoupper($prod['classe'])), 0, 0, 'L');
                /*--- date de naissance ----*/
                if ($prod['date_naiss'] == "01/01/1970") {
                    $prod['date_naiss'] = "";
                }
                /*--- date de naissance ----*/
                $fpdf->SetXY($postx + 25 + 1, $posty + 35 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Né(e)        :    " . $prod['date_naiss'] . "  à   " . $prod['lieu_naiss']), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 40 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Contact     :    " . strtoupper(" " . $prod['telephone'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 40 + 3);
                $fpdf->Cell(86, 6, utf8_decode("Tuteur       :    " . strtoupper($prod['tuteur'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 23 + 3, $posty + 49.3 - 1);
                $fpdf->Cell(52, 0, utf8_decode(''), 1, 1, 'C');
                $fpdf->SetXY($postx + 23 - 22, $posty + 49.3 - 1);
                $date = date('Y');
                $fpdf->Cell(86, 5, utf8_decode("Année : " . (date('Y')) . " / " . (date('Y')) + 1), 0, 0, 'L');
                // icab
                $fpdf->SetXY($postx + 23 - 22, $posty + 46.3 - 1);
                $fpdf->Cell(86, 5, utf8_decode($prod['code_school']), 0, 0, 'C');

                $fpdf->SetXY($postx + 26, $posty + 49.3 - 1);
                $fpdf->Cell(86, 5, utf8_decode(mb_strtoupper($prod['school'])), 0, 0, 'L');

                $j++;
            }
        }

        // Verso des cartes

        // $fpdf->SetAutoPageBreak(1, 1);
        // $postx = 9;
        // $x = $postx;
        // $posty = 3;
        // $y = $posty;
        // $j = 1;

        // for ($j = 1; $j <= 10; $j++) {
        //     if ($j % 10 == 1) {
        //         $fpdf->AddPage();
        //         $postx =  $x;
        //         $posty =  $y;
        //     } elseif ($j % 5 == 1) {
        //         $posty = $y;
        //         $postx += 10 + 95;
        //     } else {
        //         $posty += 55 + 3;
        //     }

        //     $fpdf->Image('Documents/logoRelever/back.jpg', $postx, $posty, 86, 52);
        //     $fpdf->SetFont('times', 'B', '9');
        //     $fpdf->SetXY($postx, $posty + 20);
        //     $fpdf->Cell(86, 6, utf8_decode("CARTE D'ETUDIANT"), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 25);
        //     $fpdf->SetFont('times', '', '8');
        //     $fpdf->Cell(86, 6, utf8_decode('Diocèse de Bafoussam '), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 30.5);
        //     $fpdf->SetFont('times', 'B', '8');
        //     $fpdf->Cell(86, 6, utf8_decode('INSTITUT CATHOLIQUE DE BAFOUSSAM '), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 35);
        //     $fpdf->SetFont('times', '', '5');
        //     $fpdf->Cell(86, 6, utf8_decode(' ETABLISSEMENT D\'ENSEIGNEMENT SUPERIEUR AUTORISE PAR LE NUNESUP'), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 35 + 2);
        //     $fpdf->Cell(86, 6, utf8_decode('AUT : N° 15/05584/MINEUSUP/DDE 056/06/15 '), 0, 0, 'C');

        //     // logo etablissement
        //     $fpdf->Image('Documents/logoRelever/icabLogo.jpeg', $postx + 37, $posty + 7.5, 10, 10);
        //     // pied de page
        //     $fpdf->Image('Documents/logoRelever/footer.jpeg', $postx, $posty + 42, 86, 12);
        // }

        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Cartes_eleves_'.$cycle[0]['name_cycle'].'_'.$session[0]['name_session'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }

    public function print_carte_class_one($student_id, $ecole_id, $session_id, $cycle_id, $id_class){
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $SchoolModel = new SchoolModel();
        $SessionModel = new SessionModel();
        $CycleModel = new CycleModel();
        $ClassModel = new ClassModel();
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
        $folder_avart = getenv('FILE_PHOTO_STUDENT');
        $i = 0;
        $listing = array();
        foreach ($student_list as $row) {
            $listing[] = array(
                'num' 			=> $i+1,
                'matricule'     => strtoupper(strtolower($row["matricule"])),
                'nom' 	        => strtoupper(strtolower($row["name"]))." ".(ucfirst(strtolower($row["surname"]))),
                'date_naiss' 	=> strtoupper($row["date_of_birth"]),
                'lieu_naiss' 	=> strtoupper($row["birth_place"]),
                'sexe' 	        => strtoupper($row["sexe"]),
                'classe'        => $name_class,
                'session'       => $session[0]['name_session'],
                'cycle'         => $cycle[0]['name_cycle'],
                'tuteur' 	    => $row["name_parent"].' '.$row["surnameParent"],
                'telephone' 	=> $row["contactParent"],
                'avatar' 	    => $folder_avart.'/'.$row["photo"],
                'school' 	    => $ecole[0]['name'],
                'logo' 	        => $ecole[0]['logo'],
                'code_school' 	=> $ecole[0]['code']
                
            );
            $i++;
        }

        //-- start pdf
        $fpdf = new PDF_CARTE();
        $fpdf->SetAutoPageBreak(1, 1);

        $postx = 9;
        $x = $postx;
        $posty = 3;
        $y = $posty;
        $j = 1;

        foreach ($listing as $prod) {
            $path = $prod['avatar'];

            if (file_exists($path)) {
                $name  = strlen($prod['nom']);
                $name1 = $prod['nom'];
                if ($name <= 18) {
                    $name1 = $name1;
                } else {
                    $rest = '';
                    for ($i = 0; $i <= 25; $i++) {
                        if (strlen($name) < 25) {
                            $rest = $name1;
                        } else {
                            $rest = $rest . $name1[$i];
                        }
                    }
                }

                if ($j % 10 == 1) {
                    $fpdf->AddPage();
                    $postx =  $x;
                    $posty =  $y;
                } elseif ($j % 5 == 1) {
                    $posty = $y;
                    $postx += 10 + 95;
                } else {
                    $posty += 55 + 3;
                }

                $name = $prod['nom'];
                // $lenght_name = strlen($prod['nom']);
                $prod['nom'] = $this->cut_out_the_name($name . "/");

                $name = $prod['lieu_naiss'];
                $lenght_name = strlen($prod['lieu_naiss']);
                if ($lenght_name <= 12) {
                    $prod['lieu_naiss'] = $name;
                } else {
                    $prod['lieu_naiss'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . "..";
                }

                $name = $prod['cycle'];
                $lenght_name = strlen($prod['cycle']);
                if ($lenght_name <= 21) {
                    $prod['cycle'] = $name;
                } else {
                    $prod['cycle'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . $name[11] . $name[12] . $name[13] . $name[14] . $name[15] . $name[16] . $name[17] . $name[18] . $name[19] . $name[20] . $name[21] . $name[22] . '..';
                }


                // reduction du nom du tuteur
                $name = $prod['tuteur'];
                $lenght_name_tuteur = strlen($prod['tuteur']);
                if ($lenght_name_tuteur <= 21) {
                    $prod['tuteur'] = $name;
                } else {
                    $prod['tuteur'] = $name[0] . $name[1] . $name[2] . $name[3] . $name[4] . $name[5] . $name[6] . $name[7] . $name[8] . $name[9] . $name[10] . $name[11] . $name[12] . $name[13] . $name[14] . $name[15] . $name[16] . $name[17] . $name[18] . $name[19] . $name[20];
                }
                
                $fpdf->Image(getenv('BG_CARTE'), $postx, $posty, 86, 52);

                $fpdf->Image(getenv('LOGO_CIRCLE'), $postx + 18 - 10 - 5, $posty + 2, 7 + 4, 7 + 4);
                $fpdf->Image(getenv('LOGO_CIRCLE'), $postx + 44 + 27 + 9 - 5 - 3, $posty + 2, 7 + 4, 7 + 4);
                $fpdf->SetFont('times', 'B', '9');
                $fpdf->SetXY($postx, $posty + 1);
                $fpdf->Cell(86, 5, utf8_decode('RÉPUBLIQUE DU CAMEROUN'), 0, 0, 'C');
                $fpdf->SetXY($postx, $posty + 5);
                $fpdf->SetFont('times', '', '7');
                $fpdf->Cell(86, 5, utf8_decode('Paix - Travail - Patrie'), 0, 0, 'C');
                $fpdf->SetFont('times', 'B', '11');
                $fpdf->SetXY($postx, $posty + 11);
                $fpdf->Cell(86 + 5, 5, utf8_decode("CARTE D'ÉLÈVE"), 0, 0, 'C');
                $fpdf->Image(getenv('CARDRE_PHOTO'), $postx + 2 + 5 - 4, $posty + 19 - 1, 20, 22);
                $fpdf->Image($prod['avatar'], $postx + 3 + 5 - 4, $posty + 20 - 1, 18.2, 26.2 - 3 - 2.9);

                // logo de la paix
                $fpdf->Image(getenv('CAMEROUN'), $postx + 2, $posty + 20 + 20.5, 8, 6);
                // qrCode
                // $chemin = $this->generate_qrcode($prod['matricule'], $prod['nom'], $prod['date_naiss'], $prod['school'], $prod['logo']);
                $fpdf->Image("images.png", $postx + 15, $posty + 19 + 21.5, 9, 7);

                $fpdf->SetFont('times', '', '8');
                $fpdf->SetXY($postx + 25 + 1, $posty + 17 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Matricule  :    " . $prod['matricule']), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 21.5 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Nom(s)     :    " . $prod['nom']), 0, 0, 'L');
                // $fpdf->Cell(86,6,utf8_decode(),0,0,'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 26 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Sexe         :    " . strtoupper($prod['sexe']). "       Section : " . strtoupper($prod['session'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 30.5 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Cycle        :    " . strtoupper($prod['cycle']) . "       Classe : " . strtoupper($prod['classe'])), 0, 0, 'L');
                /*--- date de naissance ----*/
                if ($prod['date_naiss'] == "01/01/1970") {
                    $prod['date_naiss'] = "";
                }
                /*--- date de naissance ----*/
                $fpdf->SetXY($postx + 25 + 1, $posty + 35 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Né(e)        :    " . $prod['date_naiss'] . "  à   " . $prod['lieu_naiss']), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 40 - 1);
                $fpdf->Cell(86, 6, utf8_decode("Contact     :    " . strtoupper(" " . $prod['telephone'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 25 + 1, $posty + 40 + 3);
                $fpdf->Cell(86, 6, utf8_decode("Tuteur       :    " . strtoupper($prod['tuteur'])), 0, 0, 'L');

                $fpdf->SetXY($postx + 23 + 3, $posty + 49.3 - 1);
                $fpdf->Cell(52, 0, utf8_decode(''), 1, 1, 'C');
                $fpdf->SetXY($postx + 23 - 22, $posty + 49.3 - 1);
                $date = date('Y');
                $fpdf->Cell(86, 5, utf8_decode("Année : " . (date('Y')) . " / " . (date('Y')) + 1), 0, 0, 'L');
                // icab
                $fpdf->SetXY($postx + 23 - 20, $posty + 46.3 - 1);
                $fpdf->Cell(86, 5, utf8_decode($prod['code_school']), 0, 0, 'L');

                $fpdf->SetXY($postx + 26, $posty + 49.3 - 1);
                $fpdf->Cell(86, 5, utf8_decode(mb_strtoupper($prod['school'])), 0, 0, 'L');

                $j++;
            }
        }

        // Verso des cartes

        // $fpdf->SetAutoPageBreak(1, 1);
        // $postx = 9;
        // $x = $postx;
        // $posty = 3;
        // $y = $posty;
        // $j = 1;

        // for ($j = 1; $j <= 10; $j++) {
        //     if ($j % 10 == 1) {
        //         $fpdf->AddPage();
        //         $postx =  $x;
        //         $posty =  $y;
        //     } elseif ($j % 5 == 1) {
        //         $posty = $y;
        //         $postx += 10 + 95;
        //     } else {
        //         $posty += 55 + 3;
        //     }

        //     $fpdf->Image('Documents/logoRelever/back.jpg', $postx, $posty, 86, 52);
        //     $fpdf->SetFont('times', 'B', '9');
        //     $fpdf->SetXY($postx, $posty + 20);
        //     $fpdf->Cell(86, 6, utf8_decode("CARTE D'ETUDIANT"), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 25);
        //     $fpdf->SetFont('times', '', '8');
        //     $fpdf->Cell(86, 6, utf8_decode('Diocèse de Bafoussam '), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 30.5);
        //     $fpdf->SetFont('times', 'B', '8');
        //     $fpdf->Cell(86, 6, utf8_decode('INSTITUT CATHOLIQUE DE BAFOUSSAM '), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 35);
        //     $fpdf->SetFont('times', '', '5');
        //     $fpdf->Cell(86, 6, utf8_decode(' ETABLISSEMENT D\'ENSEIGNEMENT SUPERIEUR AUTORISE PAR LE NUNESUP'), 0, 0, 'C');
        //     $fpdf->SetXY($postx, $posty + 35 + 2);
        //     $fpdf->Cell(86, 6, utf8_decode('AUT : N° 15/05584/MINEUSUP/DDE 056/06/15 '), 0, 0, 'C');

        //     // logo etablissement
        //     $fpdf->Image('Documents/logoRelever/icabLogo.jpeg', $postx + 37, $posty + 7.5, 10, 10);
        //     // pied de page
        //     $fpdf->Image('Documents/logoRelever/footer.jpeg', $postx, $posty + 42, 86, 12);
        // }

        $name_folder = getenv('FILE_PRINT_DOC');
        $name_file = $name_folder.'/Cartes_eleves_'.$student_list[0]['matricule'].'_'.$student_list[0]['name'].'_'.$name_class.'_'.($tab_start_year[0].'_'.($tab_start_year[0]+1)).'.pdf';
		$fpdf->Output($name_file,'F');

        $response = [
            'success'   => true,
            'status'    => 200,
            'name_file' => $name_file,
        ];
        return $this->respond($response);
    }
}
