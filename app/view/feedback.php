<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<?php

include_once "../classes/Feedback.php";
include_once "../controller/feedback_controller.php";

if (isset($_POST['descricao'])) {
    $obj_feedback = new Feedback();

    $obj_feedback->id_usuario = $_SESSION['id_usuario'];
    $obj_feedback->titulo = $_POST['titulo'];
    $obj_feedback->descricao = $_POST['descricao'];
    if (isset($_POST['anonimo'])) {
        $obj_feedback->anonimo = 1;
    } else {
        $obj_feedback->anonimo = 0;
    }


    $feedback_controller = new FeedbackController();
    echo "<script>var saveStatus ='" . $feedback_controller->salvar($obj_feedback) . "';</script>";
}




?>
<title>Deixe seu feedback!</title>
<link rel="stylesheet" href="../view/css/form.css">
</head>

<body class="d-flex flex-wrap">
    <header class="col-md-1 col-sm-2 col-12">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 col-12 d-block">
        <h2 class="my-4 font-purple">Feedback</h2>
        <h4 class="font-weight-bold mt-3 mb-5 font-purple">Diga-nos <span class="font-green">o que achou!</span></h4>
        <form method="POST" action="feedback.php">
            <label class="form-check-label font-weight-bold mt-3 mb-1 font-purple" for="ftitulo">Titulo</label>
            <input class="form-control col-12 col-md-5" id="ftitulo" type="text" name="titulo" placeholder="Titulo" maxlength="20"></Input>
            <label class="form-check-label font-weight-bold mt-3 mb-1 font-purple" for="fdescricao" title="Diga o que você acha sobre nós">Descrição</label>
            <textarea class="form-control col-12 col-md-5" id="fdescricao" name="descricao" cols="60" rows="5" placeholder="Deixe sua dica, sugestão de melhoria, ajuste, função, elogio..." maxlength="500" title="Diga o que você acha sobre nós" required></textarea>
            <label class="form-check-label font-weight-bold mt-3 mb-1 font-purple" for="fanonimo">Anônimo</label>
            <div class="d-flex align-items-baseline line-height-0 my-1">
                <input class="mr-1" id="fanonimo" type="checkbox" name="anonimo">
                <p class="disabled">Enviar o comentario anonimamente?</p>
            </div>
            <span id="saveStatus" class="alert d-block mb-5 col-12 col-md-5"></span>


            <button name="submit" class="btn btn-success my-4">Enviar</button>

        </form>
    </main>
    <?php include "imports/js.php"; ?>
    <script>
        //Toastr
        toastr.options.closeButton = true;
        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 300;
        toastr.options.closeEasing = 'swing';
        toastr.options.preventDuplicates = true;

        function updateTextRecorrente() {
            let val = document.getElementById('frecorrente').value;
            document.getElementById('spanRecorrente').innerText = "A despesa se repete por: " + val + " mes(es)";
        }
        var span = document.getElementById('saveStatus');
        if (typeof saveStatus == "string") {
            span.innerText = saveStatus;
            if (saveStatus.search("sucesso") > -1) {
                toastr.success(saveStatus, 'Sucesso!');
                span.classList.add('alert-success');
            } else {
                toastr.error(saveStatus, 'Ops!');
                span.classList.add('alert-danger');
            }
        }
    </script>
</body>

</html>