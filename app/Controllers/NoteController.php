<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\NoteModel;
use App\Models\DocumentModel;
use App\Models\StudentModel;
use App\Models\YearModel;
use App\Models\StudentClassModel;
use App\Models\TeachingUnitModel;

class NoteController extends ResourcePresenter
{

    use ResponseTrait;

    public function save(){
        return view('note/save.php');
    }
    
    public function liste(){
        return view('note/list.php');
    }

    public function imprimer_bulletin(){
        return view('note/imprimer_bulletin.php');
    }
    

    public function GetnoteByTeachingUnit($id_class, $id_teaching_unit, $id_sequence){
        $NoteModel = new NoteModel();
        $StudentModel = new StudentModel();
        $YearModel = new YearModel();
        $StudentClassModel = new StudentClassModel();
        $TeachingUnitModel = new TeachingUnitModel();
        $teaching_unit =  $TeachingUnitModel->getTeachingById($id_teaching_unit);
        $yearActif = $YearModel->getYearActif();
        $year_id = $yearActif[0]['year_id'];

        $student_class = $StudentClassModel->getStudentByClass($id_class, $year_id);
        $data_final = array();

        foreach ($student_class as $row) {
            $note = $NoteModel->getNoteByStudent($row['student_id'], $id_teaching_unit, $year_id, $id_sequence);
            $val_note = -1;
            $close = "false";
            if (sizeof($note) != 0) {
                $val_note = $note[0]['note'];
                $close = $note[0]['close'];
            }

            $data_final[] = [
                'id_student'    => $row['student_id'],
                'matricule'     => $row['matricule'],
                'name'          => $row['name'],
                'surname'       => $row['surname'],
                'coefficient'   => $teaching_unit[0]['coefficient'],
                'note'          => $val_note,
                'close'         => $close
            ];
        }

        return $this->respond($data_final);
    }


    #@-- 1 --> insertion des notes
    #- use:
    #-
    public function insertnote()
    {

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
            'name_trimestre'=> [
                'rules'         => 'required'
            ],
            'name_sequence' => [
                'rules'         => 'required'
            ],
            'name_matiere'  => [
                'rules'         => 'required'
            ],
            'user_id'       => [
                'rules'         => 'required'
            ],
            'student_id'   => [
                'rules'         => 'required'
            ],
            'note'         => [
                'rules'         => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            
            $name_school     = $this->request->getvar('name_school');
            $name_session    = $this->request->getvar('name_session');
            $name_cycle      = $this->request->getvar('name_cycle');
            $name_classe     = $this->request->getvar('name_classe');
            $name_trimestre  = $this->request->getvar('name_trimestre');
            $name_sequence   = $this->request->getvar('name_sequence');
            $name_matiere    = $this->request->getvar('name_matiere');
            $user_id         = $this->request->getvar('user_id');
            $student_id      = $this->request->getvar('student_id');
            $note            = $this->request->getvar('note');
            $NoteModel = new NoteModel();
            $YearModel = new YearModel();
            $yearActif = $YearModel->getYearActif();
            $year_id = $yearActif[0]['year_id'];
            
            for ($j=0; $j < sizeof($note); $j++) { 
                $one_note = floatval($note[$j]);
                
                if ($one_note == NULL || $one_note == "") {
                    $one_note = -1;
                }else if ($one_note > 20) {
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "A la ligne ".($j+1)." note ne doit pas être supérieure à 20.00",
                    ];
        
                    return $this->respond($response);
                }else if ($one_note < 0) {
                  
                    $response = [
                        "success" => false,
                        "status"  => 500,
                        "code"    => "error",
                        "title"   => "Erreur",
                        "msg"     => "A la ligne ".($j+1)." note ne doit pas être inférieure à 0.00",
                    ];
        
                    return $this->respond($response);
                }

                $data_final = [
                    'note'              =>  $one_note,
                    'student_id'        =>  $student_id[$j],
                    'teachingunit_id'   =>  $name_matiere,
                    'year_id'           =>  $year_id,
                    'sequence_id'       =>  $name_sequence,
                    'status_note'       =>  0,
                    'etat_note'         =>  'actif',
                    'close'             =>  'false',
                    'created_at'        =>  date("Y-m-d H:m:s"),
                    'updated_at'        =>  date("Y-m-d H:m:s")
                ];

                // if not exists
                $get_note =  $NoteModel->getNoteByStudent($student_id[$j], $name_matiere, $year_id, $name_sequence);
                if (sizeof($get_note) == 0) {
                    // insert
                    if (!$NoteModel->save($data_final)) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => "Echec de l'insertion",
                        ];
                        return $this->respond($response);
                    }
                    
                }else if (sizeof($get_note) != 0) {
                    // update
                    $note_id = $get_note[0]['note_id'];
                    if ($NoteModel->where('note_id', $note_id)->set($data_final)->update() === false) {
                        $response = [
                            "success" => false,
                            "status"  => 500,
                            "code"    => "error",
                            "title"   => "Erreur",
                            'msg'     => "Echec de la modification",
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
                "msg"     => 'Insertion réussir',
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

    public function listenote(){

        $NoteModel = new NoteModel();

        // validation du formulaire 
        $rules = [

            'class'          => [
                'rules' => 'required|max_length[15]'
            ],
            'sequence'          => [
                'rules' => 'required|max_length[10]'
            ],
            'matiere'          => [
                'rules' => 'required|max_length[40]'
            ],
            'annee'          => [
                'rules' => 'required'
            ]
        ];

        if ($this->validate($rules)) {
            //validation good

            $class              = $this->request->getvar('class');
            $sequence           = $this->request->getvar('sequence');
            $matiere            = $this->request->getvar('matiere');
            $annee              = $this->request->getvar('annee');
            $DocumentModel      = new DocumentModel;
            $class_id           = $DocumentModel->getId('class', 'class.class_id', 'name',$class, 'status_class', 'etat_class');
            $sequence_id        = $DocumentModel->getId('sequence', 'sequence.sequence_id', 'name',$sequence, 'status_sequence', 'etat_sequence');
            $teachingunit_id    = $DocumentModel->getId('teachingunit', 'teachingunit.teachingunit_id', 'name',$matiere, 'status_teachingunit', 'etat_teachingunit');
             $year_id           = $DocumentModel->getId('year', 'year.year_id', 'name_year',$annee, 'status_year', 'etat_year');

            $data = $NoteModel->getNote_student($class_id, $teachingunit_id, $sequence_id, $year_id);

            $listing  = array();

            foreach ($data as $values){

            $donnee = [
                    'student'       => $values->name,
                    'note_id'       => $values->note_id,
                    'last_note'     => $values->note,
                    'new_note'      =>'',
                ];

            $listing = array($donnee);
            }
            return $this->respond($listing);
        }

        else {
                //validation failed
                $response = [
                    "success" => false,
                    "status"  => 500,
                    "code"    => "error",
                    "title"   => "Erreur",
                    'msg'     =>$this->validator->getErrors(),
                ];
                return $this->respond($response);
        }     
    }


    #@-- 1 --> modification des notes
    #- use:
    #-
    // public function updatenote()
    // {
    //     $NoteModel = new NoteModel;
    //     // validation du formulaire 
    //     $rules = [
    //         'class'          => [
    //             'rules' => 'required|max_length[15]'
    //         ],
    //         'sequence'          => [
    //             'rules' => 'required|max_length[10]'
    //         ],
    //         'matiere'          => [
    //             'rules' => 'required|max_length[40]'
    //         ],
    //         'annee'          => [
    //             'rules' => 'required'
    //         ]
    //     ];
    
    //     if ($this->validate($rules)) {
    //         //validation good


    //         if ($new_note > 20) {
    //             $response = [
    //                 "success" => false,
    //                 "status"  => 500,
    //                 "code"    => "error",
    //                 "title"   => "Erreur",
    //                 "msg"     => "la note est incorrecte",
    //             ];
    
    //             return $this->respond($response);
    //         }else {

    //             //modification en lot
    //             $post_datas = $this->request->getvar($listing); //Array post datas

    //             $data = []; //Initialize array 

    //             foreach ($post_datas as $listing) {

    //                 $row = [
    //                 'note'                   => $listing->new_note,
    //                 'status_note'            => 0,
    //                 'etat_note'              => 'actif',
    //                 'updated_at'             => date("Y-m-d H:m:s"),
    //                 ];

    //                 // add row to data
    //                 array_push($data, $row);
    //             }

    //             $note_id = $this->listenote($listing->note_id);
                
    //            if ($NoteModel->where('note_id', $note_id)->set($data)->update() === false) {
    //                 // echec de modification
    //                 $response = [
    //                     "success" => false,
    //                     "status"  => 500,
    //                     "code"    => "error",
    //                     "title"   => "Erreur",
    //                     'msg'     => "champs incorrect",
    //                     ];
    //                 return $this->respond($response);

    //             }else
    //                 {
    //                  // modification reussir
    //                 $response = [
    //                     'success' => true,
    //                     'status'  => 200,
    //                     "code"    => "error",
    //                     "title"   => "Erreur",
    //                     'msg'     => "modification reussir",
    //                 ];
    //                 return $this->respond($response);

    //             }

    //         }
                
    //     }else{
    //         // validation failed
    //         $response = [
    //             "success" => false,
    //             "status"  => 500,
    //             "code"    => "error",
    //             "title"   => "Erreur",
    //             "msg"     => $this->validator->getErrors(),
    //         ];
    //         return $this->respond($response);
    //     }
    // }

}
