<?php
include_once "../class/contato.php";
include_once "../business/contato_bus.php";

//End point
if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    $contato = new Contato();

    $contato->nome = $_POST['name']; 
    $contato->email = $_POST['email']; 
    $contato->mensagem = $_POST['message']; 

    $contatoCon = new Contato_controller();
    print_r(json_encode($contatoCon->salvar($contato)));
}



class Contato_controller{
    function salvar($obj_contato){
        return ContatoBus::salvarNovo($obj_contato);
    }
}
