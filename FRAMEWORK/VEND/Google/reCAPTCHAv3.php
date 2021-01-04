<?php
/*
    Copyright (C) 2020  Fred Ciabattoni - ProjectOverground v1.0
    http://projectoverground.com/

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace VEND\Google;

use FFW\System;

class reCAPTCHAv3 extends System
{
    public $jsFooter;
    private $clientCode;
    private $serverCode;
    private $score;

    public function __construct($formName){
        parent::__construct();
        $this->clientCode = $this->globalVars['reCAPTCHAv3']['clientSide'];
        $this->serverCode = $this->globalVars['reCAPTCHAv3']['serverSide'];
        $this->score = $this->globalVars['reCAPTCHAv3']['score'];
        $this->jsFooter = $this->clientJS($formName);
    }

    public function serverSideCheck($GoogleToken){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=".$this->serverCode."&response=".$GoogleToken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($server_output);
        
        if($response->success && $response->score >= $this->score){
            return true;
        }else{
            return false;
        }
    }
    
    public function submitButton($text,$class){ 
        $button = '<input type="hidden" id="GoogleToken" name="GoogleToken">
                   <button class="g-recaptcha '.$class.'" data-sitekey="'.$this->clientCode.'" data-callback="onSubmit" data-action="submit">
                       '.$text.'
                   </button>';
        return $button;
    }
    
    private function clientJS($formName){
        $JS = '<script src="https://www.google.com/recaptcha/api.js"></script>
               <script>
                   function onSubmit(token) {
                       document.getElementById("GoogleToken").value = token;
                       document.getElementById("'.$formName.'").submit();
                   }
               </script>';
        return $JS;
    }
}