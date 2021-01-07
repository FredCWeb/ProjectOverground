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

namespace VEND\MAIL;

use PHPMailer\PHPMailer;
use FFW\System;
use FFW\HTML\Valid;

class PHPMailerExtend extends System
{
    private $mail;

    public function __construct()
    {
        parent::__construct();
        $arrGlobal = $this->globalVars;
        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = $arrGlobal['PHPMailer']['SMTPDebug'];
        $this->mail->Host = $arrGlobal['PHPMailer']['Host'];
        $this->mail->Port = $arrGlobal['PHPMailer']['Port'];
        $this->mail->SMTPSecure = $arrGlobal['PHPMailer']['SMTPSecure'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $arrGlobal['PHPMailer']['Username'];
        $this->mail->Password = $arrGlobal['PHPMailer']['Password'];
    }

    public function sendMail($to,$from,$subject,$templateName,$arrVar = false){
        $hasHTML = false;
        if(is_array($from) && isset($from['email'])){
            $emailCheck = array(0 => array('email' => true, 'value' => $to, 'key' => 'to'),
                1 => array('email' => true, 'value' => $from['email'], 'key' => 'from'));
        }else {
            $emailCheck = array(0 => array('email' => true, 'value' => $to, 'key' => 'to'),
                1 => array('email' => true, 'value' => $from, 'key' => 'from'));
        }
        $valid = new Valid();
        $valid->validateForm($emailCheck);
        if(!$valid->success){ return false; }
        if(!$this->frommAddress($from)){ return false; }
        if(!$this->toAddress($to)){ return false; }
        $this->mail->Subject = $subject;
        $htmlFile = BASE_DIR.'/TEMPLATES/Email/html/'.$templateName.'.html';
        if(file_exists($htmlFile)){
            $htmlValue = file_get_contents($htmlFile);
            if(is_array($arrVar)) {
                foreach ($arrVar as $key => $value){
                    $htmlValue = str_replace('<%-'.$key.'-%>', $value, $htmlValue);
                }
            }
            $hasHTML = true;
            $this->mail->msgHTML($htmlValue);
        }
        $textFile = BASE_DIR.'/TEMPLATES/Email/text/'.$templateName.'.txt';
        if(file_exists($textFile)){
            $textValue = file_get_contents($textFile);
            if(is_array($arrVar)) {
                foreach ($arrVar as $key => $value){
                    $textValue = str_replace('<%-'.$key.'-%>', $value, $textValue);
                }
            }
            if(!$hasHTML){
                $htmlValue = str_replace("\n", "<br>", $textValue);
                $this->mail->msgHTML($htmlValue);
            }
            $this->mail->AltBody = $textValue;
        }else{
            return false;
        }

        return $this->mail->send();
    }

    private function frommAddress($from){
        if(is_array($from)){
            if(isset($from['email']) && $from['email'] != ''){
                $fromEmail = $from['email'];
                if(isset($from['name']) && $from['name'] != ''){
                    $fromName = $from['name'];
                }else{
                    $fromName = '';
                }
                return $this->mail->setFrom($fromEmail, $fromName);
            }else{
                return false;
            }
        }else if($from != ''){
            $fromEmail = $from;
            return $this->mail->setFrom($fromEmail, "");
        }else{
            return false;
        }
    }

    private function toAddress($to){
        if($to != ''){
            $toEmail = $to;
            return $this->mail->addAddress($toEmail, "");
        }else{
            return false;
        }
    }
}
?>