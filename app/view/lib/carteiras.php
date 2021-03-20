<link rel="stylesheet" href="css/bootstrap.min.css" >

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/carteira_controller.php";

$carteira = new Carteiras($_SESSION['id_usuario']);


// excluir carteira
if (isset($_GET['id'])) {
    print_r($carteira->deletarCarteira($_GET['id']));
    $url = strpos($_SERVER["REQUEST_URI"], "?");
    $url = substr($_SERVER["REQUEST_URI"], 0, $url);
    header("Location: {$url}");
}

?>




<link rel="stylesheet" href="../view/css/table.css">

<title>Lista de carteiras</title>
</head>

<body>
    <section class="body main">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <h1>Carteiras</h1>
            <?php
            $render = "
            <table class='table table-hover' data-tabela-carteiras>
                <thead>
                    <th>Nome da carteira</th>
                    <th>Saldo</th>
                    <th>Data da criação</th>
                    <th>Última transação</th>
                    <th></th>
                </thead>
            <tbody>";
            $result = $carteira->buscarTodoscarteiras();
            if (gettype($result) === "array") {
                foreach ($result as $key => $value) {
                    $date_create = date_create($value['data_criacao']);
                    $date_alter = date_create($value['data_update']);
                    $render .= "
                        
                        <tr title='" . $value['nome_carteira'] . "'>
                            <td>" . $value['nome_carteira'] . "</td>
                            <td>R$ " . number_format($value['saldo'], 2, ',', '.') . "</td>
                            <td>" . date_format($date_create, 'd/m/Y') . "</td>
                            <td>" . date_format($date_alter, 'd/m/Y') . "</td>
                            <td><button class=\"btn btn-warning\" onclick=\"editar({$value['id_carteira']})\">Editar</button>
                            <button class=\"btn btn-danger\" onclick=\"excluir({$value['id_carteira']})\">Excluir</button></td>
                        </tr>";
                }
                $render .= "</tbody></table>";
            } else {
                $render = "<b>" . $result . "</b>";
            }
            echo $render;
            ?>
        </section>
    </section>
    <script>
        function excluir(id) {
            if (confirm("Deseja mesmo excluir esse carteira?")) {
                window.location.href = window.location.href + `?id=${id}`
            }
        }

        function editar(id) {
            window.location.href = `cad_carteira.php?id=${id}`
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js" ></script>
    <script src="js/popper.min.js" ></script>
    <script src="js/bootstrap.min.js" ></script>
    <?php include "imports/js.php";?>
</body>

</html>