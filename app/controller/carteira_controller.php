<?php
include_once "../business/carteira_bus.php";
include_once "../classes/Carteira.php";
include_once "../controller/session_controller.php";

//init session

if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['funcao'])) {

    //valida sessão
    $sessao = new Session_security("isValid");

    if (!$sessao->validaSessao("isValid")) {
        print_r(json_encode("Erro : tempo de sessao excedido!"));
        exit();
    }

    if (!isset($_SESSION['id_usuario'])) {
        print_r(json_encode("Erro na sessao atual, entre novamente no sistema!"));
        exit();
    }

    $carteira = new Carteiras($_SESSION['id_usuario']);


    try {
        //Create and Update
        if ($_POST['funcao'] == 'salvar') {
            if (isset($_POST['nome_cateira'])) {

                $obj_carteira = new Carteira();
                $obj_carteira->id_usuario = $_SESSION['id_usuario'];
                $obj_carteira->nome_carteira = $_POST['nome_cateira'];
                if (isset($_POST['cor'])) {
                    $obj_carteira->cor = $_POST['cor'];
                }
                if (isset($_POST['descricao'])) {
                    $obj_carteira->descricao = $_POST['descricao'];
                }
                if (isset($_POST['poupanca'])) {
                    $obj_carteira->poupanca = ($_POST['poupanca']=="true"?1:0);
                }
                if (isset($_POST['id'])) {
                    $obj_carteira->id_carteira = $_POST['id'];
                    print_r(json_encode($carteira->atualizarcarteira($obj_carteira)));
                    exit();
                } else {
                    print_r(json_encode($carteira->salvarcarteira($obj_carteira)));
                    exit();
                }
            }
        }
        //Listar
        if ($_POST['funcao'] == 'listar') {
            print_r(json_encode($carteira->listarCarteiras()));
            exit();
        }
        //Delete
        if ($_POST['funcao'] == 'excluir' and isset($_POST['id'])) {
            print_r(json_encode($carteira->deletarCarteira($_POST['id'])));
            exit();
        }
        //Transferir
        if ($_POST['funcao'] == 'transferir' and isset($_POST['id_carteira_origem'], $_POST['id_carteira_destino'], $_POST['valor'])) {
            print_r(json_encode($carteira->transferir($_POST['id_carteira_origem'], $_POST['id_carteira_destino'], $_POST['valor'])));
            exit();
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação!"));
        exit();
    }
}



//End points
if (isset($_GET['id_usuario'], $_GET['funcao'])) {
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
    //Listar carteras poupanca
    if ($_GET['funcao'] == 'listarpoupanca') {
        print_r(json_encode($carteira->listarCarteirasPoupanca()));
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
class Carteiras
{
    private $total_carteiras;
    private $_id_usuario;
    public $lista_carteiras;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function salvarCarteira($obj_Carteira)
    {
        return CarteiraBus::createCarteira($obj_Carteira);
    }

    function listarCarteiras()
    {
        return CarteiraBus::readCarteira($this->_id_usuario);
    }

    function listarCarteirasPoupanca()
    {
        return CarteiraBus::readCarteiraPoupanca($this->_id_usuario);
    }

    function buscarCarteira($id_Carteira)
    {
        return CarteiraBus::readCarteira($this->_id_usuario, $id_Carteira);
    }

    function atualizarCarteira($obj_Carteira)
    {
        return CarteiraBus::updateCarteira($obj_Carteira);
    }

    function deletarCarteira($id_Carteira)
    {
        return CarteiraBus::deleteCarteira($id_Carteira);
    }

    function transferir($id_carteira_origem, $id_carteira_destino, $valor)
    {
        return CarteiraBus::transferirValorEntreCarteiras($this->_id_usuario, $id_carteira_origem, $id_carteira_destino, $valor);
    }
}
