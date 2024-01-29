<?php

namespace App\Helpers;

class SharedHelper
{
    public function getCreateAt(){
        return date("Y-m-d H:m:s");
    }
    public function getUpdateAt(){
        return date("Y-m-d H:m:s");
    }
    public function getDeleteAt(){
        return date("Y-m-d H:m:s");
    }
}