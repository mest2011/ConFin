<?php
include_once "../controller/session_controller.php";
include_once "../business/meta_bus.php";

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

    //criar user de teste
    //$_SESSION['id_usuario'] = 1;

    $meta = new Metas($_SESSION['id_usuario']);


    try {
        //Create and Update
        if ($_POST['funcao'] == "salvar") {

            if (!isset(
                $_POST['id_carteira'],
                $_POST['titulo'],
                $_POST['descricao'],
                $_POST['valor'],
                $_POST['dt_limite']
            )) {
                print_r(json_encode("Erro : Faltam informações no formulário!"));
                exit();
            }

            $obj_meta = new Meta();
            $obj_meta->id_carteira = $_POST['id_carteira'];
            $obj_meta->titulo = $_POST['titulo'];
            $obj_meta->descricao = $_POST['descricao'];
            $obj_meta->valor = $_POST['valor'];
            $obj_meta->dt_limite = $_POST['dt_limite'];

            if (isset($_POST['id_meta'])) {
                $obj_meta->id_meta = $_POST['id_meta'];
                print_r(json_encode($meta->atualizarMeta($obj_meta)));
                exit();
            } else {
                print_r(json_encode($meta->salvarMeta($obj_meta)));
                exit();
            }
        }
        //Read
        if ($_POST['funcao'] == 'listar') {
            print_r(json_encode($meta->buscarTodasMetas()));
            exit();
        }
        //Delete
        if ($_POST['funcao'] == 'excluir' and isset($_POST['id_meta'])) {
            print_r(json_encode($meta->deletarMeta($_POST['id_meta'])));
            exit();
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação! {$th}"));
        exit();
    }
}

class Metas
{
    private $_id_usuario;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function salvarMeta($obj_meta)
    {
        $obj_meta->id_usuario = $this->_id_usuario;
        return MetaBus::salvar($obj_meta);
    }

    function buscarTodasMetas()
    {
        return MetaBus::listar($this->_id_usuario);
    }

    function atualizarMeta($obj_meta)
    {
        $obj_meta->id_usuario = $this->_id_usuario;
        return MetaBus::salvar($obj_meta);
    }

    function deletarMeta($id_ganho)
    {
        return MetaBus::deletar($id_ganho);
    }
}
