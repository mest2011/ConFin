<link rel="stylesheet" href="css/bootstrap.min.css">
<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/carteira_controller.php";
$carteiraController = new Carteiras($_SESSION['id_usuario']);

if (isset($_POST['carteira-origem'], $_POST['carteira-destino'], $_POST['valor'])) {

    echo "<script>var saveStatus ='" . $carteiraController->transferir($_POST['carteira-origem'], $_POST['carteira-destino'], $_POST['valor']) . "';</script>";
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

    .form-wrap {

        display: flex;
        flex-wrap: wrap;
    }

    .form-wrap select {
        height: auto !important;

    }

    .carteira-origem,
    .carteira-destino {
        margin: 0 0 10px -35px;
        border-radius: 56px;
        padding: 0 10px 15px 0px;
    }
</style>
</head>

<body>
    <section class="body">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <header>
                <h1>Tranferência de saldo</h1>
            </header>
            <main>
                <form id="form-carteira" method="POST" action="tranferenciaCarteira.php" class="form form-group">
                    <div class="form-wrap">
                        <div class="carteira-origem">
                            <label class="form-check-label" for="fnome-carteira-origem">Carteira de origem</label>
                            <select class="form-control" id="fnome-carteira-origem" name="carteira-origem" required>
                                <?php
                                $carteiraController = new Carteiras($_SESSION['id_usuario']);

                                foreach ($carteiraController->listarCarteiras() as $key => $value) {
                                    echo "<option value=\"{$value['id_carteira']}\" ";
                                    echo ">{$value['nome_carteira']} Saldo:{$value['saldo']}</option>";
                                }
                                ?>

                            </select>
                            <label class="form-check-label" for="fnome-carteira-destino">Carteira de destino</label>
                            <select class="form-control" id="fnome-carteira-destino" name="carteira-destino" required>
                                <?php
                                $carteiraController = new Carteiras($_SESSION['id_usuario']);

                                foreach ($carteiraController->listarCarteiras() as $key => $value) {
                                    echo "<option value=\"{$value['id_carteira']}\" ";
                                    echo ">{$value['nome_carteira']} Saldo:{$value['saldo']}</option>";
                                }
                                ?>

                            </select>
                            <label class="form-check-label" for="fvalor-destino">Valor</label>
                            <input class="form-control" id="fvalor-destino" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->valor; ?>" pattern="[0-9]{0,3}?.?[0-9]{0,3}?.?[0-9]{1,3},?[0-9]{0,2}" placeholder="Valor: R$ 00,00" name="valor" required>
                        </div>
                    </div>
                    <span id="saveStatus" class="alert "></span>
                    <button name="submit" class="bnt btn-success">Salvar</button>
                </form>
            </main>
        </section>
    </section>
    <footer></footer>


    <script>
        var span = document.getElementById('saveStatus');
        if (typeof saveStatus == "string") {
            span.innerText = saveStatus;
            if (saveStatus.search("sucesso") > -1) {
                span.classList.add('alert-success');
            } else {
                span.classList.add('alert-danger');
            }
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php include "imports/js.php"; ?>
</body>

</html>