<?php
include_once "../business/ganho_bus.php";

class GanhoController{
    private $total_ganhos;
    private $_id_usuario;
    public $lista_ganhos;

    function __construct($id_usuario){
        $this->_id_usuario = $id_usuario;
    }

    function salvarGanho($obj_ganho){
        return GanhoBus::createGanho($obj_ganho);
    }

    function buscarTodosGanhos(){
        return GanhoBus::readGanho($this->_id_usuario);
    }

    function buscarGanho($id_ganho){
        return GanhoBus::readGanho($this->_id_usuario, $id_ganho);
    }

    function atualizarGanho($obj_ganho){
        return GanhoBus::updateGanho($obj_ganho);
    }

    function deletarGanho($id_ganho){
        return GanhoBus::deleteGanho($id_ganho);
    }
}




?>