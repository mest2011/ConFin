<?php
include_once '../home_page/imports.html';
echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
include_once '../home_page/header.html';
echo "<main>";
include_once "../controller/cadastro_controller.php";
include_once "../controller/recover_password_controller.php";
require_once "../business/recaptchalib.php";


$rst =  new ResetPsw();

if (isset($_SESSION['user_id'], $_POST['senha'])) {
    if ($rst->alterarSenha($_POST['senha'])) {
        echo "<script>alert('Sua senha foi alterada com sucesso!');
        window.location.href = '../../cofrin/login.html';</script>";
    } else {
        echo "<script>alert('Erro ao alterar a senha!')</script>";
    }
}
if (isset($_GET['token'])) {
    if ($rst->validaToken($_GET['token'])) {

        $render = "<h1>Insira abaixo a senha nova</h1>
        
        <form id=\"form-reset-psw\" action=\"\" method=\"POST\" class=\"form\">
            <label for=\"senha\">Senha:</label>
            <input type=\"password\" id=\"senha\" name=\"senha\" placeholder=\"insira a nova senha\" minlength=\"4\" maxlength=\"20\" required>
            <small>Use uma senha forte com minúsculas, maiúsculas e números!</small>
            <input id=\"btn-enviar\" type=\"submit\" value=\"Alterar senha\">
        </form>";
    } else {
        echo "<script>alert('Token inválido!');
        window.location.href = '../../cofrin/login.html';</script>";
    }
} else {
    $render = "<h1>Esqueceu a senha?</h1>
    <p>Informe os dados abaixo e encaminharemos para seu e-mail, um link de restauração!</p>
    <form id=\"form-reset-psw\" action=\"\" method=\"POST\" class=\"form\">
        <label for=\"email\">E-mail:</label>
        <input type=\"email\" id=\"email\" name=\"email\" placeholder=\"meu.email@email.com\" required>
        <small>informe o email usado no cadastro!</small>
        <div class=\"g-recaptcha\" data-sitekey=\"6LfM9ygaAAAAAMLYKoppF72N_FrEkQVwf1p9MnRD\" data-theme=\"dark\"></div>
        <input id=\"btn-enviar\" type=\"submit\" value=\"Solicitar alteração de senha\">
    </form>";
}
if (isset($_POST['email'])) {

    // your secret key
    $secret = "6LfM9ygaAAAAAOawy8mYL-P3ij7Jd-kgFqNRAo6J";

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
            echo "<script>alert('Foi encaminhado um e-mail para sua caixa de mensagens com o procedimento de restauração da senha!');
            window.location.href = '../../cofrin/login.html';</script>";
        } else {
            echo "<script>alert('O e-mail informado não esta vinculado a nenhum usuario cadastrado! \\n\\nCrie um novo usuario\\n ou \\nNos envie seus nome e usuario por email para analisarmos. \\n\\n contato@mesttech.com.br')</script>";
        }
    } else {
        echo "<script>alert('Por favor, marque o campo de eu não sou um robo!');
    window.location.href = '../view/recover_password.php';</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupere sua senha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background-image: linear-gradient(180deg, #44BBA4, #393E41);
        }

        #main {
            margin: 7vw 20vh;
            padding: 6.5vh 6.5vw;
            background-color: #fafafa;
        }

        #form-reset-psw {
            display: grid;
        }

        #btn-enviar {
            font-size: 0.8vw;
            width: 30vw;
            margin: 5vh auto;
        }
    </style>
</head>

<body>
    <main>
        <div id="main">
            <?php echo $render; ?>

        </div>
    </main>









    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php
    echo "</main>";
    include_once '../home_page/footer.html';
    ?>