<?php
include_once "../controller/recover_password_controller.php";
require_once "../business/recaptchalib.php";


$rst =  new ResetPsw();

//Redirect vindo pelo link de email de reset
if (isset($_GET['token'])) {
    header("LOCATION: ../view/recover.html?token={$_GET['token']}");
}

if (isset($_POST['token'])) {
    if ($rst->validaToken($_POST['token'])) {
        if (isset($_SESSION['user_id'], $_POST['senha'])) {
            if ($rst->alterarSenha($_POST['senha'])) {
                print_r(json_encode("Sua senha foi alterada com sucesso!"));
            } else {
                print_r(json_encode("Erro ao alterar a senha! Tente mais tarde!"));
            }
        }
    } else {
        print_r(json_encode("Erro: Token inválido!"));
    }
}



if (isset($_POST['email'])) {

    // your secret key
    $secret = "6LfM9ygaAAAAAOawy8mYL-P3ij7Jd-kgFqNRAo6J-dhsfhsdfhsgfdsh";

    // empty response
    $response = null;

    // check secret key
    $reCaptcha = new ReCaptcha($secret);

    // if submitted check response
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }

    if ($response != null && $response->success) {

        if ($rst->solicitaAlteracaoSenha($_POST['email'])) {   
            print_r(json_encode("Foi encaminhado um e-mail para sua caixa de mensagens com o procedimento de restauração da senha!"));
        } else {
            print_r(json_encode("Erro: O e-mail informado não esta vinculado a nenhum usuario cadastrado! <br/>Crie um novo usuario ou nos envie seu nome solicitando por email a restauracao, para analisarmos. <br/> contato@mesttech.com.br"));
        }
    } else {
        print_r(json_encode("Erro: Por favor, marque o campo de eu não sou um robo! <br/>Isso se voce não for um robo!"));
    }
}
