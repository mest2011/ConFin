<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/ganho_controller.php";

$ganho = new Ganhos($_SESSION['id_usuario']);


// excluir ganho
if (isset($_GET['id'])) {
    print_r($ganho->deletarGanho($_GET['id']));
    $url = strpos($_SERVER["REQUEST_URI"], "?");
    $url = substr($_SERVER["REQUEST_URI"], 0, $url);
    header("Location: {$url}");
}

?>




<link rel="stylesheet" href="../view/css/table.css">

<title>Lista de ganhos do mês</title>
</head>

<body class="row">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block">
        <section>
            <div class="d-block">
                <div class="d-flex my-5">
                    <img class="card-icone my-auto" src="./images/ganhos.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Aqui ficam seus ganhos</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="font-green">Recebidos</p>
                    <div class="font-purple d-flex pr-5 align-items-center">
                        <small class="mx-2">Filtrar por:</small>
                        <select name="filtro" id="filter">
                            <option value="Data">Data</option>
                            <option value="Valor">Valor</option>
                            <option value="Carteira">Carteira</option>
                        </select>
                        <small class="mx-2">Download</small>
                    </div>
                </div>
            </div>
        </section>
        <section>

        </section>
    </main>
    <script>
        function excluir(id) {
            if (confirm("Deseja mesmo excluir esse ganho?")) {
                window.location.href = window.location.href + `?id=${id}`
            }
        }

        function editar(id) {
            window.location.href = `cad_ganho.php?id=${id}`
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php include "imports/js.php"; ?>
</body>

</html>