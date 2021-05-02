<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>

<title>Lista de ganhos do mês</title>

<link rel="stylesheet" href="./lib/css/emojis.css">

</head>

<body class="d-flex flex-wrap" onload="loadCards()">
    <header class="col-md-1 col-sm-2 col-12">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 col-12 d-block mt--23em">
        <section>
            <div class="d-block">
                <div id="emojis" style="position: fixed; z-index: 1; bottom: 0; display:none"></div>
                <div class="d-flex mt-0 mt-sm-5 mb-3">
                    <img class="card-icone my-auto" src="./images/ganhos.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Aqui ficam seus ganhos</h4>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="font-green my-auto">Recebidos</h5>
                    <div class="font-purple d-flex pr-5 align-items-center mb-3">
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
                            <div id="form-cat" class="ml-auto d-none">
                                <div class="d-block mt-1 mr-1">
                                    <small>Categoria:</small>
                                    <select class="container-transactions-select form-control form-control-sm" name="filtro-categoria" id="filter-categoria" onchange="buscarDadosPorCategoria()">
                                        <option value="" selected></option>
                                        <?php
                                        include_once "../controller/categoria_controller.php";
                                        $obj_categorias = new Categorias($_SESSION['id_usuario']);
                                        foreach ($obj_categorias->listaCategoria(0) as $key => $value) {
                                            echo "<option value='{$value['nome_categoria']}'>{$value['nome_categoria']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="form-cart" class="ml-auto d-none">
                                <div class="d-block mt-1 mr-1">
                                    <small>Carteira:</small>
                                    <select class="container-transactions-select form-control form-control-sm" name="filtro-carteira" id="filter-carteira" onchange="buscarDadosPorCarteira()">
                                        <option value="" selected></option>
                                        <?php
                                        include_once "../controller/carteira_controller.php";
                                        $obj_carteiras = new Carteiras($_SESSION['id_usuario']);
                                        foreach ($obj_carteiras->listarCarteiras() as $key => $value) {
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
                        <a class="hover-alter ml-3" onclick="fechaSideModal(); setTimeout(()=>{loadSideModal()}, 500)">
                            <img class="container-transactions-add hover-off my-auto" src="./images/plus.png" alt="">
                            <img class="container-transactions-add my-auto hover-on" src="./images/plus-green.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-transactions bg-gray d-flex flex-wrap">
            <div class="col-md-1 col-sm-2 mobile-off"></div>

            <div class="col-md-11 col-sm-10 col-12 pl-4 p-sm-4" id="container-main">
                <div class="side-modal p-5" id="side-modal"></div>
                <div id="container-cards" class="col-12 d-block "></div>
            </div>

        </section>
    </main>
    <div class="modal fade" id="ModalCadCategoria" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 1em;">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Nova Categoria</h3>
                    <button type="button" class="close modal-btn-fechar" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-body-categoria" class="modal-body p-4">
                    <div>
                        <form id="formCadCategoria" action="">
                            <input type='text' name='tipo' style='display:none;' value='1'>
                            <div class="form-group">
                                <label for="nome" class="col-form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" maxlength="30" autocomplete="off" required>
                            </div>
                            <span id="span-status" class="alert "></span>
                        </form>
                    </div>
                    <div id='lista-categoria'></div>
                </div>
                <div class="modal-footer">
                    <button id="modal-btn-fechar" type="button" class="btn modal-btn-fechar btn-danger col-5 mr-auto">Fechar</button>
                    <button id="modal-btn-salvar" type="button" class="btn btn-success col-5" name="submit" form="formCadCategoria" onclick="cadCategoria();">Salvar</button>
                </div>
            </div>
        </div>
    </div>
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

        var zoomNaImagemAtivo = true;

        //Requisições
        const refreshDataFilter = (data) => {

            document.getElementById('form-dt').classList.remove('d-flex')
            document.getElementById('form-dt').classList.add('d-none')
            document.getElementById('form-cat').classList.remove('d-flex')
            document.getElementById('form-cat').classList.add('d-none')
            document.getElementById('form-cart').classList.remove('d-flex')
            document.getElementById('form-cart').classList.add('d-none')

            switch (data) {
                case "Data":
                    document.getElementById('form-dt').classList.remove('d-none')
                    document.getElementById('form-dt').classList.add('d-flex')
                    break;

                case "Categoria":
                    document.getElementById('form-cat').classList.remove('d-none')
                    document.getElementById('form-cat').classList.add('d-flex')
                    break;

                case "Carteira":
                    document.getElementById('form-cart').classList.remove('d-none')
                    document.getElementById('form-cart').classList.add('d-flex')
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

            const response = await fetch(`../controller/ganho_controller.php`, requestOptions)
            const resultJson = await response.json();

            if (resultJson === "0 dados encontrados") {
                return;
            }

            document.getElementById('container-cards').innerHTML = "";
            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById('container-cards').innerHTML += `
                    <div class=\"cartao pointer p-3 mr-0 mr-sm-4 d-block  my-4\" onclick=\"fechaSideModal(); setTimeout(()=>{loadSideModal(
                            ${resultJson[i]['id_ganho']},
                            \`${resultJson[i]['tipo']}\`,
                            \`${resultJson[i]['icone']}\`,
                            \`${resultJson[i]['titulo']}\`,
                            \`${resultJson[i]['data_do_credito']}\`,
                            \`${resultJson[i]['id_carteira']}\`,
                            \`${resultJson[i]['nome_carteira']}\`,
                            \`${resultJson[i]['descricao']}\`,
                            \`${resultJson[i]['valor']}\`,
                            \`${resultJson[i]['comprovante']}\`)}, 500)\" title=\"trabalho\" >
                            <div class=\"d-flex w-100\">
                                <h4 class=\"cartao-icon  bg-gray my-auto p-2 mx-2\">${resultJson[i]['icone']}</h4>
                                <div class=\"my-auto d-flex w-100 justify-content-between flex-wrap\">
                                    <div>
                                        <h4 class=\"my-auto font-purple\">${resultJson[i]['titulo']}</h4>
                                        <small class=\"my-auto font-gray\">Data da crédito: ${resultJson[i]['data_do_credito_ptbr']}</small>
                                    </div>
                                    <div class=\"my-auto d-flex\">
                                        <h4 class=\"font-green number my-auto\">R$${resultJson[i]['valor']}</h4>
                                        <p class="hover-red hover-bg-gray my-auto mx-2 p-1 font-weight-bold" onmouseover="this.innerText = 'Excluir?'" onmouseout="this.innerText = '. . .'" onclick="
                                            event.stopPropagation();                                            
                                            if(confirm('Deseja mesmo excluir esse gasto?')){
                                                deletarGasto(${resultJson[i]['id_ganho']});
                                            }
                                            ">. . .<p>
                                    </div>
                                </div>
                            </div>
                            <hr class=\"my-4\">
                            <div class=\"m-2\">
                            <div class=\"d-flex w-100 justify-content-between my-auto\">
                                <div class=\"d-block\">
                                    <!--<p class=\"d-block font-weight-bold my-auto\">Categoria:</p> -->
                                    <p class=\"font-green font-weight-bold\">${resultJson[i]['tipo']}</p>
                                </div>
                            <small class=\"font-white bg-purple p-2 rounded mb-auto\">${resultJson[i]['nome_carteira']}</small>
                            </div>
                            <small class=\"d-block font-weight-bold my-auto\">Descrição:</small>
                            <small class=\"font-gray\">${resultJson[i]['descricao']}</small>
                            </div>

                        </div>`;
            }
        }


        function cadCategoria() {
            let nome = document.getElementById('nome').value;
            nome = nome.replace(['\'', '\\', '>', '<', '"'], '');

            let modalSpan = document.getElementById('span-status');

            let url = `cad_categoria.php?ajax=true&nome=${nome}&tipo=0&id_usuario=<?php echo $_SESSION['id_usuario'] ?>`;

            let xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status = 200) {
                        let result = xhr.responseText;
                        if (typeof result == "string") {
                            modalSpan.innerText = result;
                            if (result.search("sucesso") > -1) {
                                modalSpan.classList.remove('alert-danger');
                                modalSpan.classList.add('alert-success');
                                modalSpan.innerHTML = modalSpan.innerText;
                                document.getElementById('modal-btn-fechar').style.display = 'none';
                                document.getElementById('modal-btn-salvar').style.display = 'none';
                                document.getElementById('lista-categoria').style.display = 'none';
                            } else {
                                modalSpan.classList.add('alert-danger');
                            }
                        }
                    } else {
                        console.log("Erro ao salvar categoria");
                    }

                }
            }
            xhr.send();
        }

        function listaCategoria(tipo) {
            let modalBody = document.getElementById('lista-categoria');

            let url = `cad_categoria.php?ajax=true&listar=${tipo}&id_usuario=<?php echo $_SESSION['id_usuario'] ?>`;

            let xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status = 200) {

                        let result = xhr.responseText;
                        modalBody.innerHTML = result;
                    } else {
                        console.log("Erro ao listar categoria");
                    }

                }
            }
            xhr.send();
        }

        function deletaCategoria(id) {
            if (!confirm("Deseja realmente apagar essa categoria?")) return false;

            let url = `cad_categoria.php?ajax=true&apagar=${id}&id_usuario=<?php echo $_SESSION['id_usuario'] ?>`;

            let xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status = 200) {
                        let result = xhr.responseText;
                        alert(result);
                        listaCategoria(0);
                    } else {
                        console.log("Erro ao listar categoria");
                    }

                }
            }
            xhr.send();
        }

        const deletarGasto = async (id) => {
            try {

                var myHeaders = new Headers();
                myHeaders.append("post", `id=${id}&funcao=excluir`);

                var formdata = new FormData();
                formdata.append("id", id);
                formdata.append("funcao", "excluir");


                const response = await fetch(`../controller/ganho_controller.php`, {
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

        function loadSideModal(id = '',
            categoria = '',
            icone = '&#x1f4b0',
            titulo = '',
            data = '',
            id_carteira = '',
            carteira = 'Selecione',
            descricao = '',
            valor = '',
            comprovante = '') {
            let sideModal = document.getElementById('side-modal');
            zoomNaImagemAtivo = true;

            sideModal.innerHTML = `
                <form id="form-ganho" onsubmit="event.preventDefault(); saveGanho();">
                <div class="d-flex">
                        <input class="d-none" type="text" id="form-id" name="form-id" value="${id}">
                        <h3 class="p-3 bg-gray my-auto mr-3 rounded" id="form-icon" onclick="hiddenShowEmojiKeyboard()">${icone}</h3>
                        <input class="form-control font-title-modal" type="text" id="form-title" name="form-title" value="${titulo}" maxlength="30" placeholder="Titulo do ganho" required>
                    </div>
                    <hr/>
                    <div class="d-block mt-5">
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Categoria:</p>
                            <select id="form-categoria" name="form-categoria" class="form-control col-sm-4" required>
                                <option value="${categoria}" selected>${categoria}</option>
                            </select>
                            <a type="button" data-toggle="modal" data-target="#ModalCadCategoria" class="pointer bg-green bg-darkgreen rounded-circle ml-2 my-auto p-1  btn-add-transfer" style="line-height:1" onclick="listaCategoria(0);">✚</a>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Valor total:</p>
                            <input class="form-control number col-sm-4 font-green font-weight-bold" placeholder="R$" type="text" id="form-valor"  onfocus="ValidaCampos.MoedaUnitarioQuantidade('#form-valor', 2);" value="${valor}"  required>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Data do recebimento:</p>
                            <input class="form-control col-sm-6" id="form-data" name="form-data" type="date" value="${(data)===''?new Date().getFullYear()+'-'+String(new Date().getMonth() + 1).padStart(2, '0')+'-'+String(new Date().getDate()).padStart(2, '0'):data}"  min="2000-01-01" required>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Carteira:</p>
                            <select id="form-carteira" name="form-carteira" class="form-control col-sm-8" required>
                                <option value="${id_carteira}">${carteira}</option>
                            </select>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Descrição:</p>
                            <textarea class="form-control col-sm-8" id="form-descricao" placeholder="Adicione mais detalhes sobre o gasto..." rows="4" maxlength="100">${descricao}</textarea>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Anexar comprovante:</p>
                            <div class="p-0 col-sm-8">
                                <label type="button" for="form-comprovante" class="pointer bg-green bg-darkgreen rounded-circle mt-1 mb-auto p-2  btn-add-transfer" style="line-height:1">✚</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                <input type="file" class="form-control-file d-none" 
                                    onchange="
                                        if(this.files[0].size > 3145728){
                                            toastr.error('Tamanho do arquivo não pode exceder 3MB!', 'Atenção:');
                                            this.value = null;
                                            document.getElementById('span-file').innerText = this.files[0].name;
                                        }else{
                                            document.getElementById('span-file').innerText = this.files[0].name; 
                                        }" id="form-comprovante" accept=".jpg, .jpeg">
                                <small class="font-purple mt-1 p-0 col-12" id="span-file"></small>
                            </div>
                        </div>
                        <div class="my-4  ${(comprovante==='')?'d-none':'d-flex '}">
                            <div class="col-sm-4 m-auto">
                                <a href="../../uploads/${comprovante}" download="Comprovante_titulo-${titulo}_data-${data}" class="btn btn-dark m-auto">Baixar ↷</a>
                            </div>
                            <div class="col-sm-8 m-auto" 
                                onclick="if(zoomNaImagemAtivo){
                                            this.classList.add('div-view-img');
                                            this.children[0].classList.add('img-fullscreen');
                                            zoomNaImagemAtivo = false;
                                        }else{
                                            zoomNaImagemAtivo = true;
                                            this.classList.remove('div-view-img');
                                            this.children[0].classList.remove('img-fullscreen');
                                        }">
                                <img id="file" class="foto-perfil my-auto" src="../../uploads/${comprovante}" alt="Anexo" onerror="this.src='../../uploads/avatar.svg'">
                            </div>
                        </div>
                        
                        
                        <div class="d-flex my-5 col-sm-12">
                            <a class="btn btn-white col-6 mx-2" onclick="fechaSideModal()">Cancelar</a>
                            <button submit="form-ganho" class="btn btn-success col-6 mx-2">Salvar</button>
                        </div>
                    </div>
                    </form>
                `;

            carregarCategorias();
            carregarCarteiras();

            $('#side-modal').fadeIn(500).css({
                'margin-right': '0',
                'z-index': '1'
            });


        }

        const saveGanho = async () => {

            hiddenShowEmojiKeyboard(false);

            let id = document.getElementById('form-id').value;
            let icon = emojiUnicode(document.getElementById('form-icon').innerText);
            let title = document.getElementById('form-title').value;
            let categoria = document.getElementById('form-categoria').value;
            let data = document.getElementById('form-data').value;
            let carteira = document.getElementById('form-carteira').value;
            let descricao = document.getElementById('form-descricao').value;
            let valor = document.getElementById('form-valor').value;
            let file = document.getElementById('form-comprovante').files[0];

            console.log(id);
            console.log(icon);
            console.log(title);
            console.log(categoria);
            console.log(data);
            console.log(carteira);
            console.log(descricao);
            console.log(valor);

            try {

                var myHeaders = new Headers();
                myHeaders.append("post", `id_usuario=${id_usuario}&id=${id}&data=${data}&valor=${valor}`);


                var formdata = new FormData();
                formdata.append("id_usuario", id_usuario);
                if (id > 0) {
                    formdata.append("id", id);
                }
                if (file) {
                    formdata.append('file', file);
                }
                formdata.append("icone", icon);
                formdata.append("titulo", title);
                formdata.append("categoria", categoria);
                formdata.append("data", data);
                formdata.append("carteira", carteira);
                formdata.append("descricao", descricao);
                formdata.append("valor", valor);



                const response = await fetch(`../controller/ganho_controller.php`, {
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
            if (resultJson === "0 dados encontrados") {
                return;
            }

            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById(selectContainer).innerHTML += `<option value="${resultJson[i]['id_carteira']}">${resultJson[i]['nome_carteira']}</option>`;
            }
        }

        const buscarCategorias = async (selectContainer, id_usuario) => {
            var myHeaders = new Headers();
            myHeaders.append("Cookie", "PHPSESSID=9e8p1o4t0fnhdcv3veig0fvrsc");

            var formdata = new FormData();

            var requestOptions = {
                method: 'GET',
                headers: myHeaders,
                redirect: 'follow'
            };

            const response = await fetch(`../controller/categoria_controller.php?id_usuario=${id_usuario}&funcao=listartudo&tipo=0`, requestOptions)
            const resultJson = await response.json();
            if (resultJson === "0 dados encontrados") {
                return;
            }

            for (var i = 0; i < resultJson.length; i++) {
                document.getElementById(selectContainer).innerHTML += `<option value="${resultJson[i]['nome_categoria']}">${resultJson[i]['nome_categoria']}</option>`;
            }
        }


        function carregarCarteiras() {
            const localcarteiras = buscarCarteiras('form-carteira', id_usuario);
        }

        function carregarCategorias() {
            const localcategoria = buscarCategorias('form-categoria', id_usuario);
        }


        function fechaSideModal() {
            $('#side-modal').css({
                'margin-right': '-55%'
            }).fadeOut(500);
            hiddenShowEmojiKeyboard(false)
        }


        //emoji
        function trocaEmojiForm(value) {
            document.getElementById('form-icon').innerHTML = value;
            hiddenShowEmojiKeyboard(false);
        }

        function hiddenShowEmojiKeyboard(visible = "default") {
            let keyEmoji = document.getElementById('emojis');
            if (keyEmoji.style.display == "block" || visible == false) {
                keyEmoji.style.display = "none";
            } else if (keyEmoji.style.display == "none" || visible == true) {
                keyEmoji.style.display = "block"
            }
        }

        //Gerador do emoji keybord
        $("#emojis").disMojiPicker()
        $("#emojis").picker(emoji => trocaEmojiForm(emoji));
        twemoji.parse(document.getElementById('emojis'));
    </script>
</body>

</html>