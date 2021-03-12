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
    <header class="container py-5">
        <div class="align-items-center text-center">
            <h2>Cofrin sua plataforma de controle financeiro</h2>
        </div>
    </header>
    <main class="container px-3 py-5 mt-2">
        <h3 class="">Não quer mais receber nossos e-mails? 😭</h3>
            <small class="d-block mb-3">Tudo bem, preencha o formulário abaixo para não receber mais nossos e-mails de publicidade, promoções e atualizações! </small>
        <form action="" onsubmit="event.preventDefault(); descadastraEmail();">
            <label for="email" class="mt-2 font-weight-bold">Seu e-mail de cadastro:</label>
            <input class="form-control col col-12 col-md-6" type="email" name="email" id="email" placeholder="Jorge@mail.com" <?php if (isset($_GET['email'])) {
                                                                                                                                    echo "value=\"{$_GET['email']}\"";
                                                                                                                                } ?> required>
            <label for="motivo" class="mt-2 font-weight-bold">Por qual motivo:</label>
            <textarea class="form-control col col-12 col-md-6" name="motivo" id="motivo" cols="30" rows="5" limit="400" placeholder="Eu prefiro..."></textarea>
            <small class="d-block">Campo não obrigatório!</small>
                                                                                                                        <button class="mt-5 btn btn-info btn-sm">Deixar de receber e-mail's</button>
        </form>
    </main>
    <script src="../dependencies/js/jquery-3.5.1.min.js"></script>
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