<?php

class Db{

    private $DB_PORT = '127.0.0.1:3306';
    private $DB_USER = 'root';
    private $DB_PASSWORD = 'root';
    private $DB_DATABASE = 'u292793146_confin';


    function connect(){

        $conn = mysqli_connect($this->DB_PORT, $this->DB_USER, $this->DB_PASSWORD) or die("Erro na conex�o " . mysql_error());

        mysqli_select_db($conn, $this->DB_DATABASE) or die("Erro ao selecionar o banco " . mysql_error());

        return $conn;
    }

    function close($conn){
        mysqli_close($conn);
    }
}


?>