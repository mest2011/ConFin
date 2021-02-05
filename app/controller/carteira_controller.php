<?php
include_once "../business/carteira_bus.php";

class Carteiras{
    private $total_carteiras;
    private $_id_usuario;
    public $lista_carteiras;

    function __construct($id_usuario){
        $this->_id_usuario = $id_usuario;
    }

    function salvarCarteira($obj_Carteira){
        return CarteiraBus::createCarteira($obj_Carteira);
    }

    function buscarTodosCarteiras(){
        return CarteiraBus::readCarteira($this->_id_usuario);
    }

    function buscarCarteira($id_Carteira){
        return CarteiraBus::readCarteira($this->_id_usuario, $id_Carteira);
    }

    function atualizarCarteira($obj_Carteira){
        return CarteiraBus::updateCarteira($obj_Carteira);
    }

    function deletarCarteira($id_Carteira){
        return CarteiraBus::deleteCarteira($id_Carteira);
    }
    function listarCarteiras(){
        return CarteiraBus::carteiras($this->_id_usuario);
    }

    function transferir($id_carteira_origem, $id_carteira_destino, $valor){
        return CarteiraBus::transferirValorEntreCarteiras($this->_id_usuario, $id_carteira_origem, $id_carteira_destino, $valor);
    }
}




?>