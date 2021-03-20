<?php
include_once "../business/ganho_bus.php";
include_once "../business/carteira_bus.php";
include_once "../controller/session_controller.php";
include_once "../controller/imagem_controller.php";

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

    $ganho = new Ganhos($_SESSION['id_usuario']);


    try {
        //Create and Update
        if (isset($_POST['titulo'])) {

            $obj_ganho = new Ganho();
            $obj_ganho->titulo = $_POST['titulo'];
            $obj_ganho->descricao = $_POST['descricao'];
            $obj_ganho->categoria = $_POST['categoria'];
            $obj_ganho->data_do_credito = $_POST['data'];
            $obj_ganho->valor = $_POST['valor'];
            $obj_ganho->id_carteira = $_POST['carteira'];
            if (isset($_POST['icone'])) {
                $obj_ganho->icone = $_POST['icone'];
            }
            //Save image
            if (isset($_FILES['file'])) {
                $img = new ImageController();
                $obj_ganho->comprovante = $img->salvar('file', "{$_SESSION['id_usuario']}");
            }
            if (isset($_POST['id'])) {
                $obj_ganho->id_ganho = $_POST['id'];
                print_r(json_encode($ganho->atualizarganho($obj_ganho)));
                exit();
            } else {
                print_r(json_encode($ganho->salvarganho($obj_ganho)));
                exit();
            }
        }
        if (isset($_POST['funcao'])) {
            //Read
            if ($_POST['funcao'] == 'listar') {
                $parametros = ([
                    'dt_ini' => (isset($_POST['dt_ini']) ? $_POST['dt_ini'] : null),
                    'dt_fim' => (isset($_POST['dt_fim']) ? $_POST['dt_fim'] : null),
                    'categoria' => (isset($_POST['categoria']) ? $_POST['categoria'] : null),
                    'carteira' => (isset($_POST['carteira']) ? $_POST['carteira'] : null),
                ]);
                print_r(json_encode($ganho->buscarTodosGanhos($parametros)));
                exit();
            }
            //Delete
            if ($_POST['funcao'] == 'excluir' and isset($_POST['id'])) {
                print_r(json_encode($ganho->deletarGanho($_POST['id'])));
                exit();
            }
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação! {$th}"));
        exit();
    }
}

class Ganhos
{
    private $total_ganhos;
    private $_id_usuario;
    public $lista_ganhos;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function salvarGanho($obj_ganho)
    {
        return GanhoBus::createGanho($this->_id_usuario, $obj_ganho);
    }

    function buscarTodosGanhos($parametros = [])
    {
        return GanhoBus::readGanho($this->_id_usuario, null, $parametros);
    }

    function buscarGanho($id_ganho)
    {
        return GanhoBus::readGanho($this->_id_usuario, $id_ganho);
    }

    function atualizarGanho($obj_ganho)
    {
        return GanhoBus::updateGanho($obj_ganho);
    }

    function deletarGanho($id_ganho)
    {
        return GanhoBus::deleteGanho($id_ganho);
    }
    function listarCarteiras()
    {
        return CarteiraBus::carteiras($this->_id_usuario);
    }
}
