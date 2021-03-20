<?php
include_once "../business/categoria_bus.php";

//init session
if (!isset($_SESSION)) {
    session_start();
}


//End points
if(isset($_GET['id_usuario'], $_GET['funcao'])){
    $categorias = new Categorias($_GET['id_usuario']);
    //novo
    if ($_GET['funcao'] == 'salvar' and isset($_GET['nome_categoria'], $_GET['tipo'])) {
        $nome_carteira = $_GET['nome_categoria'];
        $tipo = $_GET['tipo'];

        print_r(json_encode($categorias->cadastraCategoria($tipo, $nome_carteira)));
        exit();
    }
    //buscar todos
    if ($_GET['funcao'] == 'listartudo' and isset($_GET['tipo'])) {
        print_r(json_encode($categorias->listaCategoria($_GET['tipo'])));
        exit();
    }
    //apagar desativar
    if ($_GET['funcao'] == 'excluir' and isset($_GET['id_categoria'])) {
        print_r(json_encode($categorias->deletaCategoria($_GET['id_categoria'])));
        exit();
    }

}


class Categorias{
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