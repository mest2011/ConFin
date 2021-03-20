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
            $parametros = ([ 
                    'dt_ini' => (isset($_POST['dt_ini'])?$_POST['dt_ini']:null),
                    'dt_fim' => (isset($_POST['dt_fim'])?$_POST['dt_fim']:null),
                    'categoria' => (isset($_POST['categoria'])?$_POST['categoria']:null),
                    'carteira' => (isset($_POST['carteira'])?$_POST['carteira']:null),
            ]);

            print_r(json_encode($extratos->buscar($parametros)));
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

    function buscar($parametros = [])
    {
        return ExtratoBus::buscar($this->_id_usuario, $parametros);
    }
}
