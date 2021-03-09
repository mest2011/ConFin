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
            <div class="d-block">
                <div id="emojis" style="position: fixed; z-index: 1; bottom: 0; display:none"></div>
                <div class="d-flex mt-0 mt-sm-5 mb-3">
                    <img class="card-icone my-auto p-2 bg-gray rounded" src="./images/calendario.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Extrato</h4>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="font-green my-auto">Transações</h5>
                    <div class="font-purple d-flex pr-5 align-items-center my-4">
                        <!-- <small class="mx-2">Filtrar por:</small>
                        <select class="container-transactions-select p-1" name="filtro" id="filter">
                            <option value="" selected></option>
                            <option value="Data">Data</option>
                            <option value="Valor">Valor</option>
                            <option value="Carteira">Carteira</option>
                        </select> -->
                        <!-- <a class="hover-alter ml-3" onclick="fechaSideModal(); setTimeout(()=>{loadSideModal()}, 500)">
                            <img class="container-transactions-add hover-off my-auto" src="./images/plus.png" alt="">
                            <img class="container-transactions-add my-auto hover-on" src="./images/plus-green.png" alt="">
                        </a> -->
                    </div>
                </div>
            </div>
            <section class="container-transactions bg-gray d-flex flex-wrap">
                <div class="col-md-1 col-sm-2"></div>

                <div class="col-md-11 col-sm-10 col-12 pl-4 p-sm-4">
                    <div class="side-modal p-5 mobile-off" id="side-modal"></div>
                    <div id="container-cards" class="col-12 d-block"  id="container-main"></div>
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
        const loadCards = async (orderBy = false) => {
            var myHeaders = new Headers();
            myHeaders.append("post", `funcao=listar`);

            var formdata = new FormData();
            formdata.append("funcao", "listar");

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
                                        <h4 class=\"${(resultJson[i]['valor'] < 0)? 'font-red' : 'font-green'} number my-auto\">R$${resultJson[i]['valor']}</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class=\"my-4\">
                            <div class=\"m-2\">
                            <div class=\"d-flex w-100 justify-content-between my-auto\">
                                <div class=\"d-block\">
                                    <!--<p class=\"d-block font-weight-bold my-auto\">Categoria:</p> -->
                                    <p class=\"${(resultJson[i]['valor'] < 0)? 'font-red' : 'font-green'} font-weight-bold\">${resultJson[i]['tipo']}</p>
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