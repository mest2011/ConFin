<?php
include_once "../business/extrato_bus.php";
include_once "../controller/session_controller.php";

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

    $extratos = new Extratos($_SESSION['id_usuario']);


    try {
        //Read
        if ($_POST['funcao'] == 'listar') {
            if (isset($_POST['dt_ini'], $_POST['dt_fim'])) {
                print_r(json_encode($extratos->buscar($_POST['dt_ini'], $_POST['dt_fim'])));
                exit();
            }

            print_r(json_encode($extratos->buscar()));
            exit();
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação!"));
        exit();
    }
}




class Extratos
{
    private $_id_usuario;

    function __construct($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    function buscar($dt_inicio = "0", $dt_fim = "0")
    {
        return ExtratoBus::buscar($this->_id_usuario, $dt_inicio = "0", $dt_fim = "0");
    }
}
