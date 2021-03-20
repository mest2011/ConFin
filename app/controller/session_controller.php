<?php

class Session_security
{
    protected $_time_session;

    function __construct($type = false)
    {
        if (!isset($_SESSION)) {
            session_start();
        }


        if (!isset($_SESSION['time'])) {
            if (!$type) {
                session_destroy();
                header("LOCATION: ../../cofrin/view/login.html?erro=usuario_nao_logado");
                exit();
            } else {
                return false;
                exit();
            }
        } else {
            $this->_time_session = $_SESSION['time'];
            if (!$type) {
                $this->validaSessao($type);
            }
        }

        
    }

    function validaSessao($func)
    {
        if ($func == "isValid") {
            return $this->checkTimeSession();
            exit();
        }


        if ($this->checkTimeSession()) {
            $_SESSION['time'] = date_create();
        } else {
            session_destroy();
            header("LOCATION: ../../cofrin/view/login.html?erro=tempo_inatividade_excedido");
        }
    }


    function checkTimeSession()
    {
        $diferenca_tempo = date_interval_format(date_diff($this->_time_session, date_create()), '%i');
        if ($diferenca_tempo > 10) {
            return false;
        } else {
            return true;
        }
    }
}
