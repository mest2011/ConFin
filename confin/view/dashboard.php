<?php

use function PHPSTORM_META\type;

include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/saldo_controller.php";
include_once "../controller/gastos_controller.php";

if (!isset($_SESSION['id_usuario'], $_SESSION['status']) or $_SESSION['status'] <> "logado") {
    header("Location: ../../site/home_page/index.php");
}


$obj_gastos = new Gastos($_SESSION['id_usuario']);
$obj_saldo =  new Saldo($_SESSION['id_usuario']);


?>
<title>Dashboard</title>
<link rel="stylesheet" href="../view/css/dashboard.css">
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>

</head>

<body>
    <section id="dashboard_principal">
        <?php include "imports/menu_lateral.php"; ?>
        <div id="dashboard_main" class="conteudo">
            <header>
                <h1>Confin - Dashboard</h1>
            </header>
            <main>
                <section id="dashboard_top_cards">
                    <div id="saldo_container" class="cards card-1x3" onclick="trocaPagina('extrato.php')">
                        <fieldset>
                            <legend>Saldo atual:</legend>
                            <div class="valor-1x3">
                                <p>R$ <?php echo number_format($obj_saldo->saldo(), 2, ',', '.') ?> </p>
                            </div>

                        </fieldset>

                    </div>
                    <div id="total_gastos_container" class="cards card-1x3" onclick="trocaPagina('gastos.php')">
                        <fieldset>
                            <legend>Gastos do mês:</legend>
                            <div class="valor-1x3">
                                <p>R$ <?php echo number_format($obj_gastos->total_de_gastos(), 2, ',', '.') ?> </p>
                            </div>
                        
                        </fieldset>
                    </div>
                    <div id="meta_container" class="cards card-1x3">
                        <fieldset>
                            <legend>Meta atual:</legend>
                            <div class="valor-1x3">
                                <p>R$ 00,00</p>
                            </div>

                        </fieldset>

                    </div>
                </section>
                <section id="dashboard-middle-cards">
                    <div id="gastos-mes" class="cards card-3x4">
                        <!-- arrumar id-->
                        <fieldset>
                            <legend>Despesas do mês</legend>
                            <div class="gastos-grafico">
                                <h3>Grafico de gastos</h3>
                                <div id="canvas-holder">
                                    <canvas id="chart-area"></canvas>
                                </div>
                                <script>
                                    var randomScalingFactor = function() {
                                        return Math.round(Math.random() * 100);
                                    };

                                    var config = {
                                        type: 'doughnut',
                                        data: {
                                            datasets: [{
                                                data: [
                                                    <?php include_once '../database/crud.php';
                                                    $result = Crud::read("SELECT SUM(valor) AS total, tipo FROM tb_despesa WHERE data_do_debito >= '".date('Y')."-".date('m')."-01'
                                                    AND data_do_debito <= '".date('Y')."-".date('m')."-31' AND id_usuario= {$_SESSION['id_usuario']} AND status = 1 GROUP BY tipo;");
                                                    
                                                    if (gettype($result) == "array") {
                                                        $categoria = '';
                                                        foreach ($result as $key => $value) {
                                                            echo $value['total'].",";
                                                            $categoria .= "'" . $value['tipo'] . "',";
                                                        }
                                                    }
                                                    ?>
                                                ],
                                                borderColor:"rgb(221,236,219)",
                                                hoverBorderColor: "rgb(90,87,102)",
                                                backgroundColor: [
                                                    "rgba(255,10,10,0.5)",
                                                    "rgba(255,128,10,0.5)",
                                                    "rgba(255,246,10,0.5)",
                                                    "rgba(51,239,54,0.5)",
                                                    "rgba(51,239,214,0.5)",
                                                    "rgba(62,149,205,0.5)",
                                                    "rgba(10,10,255,0.5)",
                                                    "rgba(144,10,255,0.5)",
                                                    "rgba(195,51,239,0.5)",
                                                    "rgba(239,51,208,0.5)",
                                                    "rgba(206,206,206,0.5)"
                                                ],
                                                hoverBackgroundColor: [
                                                    "rgba(255,10,10,1)",
                                                    "rgba(255,128,10,1)",
                                                    "rgba(255,246,10,1)",
                                                    "rgba(51,239,54,1)",
                                                    "rgba(51,239,214,1)",
                                                    "rgba(62,149,205,1)",
                                                    "rgba(10,10,255,1)",
                                                    "rgba(144,10,255,1)",
                                                    "rgba(195,51,239,1)",
                                                    "rgba(239,51,208,1)",
                                                    "rgba(206,206,206,1)"
                                                ],
                                                label: 'Conjunto de dados'
                                            }],
                                            labels: [
                                                <?php echo $categoria; ?>
                                            ]

                                        },
                                        options: {
                                            tooltips:{
                                                backgroundColor: '#9792E3ab',
                                                titleFontSize: 20,
                                                titleFontColor: '#fff',
                                                bodyFontColor: '#fff',
                                                bodyFontSize: 20,
                                                displayColors: false,
                                                xPadding: 10,
                                                yPadding: 10,
                                                rtl: true
                                                
                                            },
                                            legend: {
                                                display: true,
                                                position: "right",
                                                labels: {
                                                    fontSize: 18,
                                                    fontFamily:'Arial',
                                                    fontColor: "#9792e3",
                                                    borderColor: "#9792e3"
                                                }
                                            }
                                        }
                                    };

                                    window.onload = function() {
                                        var ctx = document.getElementById('chart-area').getContext('2d');
                                        window.myPie = new Chart(ctx, config);
                                    };

                                </script>
                            </div>
                            <div class="gastos-tabela">
                                <?php
                                echo "<table class='table'><thead>
                                    <th class='th-ini'>Titulo</th>
                                    <th>Valor</th>
                                    <th class='th-fim'>Data</th>
                                </thead>";
                                $contador = 0;
                                $result = $obj_gastos->lista_gastos();
                                if (gettype($result) === "array") {
                                    foreach ($result as $key => $value) {
                                        $date = date_create($value['data_do_debito']);
                                        echo "<tr onclick=\"trocaPagina('cad_gasto.php?id={$value['id_despesa']}')\" title='" . $value['descricao'] . "'><td class='td-ini'>" . $value['titulo'] . "</td><td>R$ " . number_format($value['valor'], 2, ',', '.') . "</td>
                                        <td class='td-fim'>" . date_format($date, 'd/m') . "</td>
                                        </tr>";
                                        if ($contador < 8) {
                                            $contador++;
                                        } else {
                                            echo "<tfoot><td colspan=\"4\"><a href=\"../view/gastos.php\">Ver todos...</a></td></tfoot>";
                                            break;
                                        }
                                    }
                                } else {
                                    echo "<td colspan='4'>Não há gastos cadastrados ainda!</td>";
                                }

                                echo "</table>";
                                ?>
                            </div>
                        </fieldset>
                    </div>
                    <div id="debitos-futuros" class="cards card-1x4">
                        <!-- arrumar id-->
                        <fieldset>
                            <legend>Debitos futuros</legend>
                            <?php
                            $render = "<table class='table'><thead><th class='th-ini'>Titulo</th><th>Valor</th><th class='th-fim'>Data</th></thead>";
                            $contador = 0;
                            $result = $obj_gastos->lista_gastos_futuros();
                            if (gettype($result) === "array") {
                                foreach ($result as $key => $value) {
                                    if ($contador < 9) {
                                        $date = date_create($value['data_do_debito']);
                                        $render .= "<tr title='" . $value['descricao'] . "'><td class='td-ini'>" . $value['titulo'] . "</td><td>R$ " . number_format($value['valor'], 2, ',', '.') . "</td>
                                                    <td class='td-fim'>" . date_format($date, 'd/m') . "</td></tr>";
                                        $contador++;
                                    } else {
                                        $render .= "<tfoot><td colspan=\"3\"><a href=\"../view/gastos.php\">Ver todos...</a></td></tfoot>";
                                        break;
                                    }
                                }
                                $render .= "</table>";
                            } else {
                                $render = "<b>" . $result . "</b>";
                            }
                            echo $render;

                            ?>
                        </fieldset>
                    </div>
                </section>
            </main>
            <footer></footer>
        </div>

    </section>

    <?php include "imports/js.php"; ?>

</body>

</html>