<?php

class Session_security
{
    protected $_time_session;

    function __construct()
    {
        
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }

        if (!isset($_SESSION['time'])) {
            session_destroy();
            header("LOCATION: ../../site/home_page/index.php?erro=usuario_nao_logado");
        }

        $this->_time_session = $_SESSION['time'];

        if ($this->checkTimeSession()) {
            $_SESSION['time'] = date_create();
        }else{
            session_destroy();
            header("LOCATION: ../../site/home_page/index.php?erro=tempo_inatividade_excedido");
        }
    }

    function checkTimeSession()
    {
        $diferenca_tempo = date_interval_format(date_diff($this->_time_session, date_create()), '%i');
        if($diferenca_tempo > 10) {
            return false;
        }else{
            return true;
        }
    }
}
