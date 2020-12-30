<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/ganho_controller.php";

$ganho = new GanhoController($_SESSION['id_usuario']);


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

<body>
    <section class="body main">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <h1>Ganhos do mês</h1>
            <?php
            $render = "
            <table class='table table-hover' data-tabela-gastos>
                <thead>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data do crédito</th>
                    <th></th>
                </thead>
            <tbody>";
            $result = $ganho->buscarTodosGanhos();
            if (gettype($result) === "array") {
                foreach ($result as $key => $value) {
                    $date = date_create($value['data_do_credito']);
                    $render .= "
                        
                        <tr title='" . $value['descricao'] . "'>
                            <td>" . $value['tipo'] . "</td>
                            <td>" . $value['titulo'] . "</td>
                            <td>" . $value['descricao'] . "</td>
                            <td>R$ " . number_format($value['valor'], 2, ',', '.') . "</td>
                            <td>" . date_format($date, 'd/m/Y') . "</td>
                            <td><button class=\"btn btn-warning\" onclick=\"editar({$value['id_ganho']})\">Editar</button>
                            <button class=\"btn btn-danger\" onclick=\"excluir({$value['id_ganho']})\">Excluir</button></td>
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
            if (confirm("Deseja mesmo excluir esse ganho?")) {
                window.location.href = window.location.href + `?id=${id}`
            }
        }

        function editar(id) {
            window.location.href = `cad_ganho.php?id=${id}`
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php include "imports/js.php";?>
</body>

</html>