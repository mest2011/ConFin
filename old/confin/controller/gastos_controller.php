<?php
include_once "../business/gasto_bus.php";
include_once "../business/carteira_bus.php";

class Gastos{
    private $total_gastos;
    private $_id_usuario;
    public $lista_gastos;

    function __construct($id_usuario){
        $this->_id_usuario = $id_usuario;
    }

    function total_de_gastos(){
        $this->total_gastos = GastoBus::buscarTotalGastos($this->_id_usuario);
        return $this->total_gastos;
        
    }

    function lista_gastos(){
        if(strlen($this->lista_gastos) == 0 || $this->lista_gastos == null || $this->lista_gastos == ""){
            $this->lista_gastos = GastoBus::buscarListaGastosMes($this->_id_usuario);
        }        
        return $this->lista_gastos;
    }

    function retorna_gasto($id){
        return GastoBus::buscaGasto($id, $this->_id_usuario);
    }

    function lista_gastos_futuros(){   
        return GastoBus::buscarListaGastosFuturos($this->_id_usuario);
    }

    function excluir($id){
        return GastoBus::excluirGasto($id);
    }
    
    function salvarGasto($obj_gasto){
        return GastoBus::salvarNovoGasto($obj_gasto);
    }

    function atualizarGasto($obj_gasto){
        return GastoBus::atualizaGasto($obj_gasto);
    }

    function listarCarteiras(){
        return CarteiraBus::carteiras($this->_id_usuario);
    }

}






?>