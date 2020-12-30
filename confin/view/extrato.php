<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/extrato_controller.php";
include_once "../controller/gastos_controller.php";
include_once "../controller/ganho_controller.php";

$ganho = new GanhoController($_SESSION['id_usuario']);
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php include "imports/js.php"; ?>
</body>

</html>