<?php
include_once "../business/log_bus.php";

if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['id_usuario'])) {

    
    $log = new Log($_POST['id_usuario']);
    

    try {
        //Create
        if (isset($_POST['decricao'])){
            print_r(json_encode($log->salvarLog($_POST['decricao'])));
            exit();
        }
        if (isset($_POST['parametro']) or isset($_POST['limit'])) {
            //Read
            if (isset($_POST['limit'])) {
                print_r(json_encode($log->buscarTodosLog($_POST['limit'])));
                exit();
            }
            if (isset($_POST['parametro'])) {
                print_r(json_encode($log->buscarLog(100, $_POST['parametro'])));
                exit();
            }
        }
    } catch (\Throwable $th) {
        print_r(json_encode("Erro : Erro no processamento da solicitação!"));
        exit();
    }    
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


class Log{
    private $_id_usuario;

    function __construct($id_usuario){
        $this->_id_usuario = $id_usuario;
    }

    function salvarLog($descricao){
        return LogBus::createLog($this->_id_usuario, $descricao, get_client_ip());
    }

    function buscarTodosLog($limit = 100){
        return LogBus::readLog($this->_id_usuario, $limit);
    }

    function buscarLog($parametro, $limit = 100){
        return LogBus::readLog($this->_id_usuario, $limit, $parametro);
    }
}
