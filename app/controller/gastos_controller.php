<?php
include_once "../business/gasto_bus.php";
include_once "../business/carteira_bus.php";
include_once "../controller/session_controller.php";

if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['titulo'], $_POST['data'], $_POST['carteira'], $_POST['descricao'], $_POST['valor'], $_POST['categoria']) or isset($_POST['funcao'])) {


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

    $gasto = new Gastos($_SESSION['id_usuario']);

    try {
        //Create and Update
        if (isset($_POST['titulo'])){

            $obj_gasto = new Gasto();
            $obj_gasto->titulo = $_POST['titulo'];
            $obj_gasto->descricao = $_POST['descricao'];
            $obj_gasto->categoria = $_POST['categoria'];
            $obj_gasto->data = $_POST['data'];
            $obj_gasto->valor = $_POST['valor'];
            $obj_gasto->id_carteira = $_POST['carteira'];
            if(isset($_POST['icone'])){
                $obj_gasto->icone = $_POST['icone'];
            }
            if (isset($_POST['id'])) {
                $obj_gasto->id_despesa = $_POST['id'];
                print_r(json_encode($gasto->atualizarGasto($obj_gasto)));
                exit();
            } else {
                print_r(json_encode($gasto->salvarGasto($obj_gasto)));
                exit();
            }
        }
        if (isset($_POST['funcao'])) {
            //Read
            if ($_POST['funcao'] == 'listar') {
                print_r(json_encode($gasto->lista_gastos()));
                exit();
            }
            //Delete
            if ($_POST['funcao'] == 'excluir' and isset($_POST['id'])) {
                print_r(json_encode($gasto->excluir($_POST['id'])));
                exit();
            }
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação!"));
        exit();
    }

    
}



class Gastos
{
    private $total_gastos;
    private $_id_usuario;
    public $lista_gastos;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function total_de_gastos()
    {
        $this->total_gastos = GastoBus::buscarTotalGastos($this->_id_usuario);
        return $this->total_gastos;
    }

    function lista_gastos()
    {
        if (strlen($this->lista_gastos) == 0 || $this->lista_gastos == null || $this->lista_gastos == "") {
            $this->lista_gastos = GastoBus::buscarListaGastosMes($this->_id_usuario);
        }
        return $this->lista_gastos;
    }

    function retorna_gasto($id)
    {
        return GastoBus::buscaGasto($id, $this->_id_usuario);
    }

    function lista_gastos_futuros()
    {
        return GastoBus::buscarListaGastosFuturos($this->_id_usuario);
    }

    function excluir($id)
    {
        return GastoBus::excluirGasto($id);
    }

    function salvarGasto($obj_gasto)
    {
        return GastoBus::salvarNovoGasto($this->_id_usuario, $obj_gasto);
    }

    function atualizarGasto($obj_gasto)
    {
        return GastoBus::atualizaGasto($obj_gasto);
    }

    function listarCarteiras()
    {
        return CarteiraBus::carteiras($this->_id_usuario);
    }
}
