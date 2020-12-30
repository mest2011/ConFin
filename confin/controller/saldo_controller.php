<?php

include_once "../business/saldo_bus.php";

class Saldo{
    private $_saldo;

    function __construct($id_usuario){
        $this->_saldo = SaldoBus::buscarSaldo($id_usuario);
    }

    function saldo(){
        return $this->_saldo;
    }

}








?>