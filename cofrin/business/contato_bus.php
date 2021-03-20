<?php

include_once "../database/crud.php";

class ContatoBus extends Crud{
    static function salvarNovo($obj_contato){
        //tamanho muito grande
        if(strlen($obj_contato->nome) > 50) return "Erro: Campo nome excede tamanho aceito!";
        if(strlen($obj_contato->email) > 50) return "Erro: Campo e-mail excede tamanho aceito!";
        if(strlen($obj_contato->mensagem) > 500) return "Erro: Campo mensagem excede tamanho aceito!";
        //tamanho muito curto
        if(strlen($obj_contato->nome) < 5) return "Erro: O campo nome é muito curto!";
        if(strlen($obj_contato->email) < 5) return "Erro: O campo e-mail é muito curto!";
        if(strlen($obj_contato->mensagem) < 10) return "Erro: O campo mensagem é muito curto!";



        $sql = "INSERT INTO `tb_contato` (`nome`, `email`, `mensagem`) VALUES ('{$obj_contato->nome}', '{$obj_contato->email}', '{$obj_contato->mensagem}');";

        if (parent::create($sql)) {
            return "Sua mensagem foi recebida! Entraremos em contato em breve!";
        }else{
            return "Erro ao salvar sua mensagem! Tente mais tarde ou envie um e-mail para contato@cofrin.mesttech.com.br";
        }
    }
}