<?php

use function PHPSTORM_META\type;

include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/saldo_controller.php";
include_once "../controller/gastos_controller.php";

if (!isset($_SESSION['id_usuario'], $_SESSION['status']) or $_SESSION['status'] <> "logado") {
    header("Location: ../../cofrin/view/login.html");
}


$obj_gastos = new Gastos($_SESSION['id_usuario']);
$obj_saldo =  new Saldo($_SESSION['id_usuario']);


?>
<title>Dashboard</title>
<link rel="stylesheet" href="../view/css/dashboard.css">
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>

</head>

<body class="row">
    <header class="col-12 col-md-1">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-12 col-md-11 d-flex flex-wrap min-vh-100">
        <section class="col-lg-9 col-12 d-block pr-3 pr-sm-3 pr-md-4 pr-lg-5">
            <div class="d-block pt-4 pb-4 p-sm-0">
                <h3>Olá, <?php echo explode(" ", $_SESSION['nome'])[0]; ?>!</h3>
            </div>
            <section class="d-flex flex-lg-nowrap flex-wrap">
                <div class="cards pointer p-4 mb-4 mb-sm-1" onclick="trocaPagina('extrato.php')">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/ganhos.png" class="my-auto card-icone" alt="ganhos">&nbsp;
                            <legend class="my-auto">Saldo atual:</legend>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-purple font-weight-bold">R$</p>
                            <h3 class="font-purple number"><?php echo number_format($obj_saldo->saldo(), 2, ',', '.') ?> </h3>
                        </div>
                        <div class="d-flex align-items-baseline pt-2">
                            <img src="../view/images/Polígono 7.png" class="card-poligono" alt="Ganho">&nbsp;<p class="font-green font-weight-bold">+<span class="number">0,0</span>%</p>&nbsp;<small class=" font-gray">em relação ao ultimo mês</small>
                        </div>
                    </fieldset>

                </div>
                <div class="cards pointer p-4 mb-4 mb-sm-1" onclick="trocaPagina('gastos.php')">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/gastos.png" class="my-auto card-icone" alt="gastos">&nbsp;
                            <legend class="my-auto">Gastos do mês:</legend>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-purple font-weight-bold">R$</p>
                            <h3 class="font-purple number"><?php echo number_format($obj_gastos->total_de_gastos(), 2, ',', '.') ?> </h3>
                        </div>
                        <div class="d-flex align-items-baseline pt-2">
                            <img src="../view/images/Polígono 9.png" class="card-poligono" alt="Perca">&nbsp;<p class="font-red font-weight-bold">+<span class="number">0,0</span>%</p>&nbsp;<small class="font-gray">em relação ao ultimo mês</small>
                        </div>
                    </fieldset>
                </div>
                <div class="cards pointer p-4 mb-4 mb-sm-1" onclick="funcaoIndisponivel()">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/favorito.png" class="my-auto card-icone" alt="favorito">&nbsp;
                            <legend class="my-auto">Meta atual:</legend>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-purple font-weight-bold">R$</p>
                            <h3 class="font-purple number"><?php echo number_format('00.00', 2, ',', '.') ?> </h3>
                        </div>
                        <div class="d-flex align-items-baseline pt-2">
                            <img src="../view/images/Polígono 7.png" class="card-poligono" alt="Ganho">&nbsp;<p class="font-green font-weight-bold"><span class="number">0</span>%</p>&nbsp;<small class="font-gray">da meta alcançada</small>
                        </div>
                    </fieldset>

                </div>
            </section>
            <section class="d-flex flex-lg-nowrap flex-wrap">
                <div class="hand-shake cards p-4 pb-5 bg-green col-lg-4 mb-4 mb-sm-1">
                    <h4 class="font-purple font-weight-bold mt-2 mx-2 pb-5">Recomende <span class="font-white"> para amigos</span> e desbloqueie funções <span class="font-white">especiais</span></h4>
                </div>
                <div class="cards p-4 mb-4 mb-sm-1">
                    <fieldset>
                        <legend>Meus gastos:</legend>
                        <div class="chart-area">
                            <canvas id="chart-area" width="100"></canvas>
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
                                                $result = Crud::read("SELECT SUM(valor) AS total, tipo FROM tb_despesa WHERE data_do_debito >= '" . date('Y') . "-" . date('m') . "-01'
                                                    AND data_do_debito <= '" . date('Y') . "-" . date('m') . "-31' AND id_usuario= {$_SESSION['id_usuario']} AND status = 1 GROUP BY tipo;");

                                                if (gettype($result) == "array") {
                                                    $categoria = '';
                                                    foreach ($result as $key => $value) {
                                                        echo $value['total'] . ",";
                                                        $categoria .= "'" . $value['tipo'] . "',";
                                                    }
                                                }
                                                ?>
                                            ],
                                            borderColor: "rgba(160, 161, 166,0.2)",
                                            hoverBorderColor: "rgb(25, 208, 160)",
                                            backgroundColor: [
                                                "rgba(255, 60, 60,0.5)",
                                                "rgba(255, 128,60,0.5)",
                                                "rgba(255, 236,60,0.5)",
                                                "rgba(184, 255,60,0.5)",
                                                "rgba(60, 255,143,0.5)",
                                                "rgba(60, 255,224,0.5)",
                                                "rgba(60, 169,255,0.5)",
                                                "rgba(60, 88, 255,0.5)",
                                                "rgba(140, 60,255,0.5)",
                                                "rgba(212, 60,255,0.5)",
                                                "rgba(255, 60,226,0.5)",
                                            ],
                                            hoverBackgroundColor: [
                                                "rgb(255, 60, 60)",
                                                "rgb(255, 128, 60)",
                                                "rgb(255, 236, 60)",
                                                "rgb(184, 255, 60)",
                                                "rgb(60, 255, 143)",
                                                "rgb(60, 255, 224)",
                                                "rgb(60, 169, 255)",
                                                "rgb(60, 88, 255)",
                                                "rgb(140, 60, 255)",
                                                "rgb(212, 60, 255)",
                                                "rgb(255, 60, 226)",
                                            ],
                                            label: 'Conjunto de dados'
                                        }],
                                        labels: [
                                            <?php echo $categoria; ?>
                                        ]

                                    },
                                    options: {
                                        tooltips: {
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
                                            position: "left",
                                            labels: {
                                                fontFamily: 'Arial',
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

                    </fieldset>
                </div>

            </section>
        </section>
        <section class=" col-12 col-lg-3 d-lg-block d-block dash-gastos">
            <div class="px-4 pt-5">
                <h4 class="pt-3">Últimas despesas:</h4>
            </div>
            <div class="p-2">
                <?php
                $contador = 0;
                $result = $obj_gastos->lista_gastos();
                if (gettype($result) === "array") {

                    foreach ($result as $key => $value) {
                        if ($contador < 4) {
                            $date = date_create($value['data_do_debito']);
                            echo "<div class=\"cartao p-3 mr-2 d-flex  my-4\" onclick=\"trocaPagina('gastos.php?id={$value['id_despesa']}')\" title='" . $value['descricao'] . "'>
                                            <h4 class=\"cartao-icon bg-gray my-auto p-2 mx-4\">{$value['icone']}</h4>
                                            <div class=\"my-auto \">";

                            echo "<p class=\"my-auto font-purple\">{$value['titulo']}</p>
                                            <small class=\"my-auto font-gray\">" . date_format($date, 'd/m/Y') . "</small>";
                            echo '</div>
                                            </div>';
                            $contador++;
                        } else {
                            echo "<a class=\"mx-auto my-2 text-center\" href='../view/gastos.php'><p class=\"font-green font-weight-bold\">Ver tudo...</p></a>";
                            break;
                        }
                    }
                } else {
                    echo "<td colspan='4'>Não há gastos cadastrados ainda!</td>";
                }

                echo "</table>";
                ?>

            </div>
        </section>

    </main>

    <?php include "imports/js.php"; ?>

</body>

</html>