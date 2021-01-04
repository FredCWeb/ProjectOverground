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

namespace FFW\PHP;

class Encrypt
{
    Private $CRYPT_SALT = 85; # any number ranging 1-255
    Private $START_CHAR_CODE = 100; # 'd' letter ASCII printable characters A = 97 to Z = 122

    public function __construct($salt = false,$code = false)
    {
        if($salt) {
            $this->CRYPT_SALT = $salt;
        }
        if($code) {
            $this->START_CHAR_CODE = $code;
        }
    }

    private function text_crypt_symbol($c) {
        # $c is ASCII code of symbol. returns 2-letter text-encoded version of symbol
        return chr($this->START_CHAR_CODE + ($c & 240) / 16).chr($this->START_CHAR_CODE + ($c & 15));
    }

    public function text_crypt($s) {
        if ($s == "")
            return $s;
        $enc = rand(1,255); # generate random salt.
        $result = $this->text_crypt_symbol($enc); # include salt in the result;
        $enc ^= $this->CRYPT_SALT;
        for ($i = 0; $i < strlen($s); $i++) {
            $r = ord(substr($s, $i, 1)) ^ $enc++;
            if ($enc > 255)
                $enc = 0;
            $result .= $this->text_crypt_symbol($r);
        }
        return $result;
    }

    private function text_decrypt_symbol($s, $i) {
        # $s is a text-encoded string, $i is index of 2-char code. function returns number in range 0-255
        return (ord(substr($s, $i, 1)) - $this->START_CHAR_CODE)*16 + ord(substr($s, $i+1, 1)) - $this->START_CHAR_CODE;
    }

    public function text_decrypt($s) {
        $result = '';
        if ($s == "")
            return $s;
        $enc = $this->CRYPT_SALT ^ $this->text_decrypt_symbol($s, 0);
        for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
            $result .= chr($this->text_decrypt_symbol($s, $i) ^ $enc++);
            if ($enc > 255)
                $enc = 0;
        }
        return $result;
    }
}
?>