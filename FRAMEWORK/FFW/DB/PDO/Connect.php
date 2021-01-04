<?php/*    Copyright (C) 2020  Fred Ciabattoni - ProjectOverground v1.0    http://projectoverground.com/    This program is free software: you can redistribute it and/or modify    it under the terms of the GNU General Public License as published by    the Free Software Foundation, either version 3 of the License, or    (at your option) any later version.    This program is distributed in the hope that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    GNU General Public License for more details.    You should have received a copy of the GNU General Public License    along with this program.  If not, see <http://www.gnu.org/licenses/>.*/namespace FFW\DB\PDO;use FFW\System;use PDO;use PDOException;class Connect extends System{    protected $conn;    public function __construct(){        parent::__construct();    }    public function connector()    {        if(isset($this->globalVars['PDO']['connect']) && $this->globalVars['PDO']['connect']) {            (isset($this->globalVars['PDO']['development']) && $this->globalVars['PDO']['development']) ? $site = 'dev' : $site = 'live';            (isset($this->globalVars['PDO'][$site]['Host'])) ? $Host = $this->globalVars['PDO'][$site]['Host'] : $Host = '';            (isset($this->globalVars['PDO'][$site]['DB'])) ? $DB = $this->globalVars['PDO'][$site]['DB'] : $DB = '';            (isset($this->globalVars['PDO'][$site]['Username'])) ? $Username = $this->globalVars['PDO'][$site]['Username'] : $Username = '';            (isset($this->globalVars['PDO'][$site]['Password'])) ? $Password = $this->globalVars['PDO'][$site]['Password'] : $Password = '';            try {                $conn = new \PDO("mysql:host=" . $Host . ";dbname=" . $DB, $Username, $Password);                // set the PDO error mode to exception                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                $this->conn = $conn;            } catch (PDOException $e) {                echo "Error: " . $e->getMessage();            }        }    }    public function disconnect()    {        $this->conn = null;    }}?>