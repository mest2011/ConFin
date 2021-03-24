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

if ($metas !== false) {
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
<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>

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
                <div class="cards pointer p-4 mb-4 mb-sm-1" onclick="openMeta()">
                    <fieldset class="d-block">
                        <div class="d-flex pb-2">
                            <img src="./images/favorito.png" class="my-auto card-icone" alt="favorito">&nbsp;
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
                    <h5 id="modal-resumo-titulo">Viajar</h5>
                    <p id="modal-resumo-descricao">Meta de vida, viajar pela Europa...</p>
                    <div class="d-flex justify-content-between mt-4">
                        <p>Andamento da meta atual:</p>
                        <div class="mr-5 text-right">
                            <p class="p-0 m-0"><span id="modal-resumo-saldo" class="font-yellow number">R$ 300,00</span> de <span id="modal-resumo-valor" class="font-green number">R$ 1.500,00</span></p>
                            <div class="">
                                <small class="p-0 m-0">meta <span id="modal-resumo-porcentagem">100%</span> alcançada!</small>
                                <meter id="modal-resumo-porcentagemRange" class="w-100 " type="range" min="0" max="100" low="30" high="85" optimum="90" value="90"></meter>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between my-4">
                        <p>Economizar até:</p>
                        <div class="mr-5">
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
        var meta ="";

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

        function editarMeta(){
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
            if (resultJson === "0 dados encontrados") {
                $(modalResumeMeta).modal('hide');
                $(modalCreateMeta).modal();
            } else if(response.status == 200){
                meta = resultJson;
                document.getElementById('modal-resumo-titulo').innerText = resultJson['titulo'];
                document.getElementById('modal-resumo-descricao').innerText = resultJson['descricao_meta'];
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