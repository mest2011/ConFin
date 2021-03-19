<?php 
include_once "./controller/log_controller.php";

$log = new Log(0);

$log->salvarLog('Cancelar e-mail');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="imagem/png" href="../dependencies/img/logo_porquinho.png" />
    <link rel="stylesheet" href="../dependencies/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dependencies/css/toastr.css">
    <link rel="stylesheet" href="view/css/style.css">
    <title>Confin - Cadastro de recebimento de e-mail </title>
</head>

<body>
<header class="">
        <section class="container container-fluid">
            <div class="mb-4">

                <nav class="navbar navbar-expand-md navbar-dark">
                    <a href="/" class="navbar-brand my-auto">
                        <div class="div-logo my-auto">
                            <h3 class="hover-green my-auto">Cofrin</h3><img class="my-auto" src="../dependencies/img/logo_porquinho.png"
                                alt="logo_porquinho">
                        </div>
                    </a>

                    <button class="navbar-toggler my-auto " type="button" data-toggle="collapse"
                        data-target="#navbarText" aria-controls="navbarText"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse my-auto" id="navbarText">
                        <ul class="navbar-nav mr-auto"></ul>
                        <ul class="navbar-nav  my-2 my-lg-0">
                            <li class="nav-item ml-4 my-md-auto my-1 m-m-2">
                                <a href="/cofrin/view/index.html?target=sobre">Sobre</a>
                            </li>
                            <li class="nav-item ml-4 my-md-auto my-1 m-m-2">
                                <a href="/cofrin/view/contato.html">Contato</a>
                            </li>
                            <a href="/cofrin/view/cadastro.html" class="ml-4 my-2 m-m-2">
                                <li class="nav-item btn green-bgcolor">Cadastre-se</li>
                            </a>
                            <a href="/cofrin/view/login.html" class="nav-item ml-4 my-2 m-m-2">
                                <li class="btn btn-green mr-auto">Entrar</li>
                            </a>

                        </ul>
                        
                    </div>
                </nav>
            </div>
            <hr>
        </section>
    </header>
    <main class="container px-3 py-5 mt-2">
        <h2 class="">Não quer mais receber <span class="green-color">nossos e-mails?</span> 😭</h2>
            <small class="d-block mb-3">Tudo bem, preencha o formulário abaixo para não receber mais nossos e-mails de publicidade, promoções e atualizações! </small>
        <form action="" onsubmit="event.preventDefault(); descadastraEmail();">
            <label for="email" class="mt-2 font-weight-bold">Seu e-mail de cadastro:</label>
            <input class="form-control col col-12 col-md-6" type="email" name="email" id="email" placeholder="Jorge@mail.com" <?php if (isset($_GET['email'])) {
                                                                                                                                    echo "value=\"{$_GET['email']}\"";
                                                                                                                                } ?> required>
            <label for="motivo" class="mt-2 font-weight-bold">Por qual motivo:</label>
            <textarea class="form-control col col-12 col-md-6" name="motivo" id="motivo" cols="30" rows="5" limit="400" placeholder="Não gosto de..."></textarea>
            <small class="d-block">Campo não obrigatório!</small>
                                                                                                                        <button class="mt-5 btn  green-bgcolor">Deixar de receber e-mail's</button>
        </form>
    </main>
    <script src="../dependencies/js/jquery-3.5.1.min.js"></script>
    <script src="../dependencies/js/bootstrap.min.js"></script>
    <script src="../dependencies/js/toastr.js"></script>
    <script>
        
        //Toastr
        toastr.options.closeButton = true;
        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 500;
        toastr.options.closeEasing = 'swing';
        toastr.options.preventDuplicates = true;
        
        const descadastraEmail = async () => {
            let email = document.getElementById('email');
            let motivo = document.getElementById('motivo');

            try {
                var formdata = new FormData();
                formdata.append("email", email.value);
                formdata.append("motivo", motivo.value);



                const response = await fetch(`controller/email_controller.php?function=descadastrar`, {
                    method: "POST",
                    body: formdata,
                });

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    toastr.success(resultJson, 'Parabéns:');
                    email.value = "";
                    motivo.value = "";
                    setTimeout(() => { window.location.href = "../" }, 5000);
                }
            } catch (error) {
                console.log(error);
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }
    </script>
</body>

</html>