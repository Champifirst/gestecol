<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DateOperation extends BaseController
{

    public function DateAtNumber($time){
        $timestamp = strtotime($time);
        $entier = (int)date("Ymd", $timestamp);
        
        return $entier;
    }

}