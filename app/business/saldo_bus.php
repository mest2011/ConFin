<?php
include_once "../database/crud.php";

class SaldoBus extends Crud{
    static function buscarSaldo($id_usuario){
        $sql = "SELECT sum(saldo) as saldo FROM tb_carteira WHERE id_usuario = ".$id_usuario." AND status = 1 AND poupanca <> 1;";
        return parent::read($sql)[0]['saldo'];
    }
}


?>