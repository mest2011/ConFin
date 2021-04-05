<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<title>Lista de extratos do mês</title>
</head>

<body class="d-flex flex-wrap body-quebrado" onload="loadCards()">
    <header class="col-md-1 col-sm-2 col-12">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 col-12 d-block mt--23em">
        <section>
            <div class="d-block pb-2">
                <div id="emojis" style="position: fixed; z-index: 1; bottom: 0; display:none"></div>
                <div class="d-flex mt-0 mt-sm-5 mb-3">
                    <img class="card-icone my-auto p-2 bg-gray rounded" src="./images/calendario.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Extrato</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <h5 class="font-green my-auto">Transações</h5>
                    <div class="font-purple d-flex align-items-center my-4 mr-3">
                        <div class="d-flex">
                            <div class="mr-1 mt-1">
                                <small class="d-block">Filtrar por:</small>
                                <select class="container-transactions-select form-control form-control-sm" name="filtro" id="filter" onchange="refreshDataFilter(this.value); showBtnRefresh();">
                                    <option value="" selected></option>
                                    <option value="Data">Data</option>
                                    <option value="Categoria">Categoria</option>
                                    <option value="Carteira">Carteira</option>
                                </select>
                            </div>

                        </div>
                        <div id="form-dt" class="ml-auto d-none">
                            <div class="d-block mt-1 mr-1">
                                <small>Data de inicio:</small>
                                <input class="container-transactions-select form-control form-control-sm" type="date" name="dt_ini" id="dt_ini" min="2020-01-01" onchange="showBtnRefresh()">
                            </div>
                            <div class="d-block mt-1 mr-1">
                                <small>Data de fim:</small>
                                <input class="container-transactions-select form-control form-control-sm" type="date" name="dt_fim" id="dt_fim" min="2020-01-01" onchange="showBtnRefresh()">
                            </div>
                        </div>
                        <div id="form-categoria" class="ml-auto d-none">
                            <div class="d-block mt-1 mr-1">
                                <small>Categoria:</small>
                                <select class="container-transactions-select form-control form-control-sm" name="filtro-categoria" id="filter-categoria" onchange="buscarDadosPorCategoria()">
                                    <option value="" selected></option>
                                    <option value="Entre carteiras">Entre carteiras</option>
                                    <optgroup label="Ganhos">
                                        <?php
                                        include_once "../controller/categoria_controller.php";
                                        $categorias = new Categorias($_SESSION['id_usuario']);
                                        foreach ($categorias->listaCategoria(0) as $key => $value) {
                                            echo "<option value='{$value['nome_categoria']}'>{$value['nome_categoria']}</option>";
                                        }
                                        echo "</optgroup>";
                                        echo "<optgroup label=\"Gastos\">";
                                        foreach ($categorias->listaCategoria(1) as $key => $value) {
                                            echo "<option value='{$value['nome_categoria']}'>{$value['nome_categoria']}</option>";
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div id="form-carteira" class="ml-auto d-none">
                            <div class="d-block mt-1 mr-1">
                                <small>Carteira:</small>
                                <select class="container-transactions-select form-control form-control-sm" name="filtro-carteira" id="filter-carteira" onchange="buscarDadosPorCarteira()">
                                    <option value="" selected></option>
                                    <option value="Entre carteiras">Entre carteiras</option>
                                    <?php
                                    include_once "../controller/carteira_controller.php";
                                    $carteiras = new Carteiras($_SESSION['id_usuario']);
                                    foreach ($carteiras->listarCarteiras() as $key => $value) {
                                        echo "<option value='{$value['id_carteira']}'>{$value['nome_carteira']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button class="btn p-2 mt-auto d-none" id="btn-refresh" onclick="buscarDadosPorData()">
                            <img src="../../dependencies/img/sync-alt-solid.svg" alt="Refresh" style="height: 1em;">
                        </button>
                    </div>

                </div>

            </div>
            <section class="container-transactions bg-gray d-flex flex-wrap">
                <div class="col-md-1 col-sm-2"></div>

                <div class="col-md-11 col-sm-10 col-12 pl-4 p-sm-4">
                    <div class="side-modal p-5 mobile-off" id="side-modal"></div>
                    <div id="container-cards" class="col-12 d-block" id="container-main"></div>
                </div>
            </section>
        </section>
    </main>
    <?php include "imports/js.php"; ?>
    <script>
        //Toastr
        toastr.options.closeButton = true;
        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 300;
        toastr.options.closeEasing = 'swing';
        toastr.options.preventDuplicates = true;

        const id_usuario = <?php echo $_SESSION['id_usuario']; ?>

        //Requisições

        const refreshDataFilter = (data) => {

            document.getElementById('form-dt').classList.remove('d-flex')
            document.getElementById('form-dt').classList.add('d-none')
            document.getElementById('form-categoria').classList.remove('d-flex')
            document.getElementById('form-categoria').classList.add('d-none')
            document.getElementById('form-carteira').classList.remove('d-flex')
            document.getElementById('form-carteira').classList.add('d-none')

            switch (data) {
                case "Data":
                    document.getElementById('form-dt').classList.remove('d-none')
                    document.getElementById('form-dt').classList.add('d-flex')
                    break;

                case "Categoria":
                    document.getElementById('form-categoria').classList.remove('d-none')
                    document.getElementById('form-categoria').classList.add('d-flex')
                    break;

                case "Carteira":
                    document.getElementById('form-carteira').classList.remove('d-none')
                    document.getElementById('form-carteira').classList.add('d-flex')
                    break;

                default:
                    break;
            }
        }

        const buscarDadosPorData = () => {
            filter = {
                'dt_ini': document.getElementById('dt_ini').value,
                'dt_fim': document.getElementById('dt_fim').value,
            }
            loadCards(filter);
        }

        const buscarDadosPorCategoria = () => {
            filter = {
                'categoria': document.getElementById('filter-categoria').value,
            }
            loadCards(filter);
        }

        const buscarDadosPorCarteira = () => {
            filter = {
                'carteira': document.getElementById('filter-carteira').value,
            }
            loadCards(filter);
        }

        const showBtnRefresh = () => {
            let btnRefresh = document.getElementById('btn-refresh')
            let filtro = document.getElementById('filter').value

            if (filtro == 'Data') {
                let dt_ini = document.getElementById('dt_ini').value
                let dt_fim = document.getElementById('dt_fim').value
                if (dt_ini && dt_fim) {
                    btnRefresh.classList.remove('d-none')
                    btnRefresh.classList.add('d-block')
                } else {
                    btnRefresh.classList.remove('d-block')
                    btnRefresh.classList.add('d-none')
                }
                return;
            }

            btnRefresh.classList.remove('d-block')
            btnRefresh.classList.add('d-none');

        }


        const loadCards = async (filter = false) => {
            <?php if (isset($_GET['id_carteira_meta'])) {
                echo "filter = {
                    'carteira': {$_GET['id_carteira_meta']},
                };";
            }?>
            var myHeaders = new Headers();
            myHeaders.append("post", `funcao=listar`);

            var formdata = new FormData();
            formdata.append("funcao", "listar");
            if (filter != false) {
                for (item in filter) {
                    formdata.append(item, filter[item]);
                }
            }

            var requestOptions = {
                method: 'POST',
                body: formdata,
                headers: myHeaders,
                redirect: 'follow'
            };

            const response = await fetch(`../controller/extrato_controller.php`, requestOptions)
            const resultJson = await response.json();

            if (resultJson === "0 dados encontrados") {
                return;
            }

            document.getElementById('container-cards').innerHTML = "";
            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById('container-cards').innerHTML += `
                    <div class=\"cartao p-3 mr-0 mr-sm-4 d-block  my-4\"  title=\"trabalho\" >
                            <div class=\"d-flex w-100\">
                                <h4 class=\"cartao-icon  bg-gray my-auto p-2 mx-2\">${resultJson[i]['icone']}</h4>
                                <div class=\"my-auto d-flex w-100 justify-content-between flex-wrap\">
                                    <div>
                                        <h4 class=\"my-auto font-purple\">${resultJson[i]['titulo']}</h4>
                                        <small class=\"my-auto font-gray\">Data da transação: ${resultJson[i]['data_ptbr']}</small>
                                    </div>
                                    <div class=\"my-auto d-flex\">
                                        <h4 class=\"${((resultJson[i]['valor']).replace('.', '').replace(',','.') < 0)? 'font-red' : 'font-green'} number my-auto\">R$${resultJson[i]['valor']}</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class=\"my-4\">
                            <div class=\"m-2\">
                            <div class=\"d-flex w-100 justify-content-between my-auto\">
                                <div class=\"d-block\">
                                    <!--<p class=\"d-block font-weight-bold my-auto\">Categoria:</p> -->
                                    <p class=\"${((resultJson[i]['valor']).replace('.', '').replace(',','.') < 0)? 'font-red' : 'font-green'} font-weight-bold\">${resultJson[i]['tipo']}</p>
                                </div>
                            <small class=\"font-white bg-purple p-2 rounded mb-auto\">${resultJson[i]['nome_carteira']}</small>
                            </div>
                            <small class=\"d-block font-weight-bold my-auto\">Descrição:</small>
                            <small class=\"font-gray\">${resultJson[i]['descricao']}</small>
                            </div>

                        </div>`;
            }
        }
    </script>
</body>

</html>