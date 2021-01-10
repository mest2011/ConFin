<link rel="stylesheet" href="css/bootstrap.min.css" >

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/extrato_controller.php";
include_once "../controller/gastos_controller.php";
include_once "../controller/ganho_controller.php";

$ganho = new Ganhos($_SESSION['id_usuario']);
$gastos = new Gastos($_SESSION['id_usuario']);

$extrato = new ExtratoController($_SESSION['id_usuario']);


?>




<link rel="stylesheet" href="../view/css/table.css">

<title>Lista de extratos do mês</title>
</head>

<body>
    <section class="body main">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <h1>Extrato</h1>
            <?php
            $render = "
            <table id=\"tabela\" class='table table-hover' data-tabela-gastos>
                <thead>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data da transação</th>
                    <th><button onclick=\"xlsx()\" class=\"btn btn-primary\"><img src=\"images/btn-download.png\" width=\"30px\"></button></th>
                </thead>
            <tbody>";
            $result = $extrato->ver();
            if (gettype($result) === "array") {
                foreach ($result as $key => $value) {
                    $date = date_create($value->data);
                    $render .= "
                        
                        <tr title='" . $value->titulo . "'>
                            <td>" . $value->categoria . "</td>
                            <td>" . $value->titulo . "</td>
                            <td>" . $value->descricao . "</td>
                            <td>R$ " . $value->valor . "</td>
                            <td>" . date_format($date, 'd/m/Y') . "</td>
                            <td></td>
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
        function xlsx() {
            try {
                var wb = XLSX.utils.table_to_book(document.getElementById('tabela'), {
                    sheet: "planilha",
                    raw: true
                });

                var wscols = [{
                        wch: 15
                    },
                    {
                        wch: 15
                    },
                    {
                        wch: 15
                    },
                    {
                        wch: 15
                    },
                    {
                        wch: 15
                    }
                ];

                wb["Sheets"]["planilha"]["!cols"] = wscols;

                var wbout = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'binary'
                });
                saveAs(new Blob([s2ab(wbout)], {
                    type: "application/octet-stream"
                }), 'Extrato' + '.xlsx');

            } catch (err) {
                console.log(err);
            }
        }


        function excluir(id) {
            if (confirm("Deseja mesmo excluir esse extrato?")) {
                window.location.href = window.location.href + `?id=${id}`
            }
        }

        function editar(id) {
            window.location.href = `cad_extrato.php?id=${id}`
        }
    </script>
    <script src="js/jquery-3.2.1.slim.min.js" ></script>
    <script src="js/popper.min.js" ></script>
    <script src="js/bootstrap.min.js" ></script>
    <?php include "imports/js.php"; ?>
</body>

</html>