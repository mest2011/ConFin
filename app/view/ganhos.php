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

<!-- Begin emoji-picker Stylesheets -->
<link href="lib/css/emoji.css" rel="stylesheet">
    <!-- End emoji-picker Stylesheets -->

<title>Lista de ganhos do mês</title>
</head>

<body class="d-flex">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block">
        <section>
            <div class="d-block">
                <div class="d-flex mt-5 mb-3 emoji-picker-container">
                    <img class="card-icone my-auto" src="./images/ganhos.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Aqui ficam seus ganhos</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <h5 class="font-green my-auto">Recebidos</h5>
                    <div class="font-purple d-flex pr-5 align-items-center mb-3">
                        <small class="mx-2">Filtrar por:</small>
                        <select class="container-transactions-select p-1" name="filtro" id="filter">
                            <option value="Data">Data</option>
                            <option value="Valor">Valor</option>
                            <option value="Carteira">Carteira</option>
                        </select>
                        <a class="hover-alter ml-3" href="../view/cad_ganho.php">
                            <img class="container-transactions-add hover-off my-auto" src="./images/plus.png" alt="">
                            <img class="container-transactions-add my-auto hover-on" src="./images/plus-green.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-transactions bg-gray d-flex">
            <div class="col-md-1 col-sm-2"></div>

            <div class="col-md-11 col-sm-10 p-4">
                <?php
                $render = "";
                $result = $ganho->buscarTodosGanhos();
                if (gettype($result) === "array") {
                    foreach ($result as $key => $value) {
                        $date = date_create($value['data_do_credito']);
                        $render .= "
                        <div class=\"cartao pointer p-3 mr-4 d-block  my-4\" onclick=\"editar({$value['id_ganho']})\" title=\"trabalho\" >
                            <div class=\"d-flex w-100\">
                                <input type=\"text\" class=\"cartao my-auto p-2 mx-2\" name=\"icon\" value=\"💰\" onclick=\"document.execCommand('91')\">
                                <div class=\"my-auto d-flex w-100 justify-content-between\">
                                    <div>
                                        <h4 class=\"my-auto font-purple\">" . $value['titulo'] . "</h4>
                                        <small class=\"my-auto font-gray\">Data do recebimento: " . date_format($date, 'd/m/Y') . "</small>
                                    </div>
                                    <div class=\"my-auto\">
                                        <h4 class=\"font-green number my-auto mr-5\">R$" . number_format($value['valor'], 2, ',', '.') . "</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class=\"my-4\">
                            <div class=\"m-2 pr-4\">
                                <div class=\"d-flex w-100 justify-content-between my-auto\">
                                    <p class=\"font-weight-bold my-auto\">Descrição:</p>
                                    <p class=\"font-white bg-purple p-2 rounded my-auto\">" . $value['nome_carteira'] . "</p>
                                </div>
                                <p class=\"font-gray\">" . $value['descricao'] . "</p>
                            </div>

                        </div>";
                    }
                } else {
                    $render .= "";
                }
                echo $render;
                ?>


            </div>


            </div>
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