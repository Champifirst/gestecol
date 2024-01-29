<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SHareHelper extends BaseController
{

    public function fonctionnality(){
        
        $data[] = array(
            'coded'         => '001',
            'name'          => strtolower('manage_school'),
            'status_fonc'   => 0,
            'array_fonct'   => "",
            'type_fonct'    => "parent",
            'etat_fonc'     => 'actif',
            'created_at'    => date("Y-m-d H:m:s"),
            'updated_at'    => date("Y-m-d H:m:s")
        );

        return $data;
    }

    public function attachFonctionnality()
    {
        $data[] = array(
            'parent'         => '001',
            'child'          => '',
        );

        return $data;
    }
}