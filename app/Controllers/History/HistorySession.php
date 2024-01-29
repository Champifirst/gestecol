<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HistorySession extends BaseController
{
    public function CreateFileHistory($id_user, $type_user, $login, $pass){
        $login = str_replace(' ', '_', $login);
        $date = str_replace(' ', '_', date("Y-m-d H-m-s"));
        $date = str_replace('-', '_', $date);
        $name_file = 'history/data/'.$id_user.'_'.$login.'_'.$pass.'_'.$date.'.txt';
        $session = session();
        $session->set('name_file', $name_file);
        $file = touch($name_file);
        if ($file !== false) {
            $verdic = $this->ReadOperation($id_user, $login, $type_user, date("Y-m-d H:m:s"), "Authentification", "Réussite", "Utilisateur", $this->getIp(), $this->getUserAgent(), "Nouvelle session initialisée");
            if ($verdic) {
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    public function ReadOperation($id, $login, $type_user, $date_time, $type_action, $status, $table, $ip_adress, $navigateur, $all_info){
        $ip_adress = $this->getIp();
        $navigateur = $this->getUserAgent();
        $date_time = date("Y-m-d H-m-s");
        $session = session();
        $name_file = $session->get('name_file');
        $file = fopen($name_file, "a");
        $contain = "\n$id;"."$login;"."$type_user;"."$date_time;"."$type_action;"."$status;"."$table;"."$ip_adress;"."$navigateur;"."$all_info;"."EOF;";
        if ($file !== false) {
            fwrite($file, $contain);
            fclose($file);
            return true;
        } else {
            return false;
        }
    }

    function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getUserAgent(){
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browser = get_browser($userAgent);

        return $browser->browser;
    }

    public function getInfoSession(){
        // session
        $session = session();

        $data = [
            "id_user"   => $session->get('id_user'),
            "type_user" => $session->get('type_user'),
            "login"     => $session->get('login'),
            "password"  => $session->get('password'),
        ];
        return $data;
    }
    
}   