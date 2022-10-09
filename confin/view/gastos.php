<link rel="stylesheet" href="css/bootstrap.min.css" >

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/gastos_controller.php";

$obj_gastos = new Gastos($_SESSION['id_usuario']);


// excluir gasto
if (isset($_GET['id'])) {
    echo "<script>alert('{$obj_gastos->excluir($_GET['id'])}')</script>";
    $url = strpos($_SERVER["REQUEST_URI"], "?");
    $url = substr($_SERVER["REQUEST_URI"], 0, $url);
    header("Location: {$url}");
}

?>




<link rel="stylesheet" href="../view/css/table.css">

<title>Lista de gastos mensais</title>
</head>

<body>
    <section class="body main">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <h1>Gastos do mês</h1>
            <?php
            $render = "
            <table class='table table-hover' data-tabela-gastos>
                <thead>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data do débito</th>
                    <th>Carteira</th>
                    <th><a href='../view/cad_gasto.php' class='btn btn-success' title='Novo gasto'>+</a></th>
                </thead>
            <tbody>";
            $result = $obj_gastos->lista_gastos();
            if (gettype($result) === "array") {
                foreach ($result as $key => $value) {
                    $date = date_create($value['data_do_debito']);
                    $render .= "
                        
                        <tr title='" . $value['descricao'] . "'>
                            <td>" . $value['tipo'] . "</td>
                            <td>" . $value['titulo'] . "</td>
                            <td>" . $value['descricao'] . "</td>
                            <td>R$ -" . number_format($value['valor'], 2, ',', '.') . "</td>
                            <td>" . date_format($date, 'd/m/Y') . "</td>
                            <td>" . $value['nome_carteira'] . "</td>
                            <td><button class=\"btn btn-warning\" onclick=\"editar({$value['id_despesa']})\">Editar</button>
                            <button class=\"btn btn-danger\" onclick=\"excluir({$value['id_despesa']})\">Excluir</button></td>
                        </tr>";
                }
                $render .= "</tbody></table>";
            } else {
                $render .= "<tr><td colspan='7'><b>" . $result . "</td></tr></b>";
            }
            echo $render;
            ?>
        </section>
    </section>
    <script>
        function excluir(id) {
            if (confirm("Deseja mesmo excluir esse gasto?")) {
                window.location.href = window.location.href + `?id=${id}`
            }
        }

        function editar(id) {
            window.location.href = `cad_gasto.php?id=${id}`
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js" ></script>
    <script src="js/popper.min.js" ></script>
    <script src="js/bootstrap.min.js" ></script>
    <?php include "imports/js.php";?>
</body>

</html>