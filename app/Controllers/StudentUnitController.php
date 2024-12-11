<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\StudentunitModel;
use App\Models\TeachingUnitModel;
use App\Models\SchoolModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\CycleModel;
use App\Models\YearModel;
use App\Controllers\History;
include('History/HistorySession.php');


class StudentUnitController extends ResourceController
{

    use ResponseTrait;

    public function insertstudentunit()
    {
        $StudentUnit = new StudentUnit();
        $TeachingUnitModel = new TeachingUnitModel();
        $SchoolModel       = new SchoolModel();
        $ClassModel        = new ClassModel();
        $SessionModel      = new SessionModel();
        $CycleModel        = new CycleModel();
        $YearModel         = new YearModel();

        // validation du formulaire 
        $rules = [
            'name_school'   => [
                'rules'         => 'required'
            ],
            'session'  => [
                'rules'         => 'required'
            ],
            'cycle'    => [
                'rules'         => 'required'
            ],
            'class'   => [
                'rules'         => 'required'
            ],
            'user_id'          => [
                'rules'         => 'required'
            ],
            'subjects'       => [
                'rules'         => 'required'
            ]
        ];


    }

    public function show($id = null)
    {
        //
    }


    public function new()
    {
        //
    }


    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
