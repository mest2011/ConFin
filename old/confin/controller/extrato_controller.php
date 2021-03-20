<?php
include_once "../business/extrato_bus.php";





class ExtratoController{
    private $_id_usuario;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function ver($dt_inicio = "0", $dt_fim = "0"){
        return ExtratoBus::buscar($this->_id_usuario, $dt_inicio = "0", $dt_fim = "0");
    }
}

?>