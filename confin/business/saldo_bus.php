<?php
include_once "../database/crud.php";

class SaldoBus extends Crud{
    static function buscarSaldo($id_usuario){
        $sql = "SELECT sum(saldo) as saldo FROM tb_carteiras WHERE id_usuario = '".$id_usuario."';";
        return parent::read($sql)[0]['saldo'];
    }
}


?>