<link rel="stylesheet" href="css/bootstrap.min.css" >

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/gastos_controller.php";

$obj_gastos = new Gastos($_SESSION['id_usuario']);


// excluir gasto
if (isset($_GET['id'])) {
    print_r($obj_gastos->excluir($_GET['id']));
    $url = strpos($_SERVER["REQUEST_URI"], "?");
    $url = substr($_SERVER["REQUEST_URI"], 0, $url);
    //header("Location: {$url}");
}

?>




<link rel="stylesheet" href="../view/css/table.css">

<title>Lista de gastos mensais</title>
</head>

<body class="row">
<header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block">
        <div class="d-block">
            <div class="d-flex my-5">
                <img class="card-icone my-auto" src="./images/ganhos.png" alt="">
                <h4 class="font-purple my-auto ml-2">Aqui ficam os seus gastos</h4>
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
        <section class="main conteudo">
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