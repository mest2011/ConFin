<link rel="stylesheet" href="css/bootstrap.min.css" >
<?php include "imports/head_parameters.php"; ?>  
<?php
include_once "../classes/Carteira.php";
include_once "../controller/carteira_controller.php";

if (isset($_GET['id'])) {
    $carteira = new Carteira();
    $carteira_controller = new Carteiras($_SESSION['id_usuario']);
    $carteira = $carteira_controller->buscarcarteira($_GET['id']);
}

if (isset($_POST['nome_carteira'])) {
    $carteira = new Carteira();
    if (isset($_POST['id_carteira'])) {
        $carteira->nome_carteira = $_POST['nome_carteira'];
        $carteira->id_carteira = $_POST['id_carteira'];
        $carteira->id_usuario = $_SESSION['id_usuario'];

        $carteira_controller = new Carteiras($carteira->id_usuario);
        echo "<script>var saveStatus ='".$carteira_controller->atualizarcarteira($carteira)."';</script>";
    } else {

        $carteira->nome_carteira = $_POST['nome_carteira'];
        $carteira->id_carteira = 0;
        $carteira->saldo = 0;
        $carteira->id_usuario = $_SESSION['id_usuario'];

        $carteira_controller = new Carteiras($carteira->id_usuario);
        echo "<script>var saveStatus ='".$carteira_controller->salvarcarteira($carteira)."';</script>";
    }
}


?>

    <link rel="stylesheet" href="../view/css/form.css">

    <title>Lista de carteira mensais</title>
    <style>
        form {
            display: grid;
        }

        label {
            font-weight: 900;
        }
    </style>
</head>

<body>
    <section class="body">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <header>
                <h1>Cadastro de carteiras</h1>
            </header>
            <main>
                <form id="form-carteira" method="POST" action="cad_carteira.php" class="form form-group">
                    <?php if (isset($_GET['id'])) echo "<input type='text'  name='id_carteira' style='display:none;' value='{$carteira->id_carteira}'>" ?>
                    <label class="form-check-label" for="fnome-carteira">Nome da carteira</label>
                    <input class="form-control" id="fnome-carteira" type="text" value="<?php if (isset($_GET['id'])) echo $carteira->nome_carteira; ?>" placeholder="Insira o nome da carteira" name="nome_carteira" maxlength="30" required></Input>
                    
                    <span id="saveStatus" class="alert "></span>
                    <button name="submit" class="bnt btn-success">Salvar</button>
                </form>
            </main>
        </section>
    </section>
    <footer></footer>


    <script>
        var span = document.getElementById('saveStatus');
        if(typeof saveStatus == "string"){
            span.innerText = saveStatus;
            if(saveStatus.search("sucesso") > -1){
                span.classList.add('alert-success');
            }else{
                span.classList.add('alert-danger');
            }
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js" ></script>
    <script src="js/popper.min.js" ></script>
    <script src="js/bootstrap.min.js" ></script>
    <?php include "imports/js.php";?>
</body>

</html>