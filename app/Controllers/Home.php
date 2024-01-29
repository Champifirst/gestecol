<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function login()
    {
        $session = session();
        $session->destroy();
        return view('user/login.php');
    }

    public function licence(){
        return view('licence/licence.php');
    }

    public function home(){
        return view('index.php');
    }

    public function home2(){
        return view('etatFinancier');
    }

    public function save(){
        return view('school/save.php');
    }

    public function liste(){
        return view('school/liste.php');
    }

    public function choiceSchool(){
        return view('choiceSchool/choice.php');
    }
    
    public function note(){
        return view('note/note.php');
    }
    
}
