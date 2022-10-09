<?php
include "imports/head_parameters.php";
include_once "../controller/saldo_controller.php";
include_once "../controller/gastos_controller.php";
include_once "../controller/meta_controller.php";

if (!isset($_SESSION['id_usuario'], $_SESSION['status']) or $_SESSION['status'] <> "logado") {
    header("Location: ../../cofrin/view/login.html");
}


$obj_gastos = new Gastos($_SESSION['id_usuario']);
$obj_saldo =  new Saldo($_SESSION['id_usuario']);
$metas =  new Metas($_SESSION['id_usuario']);
$obj_meta = $metas->buscarTodasMetas();
//echo "<script>console.log('".json_encode($obj_meta)."')</script>";

if ($obj_meta != false and $obj_meta['id_usuario'] !== null) {
    echo "<script>var modalmeta = true;</script>";
    $meta_saldo =  number_format($obj_meta['saldo'], 2, ',', '.');
    $porcentagem_alcancada =  $obj_meta['porcentagem_alcancada'];
} else {
    echo "<script>var modalmeta = false;</script>";
    $meta_saldo =  '00,00';
    $porcentagem_alcancada =  '0';
}


?>
<title>Dashboard</title>
<link rel="stylesheet" href="../view/css/dashboard.css">
<script src="./lib/js/Chart.min.js"></script>


<script src="./lib/js/utils.js"></script>
<style>
    @media only screen and (max-device-width: 768px) {
        * {
            box-sizing: inherit !important;
        }
    }
</style>
</head>

<body class="row">
    <header class="col-12 col-md-1">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-12 col-md-11 d-flex flex-wrap min-vh-100 pt-4">
        <section class="col-lg-9 col-12 d-block pr-3 pr-sm-3 pr-md-4 pr-lg-5">
            <div class="d-block pt-4 pb-4 p-sm-0">
                <h3>Olá, <?php echo explode(" ", $_SESSION['nome'])[0]; ?>!</h3>
            </div>
            <section class="d-flex flex-lg-nowrap flex-wrap">
                <div class="cards pointer p-4 mb-4 mb-sm-1" onclick="trocaPagina('extrato.php')">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/ganhos.png" class="card-icone" alt="ganhos">&nbsp;
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
                            <img src="./images/gastos.png" class="card-icone" alt="gastos">&nbsp;
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
                <div class="cards pointer p-4 mr-0 mb-4 mb-sm-1" onclick="openMeta()">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/favorito.png" class="card-icone" alt="favorito">&nbsp;
                            <legend class="my-auto">Meta atual:</legend>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-purple font-weight-bold">R$</p>
                            <h3 class="font-purple number"><?php echo $meta_saldo; ?> </h3>
                        </div>
                        <div class="d-flex align-items-baseline pt-2">
                            <?php
                            if ($porcentagem_alcancada >= 100) {
                                echo "<img src=\"../view/images/Polígono 7.png\" class=\"card-poligono\" alt=\"Ganho\" style=\"transform: rotate(
                                        180deg
                                        );\">&nbsp
                                    <p class=\"font-green font-weight-bold\">";
                            } else {
                                echo "<img src=\"../view/images/Polígono 9.png\" class=\"card-poligono\" alt=\"Ganho\" style=\"transform: rotate(
                                        180deg
                                        );\">&nbsp
                                    <p class=\"font-red font-weight-bold\">";
                            }
                            ?>


                            <span class="number"><?php echo $porcentagem_alcancada; ?></span>%
                            </p>&nbsp;
                            <small class="font-gray">da meta alcançada</small>
                        </div>
                    </fieldset>

                </div>
            </section>
            <section class="d-flex flex-lg-nowrap flex-wrap">
                <div class="hand-shake cards p-4 pb-5 bg-green col-lg-4 mb-4 mb-sm-1">
                    <h4 class="font-purple font-weight-bold mt-2 mx-2 pb-5">Recomende <span class="font-white"> para amigos</span> e desbloqueie funções <span class="font-white">especiais</span></h4>
                </div>
                <div class="cards p-4 mb-4 mb-sm-1 mr-0">
                    <fieldset class="d-block ">
                        <div class="d-flex justify-content-between">
                            <legend>Meus gastos:</legend>
                            <div class="d-block mb-3">
                                <div class="d-flex">
                                    <button id="btn-previous" class="btn btn-green-inverted btn-sm mb-2 mx-1 px-3" onclick="controlaMesGastoAtual('previous')" title="Mês anterior">
                                        < </button>
                                            <button id="btn-next" class="btn btn-green-inverted btn-sm mb-2 mx-1 px-3" onclick="controlaMesGastoAtual('next')" title="Mês seguinte">></button>
                                </div>
                                <p id='mes-gasto' class="font-green text-center m-0"></p>
                                <p id='gasto-mes' class="font-green text-center m-0 number"></p>
                            </div>
                        </div>
                        <div>
                            <div id="chart-container" class="chart-area">
                                <canvas id="chart-area"></canvas>

                                <?php include_once '../database/crud.php';
                                $result = Crud::read("SELECT
                                concat(MONTH(tb_despesa.data_do_debito),'/',year(tb_despesa.data_do_debito)) AS `data`,
                                SUM(valor) AS total,
                                tipo,
                                gasto_mes
                                FROM tb_despesa INNER JOIN  (
								    SELECT 
								        SUM(valor) AS gasto_mes,
								        CONCAT(MONTH(t.data_do_debito),'/',YEAR(t.data_do_debito)) AS mesano
								        FROM tb_despesa AS t
								        WHERE t.id_usuario = {$_SESSION['id_usuario']} 
								        AND t.status = 1
								        GROUP BY MONTH(t.data_do_debito),YEAR(t.data_do_debito)
								        ORDER BY data_do_debito ASC
								    ) AS t ON  concat(MONTH(tb_despesa.data_do_debito),'/',year(tb_despesa.data_do_debito)) = t.mesano
								    WHERE
                                id_usuario = {$_SESSION['id_usuario']} AND status = 1#Id usuario
                                GROUP BY MONTH(tb_despesa.data_do_debito),YEAR(tb_despesa.data_do_debito),tipo ORDER BY data_do_debito ASC;");

                                $categoria = '';
                                $rows = array();
                                if (gettype($result) == "array") {
                                    $categoria = json_encode($result);
                                }
                                echo "<script>const gastos = {$categoria}</script>";
                                ?>
                                <script>
                                    items = (obj) => {
                                        var i,
                                            arr = []
                                        for (i in obj) {
                                            arr.push(obj[i])
                                        }
                                        return arr
                                    }

                                    let verificador = null
                                    let contador = -1
                                    let arrayGastos = '{ '
                                    items(gastos).forEach((row) => {
                                        if (verificador == null || verificador != row['data']) {
                                            arrayGastos = arrayGastos.substr(0, (arrayGastos.length - 1))
                                            if (verificador != null) {
                                                arrayGastos += '},'
                                                contador = -1
                                            }
                                            contador++
                                            verificador = row['data']
                                            arrayGastos += `"${verificador}": { "${contador}": ${JSON.stringify(row)},`
                                        } else {
                                            contador++
                                            arrayGastos += ` "${contador}":  ${JSON.stringify(row)},`
                                        }
                                    })
                                    arrayGastos = arrayGastos.substr(0, (arrayGastos.length - 1)) + "}}"
                                    //console.log(arrayGastos)
                                    arrayGastos = JSON.parse(arrayGastos)
                                    //console.log(arrayGastos)

                                    const mesPrimeiroGastos = items(arrayGastos)[0][0].data
                                    const gastoMesArray = items(arrayGastos).map((value) => new Intl.NumberFormat('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL'
                                    }).format(value[0]['gasto_mes']))
                                    const qtdMeses = items(arrayGastos).length
                                    const mesUltimoGastos = items(arrayGastos)[qtdMeses - 1][0].data


                                    var mesExibindo = (qtdMeses - 1);

                                    const controlaMesGastoAtual = (sentido = null) => {
                                        let gastoMesLabel = document.getElementById('gasto-mes')
                                        if (sentido == 'next' && mesExibindo < qtdMeses - 1) {
                                            resetCanvasGastos();
                                            ctx = document.getElementById('chart-area').getContext('2d');
                                            mesExibindo++;
                                            window.myPie = new Chart(ctx, config(mesExibindo));
                                            document.getElementById('mes-gasto').innerText = items(arrayGastos)[mesExibindo][0].data;
                                            gastoMesLabel.innerText = gastoMesArray[mesExibindo];
                                        }
                                        if (sentido == 'previous' && mesExibindo > 0) {
                                            resetCanvasGastos();
                                            ctx = document.getElementById('chart-area').getContext('2d');
                                            mesExibindo--;
                                            window.myPie = new Chart(ctx, config(mesExibindo));
                                            document.getElementById('mes-gasto').innerText = items(arrayGastos)[mesExibindo][0].data;
                                            gastoMesLabel.innerText = gastoMesArray[mesExibindo];
                                        }
                                        if (!sentido) {
                                            resetCanvasGastos();
                                            ctx = document.getElementById('chart-area').getContext('2d');
                                            mesExibindo = qtdMeses - 1;
                                            document.getElementById('mes-gasto').innerText = items(arrayGastos)[mesExibindo][0].data;
                                            gastoMesLabel.innerText = gastoMesArray[mesExibindo];
                                        }
                                    }



                                    const getValueGastos = (exibirMes = mesExibindo) => {
                                        let valorGastos = []
                                        let data = []
                                        let tituloGastos = []
                                        let position = exibirMes
                                        let contador = 0
                                        items(arrayGastos).forEach((row) => {
                                            if (position == contador) {
                                                items(row).forEach((r) => {
                                                    valorGastos.push(r.total)
                                                    tituloGastos.push(r.tipo)
                                                    data.push(r.data)
                                                })
                                            }
                                            contador++
                                        })
                                        return [data, valorGastos, tituloGastos]
                                    }

                                    function resetCanvasGastos() {
                                        $('#chart-area').remove(); // this is my <canvas> element
                                        $('#chart-container').append('<canvas id="chart-area" width="100"></canvas>');

                                    };

                                    const config = (month = undefined) => {
                                        return {
                                            type: 'doughnut',
                                            data: {
                                                datasets: [{
                                                    data: getValueGastos(month)[1],
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
                                                labels: getValueGastos(month)[2]


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
                                                    display: <?php echo $categoria == '' ? 'false' : 'true'; ?>,
                                                    position: "left",
                                                    labels: {
                                                        fontFamily: 'Arial',
                                                        fontColor: "#9792e3",
                                                        borderColor: "#9792e3"
                                                    }
                                                }
                                            }
                                        };
                                    }
                                    <?php echo $categoria == '' ? 'document.getElementById(\'chart-area\').parentNode.innerHTML += \'<span class="font-green">Ainda não há gastos nesse mês!😁👍</span>\'' : ''; ?>
                                </script>
                            </div>
                        </div>


                    </fieldset>
                </div>
            </section>
            <section class="mr-1 mb-5">
                <div class="cards p-4 mb-4 mb-sm-1 cards-graphic">
                    <fieldset class="d-block">
                        <div class="d-flex justify-content-between">
                            <h5>Métricas:</h5>
                            <div class="d-block">
                                <button class="btn btn-green-inverted btn-sm" onclick="runChart(arrayMetricasGastos)">Gastos</button>
                                <button class="btn btn-green-inverted btn-sm" onclick="runChart(arrayMetricasGastosDetalhes)">Gastos detalhes</button>
                                <button class="btn btn-green-inverted btn-sm" onclick="runChart(arrayMetricasGanhos)">Ganhos</button>
                                <button class="btn btn-green-inverted btn-sm" onclick="runChart(arrayMetricasLiquido)">Líquido</button>
                            </div>
                        </div>
                        <div id="chart-metrica-pai" class="chart-area d-block chart-metricas" style="background: aliceblue; border-radius: 10px; padding: 20px;">
                            <canvas id="chart-metrica" style="height: 22em; max-width: 88vw;"></canvas>
                            <script>
                                var arrayMetricasGastos = [];
                                var arrayMetricasGastosDetalhes = [];
                                var arrayMetricasGanhos = [];
                                var arrayMetricasLiquido = [];

                                var presets = window.chartColors;
                                var utils = Samples.utils;

                                var options = {
                                    maintainAspectRatio: false,
                                    spanGaps: false,
                                    elements: {
                                        line: {
                                            tension: 0.000001
                                        }
                                    },
                                    plugins: {
                                        filler: {
                                            propagate: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            grid: {
                                                color: 'white'
                                            }
                                        },
                                        x: {
                                            grid: {
                                                color: 'white'
                                            }
                                        },
                                        xAxes: [{
                                            ticks: {
                                                autoSkip: false,
                                                maxRotation: 0,
                                                color: 'white'
                                            }
                                        }]
                                    }
                                };


                                <?php include_once '../database/crud.php';
                                $result = Crud::read("SELECT * FROM (SELECT
                                concat(MONTH(tb_despesa.data_do_debito),'/',year(tb_despesa.data_do_debito)) AS `Mes/Ano`,
                                SUM(valor) AS total
                                  FROM tb_despesa
                                 WHERE
                                 id_usuario = {$_SESSION['id_usuario']} AND status = 1#Id usuario
                                  GROUP BY MONTH(tb_despesa.data_do_debito),YEAR(tb_despesa.data_do_debito) ORDER BY data_do_debito desc
                                   LIMIT 12) as temp ORDER BY temp.`Mes/Ano` asc;");

                                //AND tb_despesa.data_do_debito > ADDDATE((SUBDATE(NOW(),INTERVAL 1 YEAR)),INTERVAL 1 MONTH) #ToDo remover essa linha e ajustar para mostrar todo historico

                                if (gettype($result) == "array") {
                                    $categoria = '';
                                    $valor = "";
                                    $legenda = "";
                                    foreach ($result as $key => $value) {
                                        $valor .= "{$value['total']},";
                                        $legenda .= "\"{$value['Mes/Ano']}\",";
                                    }
                                    echo "arrayMetricasGastos.push ({
                                        color: presets.red,
                                        legenda: \"Gastos\",
                                        arrayMetricasLegend:[{$legenda}],
                                        arrayMetricasValues:[{$valor}],
                                    });";
                                }
                                ?>
                                <?php include_once '../database/crud.php';
                                $result = Crud::read("SELECT *,
                                if(t1.tipo = t2.tipo_temp, TRUE, '') AS vdd,
                                (
                                    SELECT SUM(valor)
                                    FROM tb_despesa
                                    WHERE id_usuario = {$_SESSION['id_usuario']}
                                        AND STATUS = 1
                                        AND MONTH(tb_despesa.data_do_debito) = mes
                                        AND YEAR(tb_despesa.data_do_debito) = ano
                                        AND tb_despesa.tipo = t1.tipo
                                ) as valor
                            FROM (
                                    SELECT tipo as tipo
                                    FROM tb_despesa
                                    WHERE id_usuario = {$_SESSION['id_usuario']}
		                                AND tb_despesa.valor > 0.0
		                                AND TIMESTAMPDIFF(MONTH, tb_despesa.data_do_debito , CURDATE()) <= 12
                                        AND status = 1 #Id usuario
                                    GROUP BY tipo
                                ) AS t1
                                join (
                                    SELECT tipo as tipo_temp,
                                    tb_despesa.data_do_debito AS dt,
                                        concat(
                                            MONTH(tb_despesa.data_do_debito),
                                            '/',
                                            year(tb_despesa.data_do_debito)
                                        ) AS `Mes/Ano`,
                                        MONTH(tb_despesa.data_do_debito) AS mes,
                                        YEAR(tb_despesa.data_do_debito) AS ano,
                                        SUM(valor) AS total
                                    FROM tb_despesa
                                    WHERE id_usuario = {$_SESSION['id_usuario']}
		                                AND TIMESTAMPDIFF(MONTH, tb_despesa.data_do_debito , CURDATE()) <= 12
                                        AND status = 1 #Id usuario
                                    GROUP BY MONTH(tb_despesa.data_do_debito),YEAR(tb_despesa.data_do_debito),
                                        tipo
                                    ORDER BY data_do_debito ASC
                                ) AS t2 #ON t1.tipo = t2.tipo_temp OR ()
                                WHERE t1.tipo <> ''
                            GROUP BY t1.tipo,
                                `Mes/Ano`
                                ORDER BY t2.dt asc ");

                                if (gettype($result) == "array") {
                                    $istrueVarieble = false;
                                    $valor = "";
                                    $legenda = "";
                                    $arrayDeVerificacaoTipos = [["ahsdjkahs" => []],];
                                    $arrayDeTipos = [["ahsdjkahs" => []],];
                                    $presetColors = ["red", "blue", "orange", "yellow", "purple", "green", "cyan", "black", "grey", "pink"];
                                    echo "var teste2= '';";
                                    foreach ($result as $key => $value) {
                                        for ($i = 0; $i < count($arrayDeVerificacaoTipos); $i++) {
                                            if ($arrayDeVerificacaoTipos[$i] == "{$value['tipo']}") {
                                                $istrueVarieble = true;
                                            }
                                            if ($i == count($arrayDeVerificacaoTipos) - 1) {
                                                if ($istrueVarieble == false) {
                                                    array_push($arrayDeVerificacaoTipos, (string)$value['tipo']);
                                                    array_push($arrayDeTipos, [(string)$value['tipo'] => []]);
                                                }
                                                $istrueVarieble = false;
                                            }
                                        }
                                        array_push(
                                            $arrayDeTipos[array_search((string)$value['tipo'], $arrayDeVerificacaoTipos)][(string)$value['tipo']],
                                            (is_null($value['valor']) ? 0 : $value['valor'])
                                        );
                                    }
                                    unset($arrayDeTipos[0]);
                                    echo "var tested = " . json_encode($arrayDeTipos) . ";";
                                    $counter = 0;
                                    foreach ($arrayDeTipos as $key => $Tipo) {
                                        if ($counter > 9) {
                                            $counter = 0;
                                        }
                                        //echo "console.log('".json_encode($Tipo[key($Tipo)])."');";
                                        echo "arrayMetricasGastosDetalhes.push ({
                                        color: presets.{$presetColors[$counter]},
                                        legenda: \"" . key($Tipo) . "\",
                                        arrayMetricasLegend: arrayMetricasGastos[0].arrayMetricasLegend,
                                        arrayMetricasValues: " . json_encode($Tipo[key($Tipo)]) . "
                                        });";
                                        $counter++;
                                    }
                                }
                                ?>
                                <?php include_once '../database/crud.php';
                                $result = Crud::read("SELECT * FROM (
                                SELECT concat(
                                        MONTH(tg.data_do_credito),
                                        '/',
                                        year(tg.data_do_credito)
                                    ) AS `Mes/Ano`, tg.data_do_credito,
                                    SUM(valor) AS total
                                FROM tb_ganho as tg
                                    INNER JOIN tb_carteira as tc ON tc.id_carteira = tg.id_carteira
                                WHERE tc.poupanca = 0
                                    AND tc.status = 1
                                    AND tg.id_usuario = {$_SESSION['id_usuario']} #Id usuario
                                GROUP BY MONTH(tg.data_do_credito),
                                    YEAR(tg.data_do_credito)
                                ORDER BY data_do_credito ASC) AS temp
                                    where TIMESTAMPDIFF(MONTH, temp.data_do_credito , CURDATE()) <= 12;");

                                if (gettype($result) == "array") {
                                    $categoria = '';
                                    $valor = "";
                                    $legenda = "";
                                    foreach ($result as $key => $value) {
                                        $valor .= "{$value['total']},";
                                        $legenda .= "\"{$value['Mes/Ano']}\",";
                                    }
                                    echo "arrayMetricasGanhos.push ({
                                        color: presets.green,
                                        legenda: \"Ganhos\",
                                        arrayMetricasLegend:[{$legenda}],
                                        arrayMetricasValues:[{$valor}],
                                    });";
                                }
                                ?>
                                <?php include_once '../database/crud.php';
                                $result = Crud::read("SELECT(t2.total-t1.total) AS Liquido, t1.`Mes/Ano`
                                FROM
                                (SELECT
                                concat(MONTH(tb_despesa.data_do_debito),'/',YEAR(tb_despesa.data_do_debito)) AS `Mes/Ano`,
                                SUM(valor) AS total
                                FROM tb_despesa
                                WHERE
                                id_usuario = {$_SESSION['id_usuario']} AND STATUS = 1
                                AND TIMESTAMPDIFF(MONTH, tb_despesa.data_do_debito , CURDATE()) <= 12
                                GROUP BY MONTH(tb_despesa.data_do_debito),YEAR(tb_despesa.data_do_debito) ORDER BY data_do_debito ASC) AS t1
                                INNER JOIN
                                (SELECT 
                                concat(MONTH(tg.data_do_credito),'/',YEAR(tg.data_do_credito)) AS `Mes/Ano`, 
                                SUM(valor) AS total
                                FROM tb_ganho as tg
                                INNER JOIN tb_carteira as tc
                                ON tc.id_carteira = tg.id_carteira
                                WHERE
                                tc.poupanca = 0  
                                AND TIMESTAMPDIFF(MONTH, tg.data_do_credito , CURDATE()) <= 12
                                AND tg.id_usuario = {$_SESSION['id_usuario']} AND tg.status = 1
                                GROUP BY MONTH(tg.data_do_credito),YEAR(tg.data_do_credito) ORDER BY data_do_credito ASC) AS t2 
                                ON t1.`Mes/Ano` = t2.`Mes/Ano`;");

                                if (gettype($result) == "array") {
                                    $categoria = '';
                                    $valor = "";
                                    $legenda = "";
                                    foreach ($result as $key => $value) {
                                        $valor .= "{$value['Liquido']},";
                                        $legenda .= "\"{$value['Mes/Ano']}\",";
                                    }
                                    echo "arrayMetricasLiquido.push ({
                                        color: presets.yellow,
                                        legenda: \"Líquido\",
                                        arrayMetricasLegend:[{$legenda}],
                                        arrayMetricasValues:[{$valor}],
                                    });
                                    ";
                                }
                                ?>

                                var chartMetricas = null;

                                function runChart(
                                    arrayDataSets = [{
                                        color: presets.green,
                                        arrayMetricasLegend: arrayMetricasLegendLiquido,
                                        arrayMetricasValues: arrayMetricasValuesLiquido,
                                        legenda: "Líquido"
                                    }]) {
                                    resetCanvas();
                                    // reset the random seed to generate the same data for all charts
                                    utils.srand(8);

                                    datasetValues = [];

                                    arrayDataSets.forEach((item) => {
                                        datasetValues.push({
                                            backgroundColor: utils.transparentize(item.color),
                                            borderColor: item.color,
                                            data: item.arrayMetricasValues,
                                            label: item.legenda,
                                            fill: 'origin',
                                            tension: 0.3
                                        });
                                    })


                                    chartMetricas = new Chart('chart-metrica', {
                                        type: 'line',
                                        data: {
                                            labels: arrayDataSets[0].arrayMetricasLegend,
                                            datasets: datasetValues
                                        },
                                        options: Chart.helpers.merge(options, {
                                            title: {
                                                text: 'fill: ' + 'origin',
                                                display: false
                                            }
                                        })
                                    });


                                };

                                function resetCanvas() {
                                    $('#chart-metrica').remove(); // this is my <canvas> element
                                    $('#chart-metrica-pai').append('<canvas id="chart-metrica" style="height: 22em; max-width: 88vw;"><canvas>');

                                };


                                window.onload = () => {
                                    var ctx = document.getElementById('chart-area').getContext('2d');
                                    window.myPie = new Chart(ctx, config());
                                    runChart(arrayMetricasLiquido);
                                }
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
            <div>
                <?php
                $contador = 0;
                $result = $obj_gastos->lista_gastos();
                if (gettype($result) === "array") {

                    foreach ($result as $key => $value) {
                        if ($contador < 4) {
                            $date = date_create($value['data_do_debito']);
                            echo "<div class=\"cartao p-3 mr-2 d-flex  my-4\" onclick=\"trocaPagina('gastos.php?id={$value['id_despesa']}')\" title='" . $value['descricao'] . "'>
                                            <h4 class=\"cartao-icon bg-gray mb-auto p-2 mr-4\">{$value['icone']}</h4>
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
                    echo "<td colspan='4'><span>Ainda não há gastos cadstrados esse mês!</span></td>";
                }

                echo "</table>";
                ?>

            </div>
        </section>

    </main>


    <!-- Modal - resumo da meta -->
    <div class="modal fade" id="modalResumeMeta" tabindex="-1" role="dialog" aria-labelledby="modalResumeMetaLabel" aria-hidden="true" onfocus="buscarMeta()">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalResumeMetaLabel">Resumo da meta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="modal-resumo-titulo"></h5>
                    <p id="modal-resumo-descricao"></p>
                    <div class="d-flex justify-content-between mt-4">
                        <p>Andamento da meta atual:</p>
                        <div class="text-right">
                            <p class="p-0 m-0"><span id="modal-resumo-saldo" class="font-yellow number">R$ 0,00</span> de <span id="modal-resumo-valor" class="font-green number">R$ 0,00</span></p>
                            <div class="">
                                <small class="p-0 m-0">meta <span id="modal-resumo-porcentagem">100%</span> alcançada!</small>
                                <meter id="modal-resumo-porcentagemRange" class="w-100 " type="range" min="0" max="100" low="30" high="85" optimum="90" value="90"></meter>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between my-4">
                        <p>Economizar até:</p>
                        <div>
                            <p id="modal-resumo-dtLimite">25/12/2021</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a href="./extrato.php?id_carteira_meta=" id="modal-resumo-historico" class="btn btn-success">Histórico</a>
                    <button type="button" class="btn btn-success" onclick="editarMeta()">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - criação e alteração de meta -->
    <div class="modal fade" id="modalCreateMeta" tabindex="-1" role="dialog" aria-labelledby="modalCreateMetaLabel" aria-hidden="true" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalCreateMetaLabel">Ainda não configurou sua meta?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Deixar pra depois">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="font-green">Vamos começar!</h4>
                    <form class="my-4" id="form-meta-cadastro" action="" onsubmit="event.preventDefault(); saveMeta();">
                        <input class="d-none" type="text" id="form-meta-id" name="form-meta-id" value="0">
                        <label class="mt-1" for="form-meta-titulo">De um nome a sua meta</label>
                        <input class="form-control col col-12 col-md-8" name="form-meta-titulo" id="form-meta-titulo" type="text" minlength="4" maxlength="50" placeholder="Viagem da minha vida...">

                        <label class="mt-3" for="form-meta-carteira">Selecione uma carteira poupança ou de
                            investimento*</label>
                        <select class="form-control col col-12 col-md-8" name="form-meta-carteira" id="form-meta-carteira" onfocus="buscarCarteiras(this)" required>
                            <option value="" selected>Selecione uma carteira</option>
                        </select>

                        <div class="d-flex">
                            <div class="col col-12 col-md-4 pl-0">
                                <label class="mt-3" for="form-meta-valor">Qual a sua meta?*</label>
                                <input class="form-control number font-green" name="form-meta-valor" id="form-meta-valor" type="text" onfocus="ValidaCampos.MoedaUnitarioQuantidade('#form-meta-valor', 2);" placeholder="R$ 5.000,00" required>
                            </div>
                            <div class="col col-12 col-md-4 pr-0">
                                <label class="mt-3" for="form-meta-data">Data limite:*</label>
                                <input class="form-control" name="form-meta-data" id="form-meta-data" type="date" onfocus="this.min = dt_now;this.max = dt_limite" required>
                                <small>Até quando deve juntar esse dinheiro?
                                </small>
                            </div>
                        </div>

                        <label class="mt-1" for="form-meta-descricao">Descrição</label>
                        <textarea class="form-control col col-12 col-md-8" name="form-meta-descricao" id="form-meta-descricao" type="text" rows="5" maxlength="200" placeholder="Viajar pela Europa..."></textarea>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start">
                    <button type="submit" form="form-meta-cadastro" class="btn btn-success">Definir meta</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "imports/js.php"; ?>

    <script>
        const id_usuario = <?php echo $_SESSION['id_usuario']; ?>;
        var meta = "";

        const dt_now = new Date().getFullYear() + '-' +
            String(new Date().getMonth() + 1).padStart(2, '0') + '-' +
            String(new Date().getDate()).padStart(2, '0');

        const dt_limite = (new Date().getFullYear() + 10) + '-' +
            String(new Date().getMonth() + 1).padStart(2, '0') + '-' +
            String(new Date().getDate()).padStart(2, '0');

        function openMeta() {
            if (modalmeta) {
                $(modalResumeMeta).modal()
            } else {
                $(modalCreateMeta).modal()
            }
        }

        function editarMeta() {
            $(modalResumeMeta).modal('hide');
            $(modalCreateMeta).modal();

            document.getElementById('form-meta-id').removeAttribute("value");
            document.getElementById('form-meta-id').setAttribute("value", `${meta['id_meta']}`);
            document.getElementById('form-meta-titulo').value = meta['titulo'];
            document.getElementById('form-meta-carteira').value = meta['id_carteira'];
            document.getElementById('form-meta-valor').value = meta['valor'];
            document.getElementById('form-meta-data').value = meta['dt_limite'];
            document.getElementById('form-meta-descricao').value = meta['descricao_meta'];
        }




        const buscarCarteiras = async (event) => {
            var myHeaders = new Headers();
            myHeaders.append("Cookie", "PHPSESSID=9e8p1o4t0fnhdcv3veig0fvrsc");

            var formdata = new FormData();

            var requestOptions = {
                method: 'GET',
                headers: myHeaders,
                redirect: 'follow'
            };

            const response = await fetch(`../controller/carteira_controller.php?id_usuario=${id_usuario}&funcao=listarpoupanca`, requestOptions)
            const resultJson = await response.json();
            if (resultJson === "0 dados encontrados") {
                return;
            } else {
                event.innerHTML = "";
                for (var i = 0; i < resultJson.length; i++) {
                    event.innerHTML += `<option value="${resultJson[i]['id_carteira']}">${resultJson[i]['nome_carteira']}</option>`;
                }
            }
        }

        const buscarMeta = async (event) => {

            var formdata = new FormData();
            formdata.append("funcao", "listar");

            var requestOptions = {
                method: 'POST',
                body: formdata,
            };

            const response = await fetch(`../controller/meta_controller.php`, requestOptions)
            const resultJson = await response.json();
            if (resultJson === "0 dados encontrados" || resultJson == false) {
                $(modalResumeMeta).modal('hide');
                $(modalCreateMeta).modal();
            } else if (response.status == 200) {
                meta = resultJson;
                document.getElementById('modal-resumo-titulo').innerText = resultJson['titulo'];
                document.getElementById('modal-resumo-descricao').innerText = resultJson['descricao_meta'];
                document.getElementById('modal-resumo-saldo').classList.remove("font-green");
                document.getElementById('modal-resumo-saldo').classList.remove("font-yellow");
                document.getElementById('modal-resumo-saldo').classList.remove("font-red");
                document.getElementById('modal-resumo-saldo').classList.add(resultJson['porcentagem_alcancada'] < 30 ? "font-red" : (resultJson['porcentagem_alcancada'] < 85 ? "font-yellow" : "font-green"));
                document.getElementById('modal-resumo-saldo').innerText = "R$ " + resultJson['saldo'];
                document.getElementById('modal-resumo-valor').innerText = "R$ " + resultJson['valor'];
                document.getElementById('modal-resumo-porcentagem').innerText = resultJson['porcentagem_alcancada'] + "%";
                document.getElementById('modal-resumo-porcentagemRange').value = resultJson['porcentagem_alcancada'];
                document.getElementById('modal-resumo-dtLimite').innerText = resultJson['dt_limite_ptbr'];
                document.getElementById('modal-resumo-historico').href += resultJson['id_carteira'];
            }
        }

        const saveMeta = async () => {

            let id = document.getElementById('form-meta-id').value;
            let titulo = document.getElementById('form-meta-titulo').value;
            let carteira = document.getElementById('form-meta-carteira').value;
            let valor = document.getElementById('form-meta-valor').value;
            let data = document.getElementById('form-meta-data').value;
            let descricao = document.getElementById('form-meta-descricao').value;

            console.log(id_usuario);
            console.log(id);
            console.log(titulo);
            console.log(carteira);
            console.log(valor);
            console.log(data);
            console.log(descricao);

            try {

                var formdata = new FormData();
                formdata.append("funcao", "salvar");
                formdata.append("id_meta", id);
                formdata.append("id_usuario", id_usuario);
                formdata.append("id_carteira", carteira);
                formdata.append("titulo", titulo);
                formdata.append("descricao", descricao);
                formdata.append("valor", valor);
                formdata.append("dt_limite", data);



                const response = await fetch(`../controller/meta_controller.php`, {
                    method: "POST",
                    body: formdata,
                });

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    toastr.success(resultJson, 'Parabéns:');
                    $(modalCreateMeta).modal('hide');
                    modalmeta = true;
                }
            } catch (error) {
                console.log(error)
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }
    </script>
</body>

</html>