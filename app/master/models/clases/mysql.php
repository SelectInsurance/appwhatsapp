<?php

class mysql
{
    private $host;
    private $user;
    private $pass;
    private $db;

    public function __construct()
    {
        $this->host = 'bjltfumjhs9ag3a7kytq-mysql.services.clever-cloud.com';
        $this->user = 'uur3yy29dlbzjbte';
        $this->pass = 'JD50xv9dhQ7NP5Yl2WtV';
        $this->db = 'bjltfumjhs9ag3a7kytq'; 
/*         $this->host = 'localhost';
        $this->user = 'root';
        $this->pass = '';
        $this->db = 'whatsapp'; */
    }

    public function Conexion()
    {
        $mysql = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        //error_reporting(0);
        
        if (isset($mysql->mysqli_connect_errno)) {
            echo 'Error de conexion';
            
        } elseif ($mysql->set_charset('utf8')) {
            return $mysql;
        }
    }

    public function __destruct()
    {
        mysqli_close($this->Conexion());
    }
}
