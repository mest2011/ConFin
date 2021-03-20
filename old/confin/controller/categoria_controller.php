<?php
include_once "../business/categoria_bus.php";

class CategoriaController{
    private $_id_usuario;

    function __construct($id_usuario){
        $this->_id_usuario = $id_usuario;
    }
        
    function cadastraCategoria($tipo, $nome){
        return CategoriaBus::createCategoria($this->_id_usuario, $tipo, $nome);
    }

    function listaCategoria($tipo){
        return CategoriaBus::readCategoria($this->_id_usuario, $tipo);
    }

    function deletaCategoria($id){
        return CategoriaBus::deleteCategoria($this->_id_usuario, $id);
    }
}

?>