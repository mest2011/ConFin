<?php
include_once "../database/crud.php";

class SaldoBus extends Crud{
    static function buscarSaldo($id_usuario){
        $sql = "SELECT saldo FROM tb_saldo WHERE id_usuario = '".$id_usuario."';";
        return parent::read($sql)[0]['saldo'];
    }
}


?>