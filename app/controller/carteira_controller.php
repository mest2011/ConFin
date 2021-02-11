<?php
include_once "../business/carteira_bus.php";
include_once "../classes/Carteira.php";

//init session
if (!isset($_SESSION)) {
    session_start();
}


//End points
if(isset($_GET['id_usuario'], $_GET['funcao'])){
    $carteira = new Carteiras($_GET['id_usuario']);
    //novo
    if ($_GET['funcao'] == 'salvar' and isset($_GET['nome_carteira'])) {
        $novaCarteira = new Carteira();
        $novaCarteira->id_carteira = "0";
        $novaCarteira->id_usuario = $_GET['id_usuario'];
        $novaCarteira->nome_carteira = $_GET['nome_carteira'];

        print_r(json_encode($carteira->salvarCarteira($novaCarteira)));
        exit();
    }
    //buscar todos
    if ($_GET['funcao'] == 'listartudo') {
        print_r(json_encode($carteira->listarCarteiras()));
        exit();
    }
    
    //buscar um
    if ($_GET['funcao'] == 'listarum' and isset($_GET['id_carteira'])) {
        print_r(json_encode($carteira->buscarCarteira($_GET['id_carteira'])));
        exit();
    }
    //apagar desativar
    if ($_GET['funcao'] == 'excluir' and isset($_GET['id_carteira'])) {
        print_r(json_encode($carteira->deletarCarteira($_GET['id_carteira'])));
        exit();
    }
    //Transferir saldo
    if ($_GET['funcao'] == 'transferir' and isset($_GET['id_carteira_origem'], $_GET['id_carteira_destino'], $_GET['valor'])) {
        print_r(json_encode($carteira->transferir($_GET['id_carteira_origem'], $_GET['id_carteira_destino'], $_GET['valor'])));
        exit();
    }

}



//Class
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

    function listarCarteiras(){
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

    function transferir($id_carteira_origem, $id_carteira_destino, $valor){
        return CarteiraBus::transferirValorEntreCarteiras($this->_id_usuario, $id_carteira_origem, $id_carteira_destino, $valor);
    }
}




?>