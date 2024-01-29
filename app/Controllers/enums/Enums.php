<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Enumcy extends BaseController
{

    public function enumFonctionnality()
    { 
        $data = [
            '0'  => 'parent',
            '1'  => 'child'
        ];
        return $data;
    }

    public function enumUser()
    {
        $data = [
            '0'   => 'admin',
            '1'   => 'root',
            '2'   => 'teacher',
        ];
        return $data; 
    }
}