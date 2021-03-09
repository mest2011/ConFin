<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<title>Lista de carteiras</title>


</head>

<body class="d-flex flex-wrap" onload="loadCards()">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block body-quebrado">
        <section class="pl-2 pl-sm-0">
            <div class="d-block">
                <div id="emojis" style="position: fixed; z-index: 1; bottom: 0; display:none"></div>
                <div class="d-flex mt-5 mb-3">
                    <img class="card-icone my-auto bg-gray p-2 rounded" src="./images/carteira.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Carteiras</h4>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="font-purple my-auto">Aqui ficam suas carteiras</h5>
                    <div class="font-purple d-flex pr-5 align-items-center mb-3">
                        <!-- <small class="mx-2">Filtrar por:</small>
                        <select class="container-transactions-select p-1" name="filtro" id="filter">
                            <option value="" selected></option>
                            <option value="Data">Data</option>
                            <option value="Valor">Valor</option>
                            <option value="Carteira">Carteira</option>
                        </select> -->
                        <div class="btn-p-fixed-bottom-right z-index-1">
                            <a class="hover-alter d-flex z-index-1 my-2 align-itens-right" onclick="fechaSideModal(); setTimeout(()=>{loadSideModalTransfer()}, 500)">
                                <small class="hover-on my-auto mr-1">Transferir saldo</small>
                                <img class="container-transactions-add hover-off my-2 ml-auto mr-3 " src="./images/transfer-green.png" alt="">
                                <img class="container-transactions-add hover-on my-2 ml-auto  mr-3" src="./images/transfer.png" alt="">
                            </a>
                            <a class="hover-alter d-flex z-index-1 align-itens-right" onclick="fechaSideModal(); setTimeout(()=>{loadSideModal()}, 500)">
                                <small class="hover-on my-auto mr-1">Adicionar carteira</small>
                                <img class="container-transactions-add2 hover-off my-2 ml-auto mr-1 " src="./images/add-green.png" alt="">
                                <img class="container-transactions-add2 hover-on my-2 ml-auto mr-1" src="./images/add-blue.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <hr class="d-sm-none">
        <section>
            <div class="side-modal p-5" id="side-modal"></div>
            <div id="container-cards" class="col-12 d-flex flex-wrap"></div>
        </section>

    </main>
    <?php include "imports/js.php"; ?>

    <script>
        $('.modal-btn-fechar').click(function() {
            $('#ModalCadCategoria').modal('toggle');
            document.getElementById('form-categoria').innerHTML = "";
            carregarCategorias();
        });


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

            const response = await fetch(`../controller/carteira_controller.php`, requestOptions)
            const resultJson = await response.json();

            if (resultJson === "0 dados encontrados") {
                return;
            }

            document.getElementById('container-cards').innerHTML = "";
            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById('container-cards').innerHTML += `
                <div class="cartao w-100 w-sm-auto card-wallet pointer p-0 mr-sm-4 d-block  my-4"
                onclick=\"fechaSideModal(); setTimeout(()=>{loadSideModal(
                            ${resultJson[i]['id_carteira']},
                            '${resultJson[i]['cor']}',
                            '${resultJson[i]['saldo']}',
                            '${resultJson[i]['descricao']}',
                            '${resultJson[i]['nome_carteira']}')}, 500)\"
                                 title="trabalho">
                    <div class="d-flex w-100"  style="border-radius: 1vw 1vw 0 0; background-color: ${resultJson[i]['cor']} !important;">
                        <div class="my-auto d-flex w-100 justify-content-between px-4 pb-2 pt-4 ">
                            <div>
                                <h4 class="my-auto font-white text-shadow">${resultJson[i]['nome_carteira']}</h4>
                            </div>
                            <div class="my-auto d-flex">
                                <p class="hover-red font-white hover-bg-gray my-auto mx-2 p-1 font-weight-bold" onmouseover="this.innerText = 'Excluir?'" onmouseout="this.innerText = '. . .'" onclick="
                                            event.stopPropagation();                                            
                                            if(confirm('Deseja mesmo excluir esse gasto?')){
                                                deletarGasto(${resultJson[i]['id_carteira']});
                                            }
                                            ">. . .
                                <p>
                            </div>
                        </div>
                    </div>
                    <div class="my-2 py-2 px-4">
                        <div class="d-flex w-100 justify-content-between my-auto">
                            <div class="d-block">
                                <small class="font-gray font-weight-bold">Saldo atual</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-weight-bold font-green">R$</p>
                            <h2 class="number font-green">${resultJson[i]['saldo']}</h2>
                        </div>

                        <small class="font-gray font-weight-bold d-block">Descrição</small>
                        <small class="font-gray">${resultJson[i]['descricao']}</small>
                    </div>
                </div>
                    `;
            }
        }

        const deletarGasto = async (id) => {
            try {

                var myHeaders = new Headers();
                myHeaders.append("post", `id=${id}&funcao=excluir`);

                var formdata = new FormData();
                formdata.append("id", id);
                formdata.append("funcao", "excluir");


                const response = await fetch(`../controller/carteira_controller.php`, {
                    method: "POST",
                    body: formdata,
                    headers: myHeaders,
                });

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    _msgEnviadaAnteriormente = true;
                    toastr.success(resultJson, 'Parabéns:');
                    loadCards();
                    //setTimeout(() => { window.location.href = "./index.html" }, 5000);
                }
            } catch (error) {
                console.log(error)
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }

        function loadSideModal(
            id = '',
            cor = '#6b77cc',
            saldo = 'XX,XX',
            descricao = '',
            nome_carteira = 'Nome') {
            let sideModal = document.getElementById('side-modal');

            sideModal.innerHTML = `
                <form id="form-ganho" onsubmit="event.preventDefault(); saveGanho();">
                    <div class="d-block">
                        <input class="d-none" type="text" id="form-id" name="form-id" value="${id}">
                        <h4 class="font-purple">Adicionar carteira</h4>
                        <div class="cartao card-wallet p-4 mr-4 d-block  my-4" style="background-color: ${cor} !important;" title="trabalho">
                    <div class="d-flex w-100">
                        <div class="my-auto d-flex w-100 justify-content-between">
                            <div>
                                <h4 class="my-auto font-white text-shadow">${nome_carteira}</h4>
                            </div>
                            <div class="my-auto d-flex">
                            </div>
                        </div>
                    </div>
                    <div class="my-2">
                        <div class="d-flex w-100 justify-content-between my-auto">
                            <div class="d-block">
                                <small class="font-gray font-weight-bold">Saldo atual</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline my-2">
                            <p class="font-weight-bold font-green">R$</p>
                            <h2 class="number font-green">${saldo}</h2>
                        </div>

                        <small class="font-gray font-weight-bold d-block">Descrição</small>
                        <small class="font-white font-weight-bold text-shadow">${descricao}</small>
                    </div>
                </div>
                    </div>
                    <hr/>
                    <div class="d-block mt-5">
                        <div class="d-flex my-4">
                            <p for="form-cor" class="col-sm-4">Cor:</p>
                            <input type="color" id="form-cor" name="form-cor" value="${cor}" class="input-color"/>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Nome:</p>
                            <input class="form-control col-sm-4" id="form-nome" name="form-nome" type="text" value="${nome_carteira}" maxlength="30">
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Descrição:</p>
                            <textarea class="form-control col-sm-8" id="form-descricao" placeholder="Adicione mais detalhes sobre o gasto..." rows="4" maxlength="50" required>${descricao}</textarea>
                        </div>
                        
                        <div class="d-flex my-5 col-sm-12">
                            <a class="btn btn-white col-6 mx-2" onclick="fechaSideModal()">Cancelar</a>
                            <button submit="form-ganho" class="btn btn-success col-6 mx-2">Salvar</button>
                        </div>
                    </div>
                    </form>
                `;

            $('#side-modal').fadeIn(500).css({
                'margin-right': '0',
                'z-index': '1'
            });
        }

        function loadSideModalTransfer() {
            let sideModal = document.getElementById('side-modal');

            sideModal.innerHTML = `
                <form id="form-ganho" onsubmit="event.preventDefault(); transferirSaldo();">
                    <div class="d-block">
                        <h4 class="font-purple">Transferencia entre carteiras</h4>
                        <img class="w-100 mx-4 my-3" src="./images/img-tranferencia-carteiras.png" alt="Transferencia entre carteiras"/>
                    </div>
                    <hr/>
                    <div class="d-block mt-5">
                    <div class="d-flex my-4">
                            <p class="col-sm-4">Carteira de origem:</p>
                            <select id="form-carteira-origem" name="form-carteira-origem" class="form-control col-sm-6" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Carteira de destino:</p>
                            <select id="form-carteira-destino" name="form-carteira-destino" class="form-control col-sm-6" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Valor:</p>
                            <input class="form-control number col-sm-6 font-red mb-4 font-weight-bold" placeholder="R$" type="text" id="form-valor"  onfocus="ValidaCampos.MoedaUnitarioQuantidade('#form-valor', 2);" value=""  required>
                        </div>
                        
                        <div class="d-flex my-5 col-sm-12">
                            <a class="btn btn-white col-6 mx-2" onclick="fechaSideModal()">Cancelar</a>
                            <button submit="form-ganho" class="btn btn-success col-6 mx-2">Salvar</button>
                        </div>
                    </div>
                    </form>
                `;

            carregarCarteiras();

            $('#side-modal').fadeIn(500).css({
                'margin-right': '0',
                'z-index': '1'
            });
        }

        const buscarCarteiras = async (selectContainer, id_usuario) => {
            var myHeaders = new Headers();
            myHeaders.append("Cookie", "PHPSESSID=9e8p1o4t0fnhdcv3veig0fvrsc");

            var formdata = new FormData();

            var requestOptions = {
                method: 'GET',
                headers: myHeaders,
                redirect: 'follow'
            };

            const response = await fetch(`../controller/carteira_controller.php?id_usuario=${id_usuario}&funcao=listartudo`, requestOptions)
            const resultJson = await response.json();

            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById(selectContainer).innerHTML += `<option value="${resultJson[i]['id_carteira']}">${resultJson[i]['nome_carteira']}</option>`;
            }
        }

        function carregarCarteiras() {
            const localcarteiraorigem = buscarCarteiras('form-carteira-origem', id_usuario);
            const localcarteiradestino = buscarCarteiras('form-carteira-destino', id_usuario);
        }

        const saveGanho = async () => {

            let id = document.getElementById('form-id').value;
            let nome = document.getElementById('form-nome').value;
            let cor = document.getElementById('form-cor').value;
            let descricao = document.getElementById('form-descricao').value;

            console.log(id);
            console.log(nome);
            console.log(cor);
            console.log(descricao);

            try {

                var myHeaders = new Headers();
                myHeaders.append("post", `id_usuario=${id_usuario}&id=${id}&nome_cateira=${nome}&cor=${cor}&descricao=${descricao}&funcao=salvar`);


                var formdata = new FormData();
                formdata.append("funcao", "salvar");
                formdata.append("id_usuario", id_usuario);
                if (id > 0) {
                    formdata.append("id", id);
                }
                formdata.append("nome_cateira", nome);
                formdata.append("cor", cor);
                formdata.append("descricao", descricao);



                const response = await fetch(`../controller/carteira_controller.php`, {
                    method: "POST",
                    body: formdata,
                    headers: myHeaders,
                });

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    _msgEnviadaAnteriormente = true;
                    toastr.success(resultJson, 'Parabéns:');
                    loadCards();
                    fechaSideModal();
                    //setTimeout(() => { window.location.href = "./index.html" }, 5000);
                }
            } catch (error) {
                console.log(error)
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }

        const transferirSaldo = async () => {

            let carteiraOrigem = document.getElementById('form-carteira-origem').value;
            let carteiraDestino = document.getElementById('form-carteira-destino').value;
            let valor = document.getElementById('form-valor').value;

            console.log(carteiraOrigem);
            console.log(carteiraDestino);
            console.log(valor);

            try {

                var myHeaders = new Headers();
                myHeaders.append("post", `id_carteira_origem=${carteiraOrigem}&id_carteira_destino=${carteiraDestino}&valor=${valor}&funcao=transferir`);


                var formdata = new FormData();
                formdata.append("funcao", "transferir");
                formdata.append("id_carteira_origem", carteiraOrigem);
                formdata.append("id_carteira_destino", carteiraDestino);
                formdata.append("valor", valor);



                const response = await fetch(`../controller/carteira_controller.php`, {
                    method: "POST",
                    body: formdata,
                    headers: myHeaders,
                });

                const resultJson = await response.json();
                if (resultJson.search("Erro") > -1 && response.status == 200) {
                    toastr.error(resultJson, 'Atenção:');
                } else {
                    _msgEnviadaAnteriormente = true;
                    toastr.success(resultJson, 'Parabéns:');
                    loadCards();
                    fechaSideModal();
                    //setTimeout(() => { window.location.href = "./index.html" }, 5000);
                }
            } catch (error) {
                console.log(error)
                toastr.clear();
                toastr.warning("Erro no envio dos dados.<br/>Problema ao comunicar-se com o sistema!<br/>Tente mais tarde, por favor!", 'Ops!');
            }
        }

        function fechaSideModal() {
            $('#side-modal').css({
                'margin-right': '-55%'
            }).fadeOut(500);
        }
    </script>
</body>

</html>