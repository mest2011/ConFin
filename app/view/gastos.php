<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../controller/gastos_controller.php";

$obj_gastos = new Gastos($_SESSION['id_usuario']);


// excluir gasto
if (isset($_GET['id'])) {
    print_r($obj_gastos->excluir($_GET['id']));
    $url = strpos($_SERVER["REQUEST_URI"], "?");
    $url = substr($_SERVER["REQUEST_URI"], 0, $url);
    //header("Location: {$url}");
}

?>
<link rel="stylesheet" href="../view/css/table.css">
<title>Lista de gastos mensais</title>

<link rel="stylesheet" href="./lib/css/emojis.css">

</head>

<body class="d-flex">
    <header class="col-md-1 col-sm-2">
        <?php include "imports/menu_lateral.php"; ?>
    </header>
    <main class="col-md-11 col-sm-10 d-block">
        <section>
            <div class="d-block">
                <div id="emojis" style="position: fixed; z-index: 1; bottom: 0; display:none"></div>
                <div class="d-flex mt-5 mb-3">
                    <img class="card-icone my-auto" src="./images/gastos.png" alt="">
                    <h4 class="font-purple my-auto ml-2">Aqui ficam os seus gastos</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <h5 class="font-red my-auto">Saídas</h5>
                    <div class="font-purple d-flex pr-5 align-items-center mb-3">
                        <small class="mx-2">Filtrar por:</small>
                        <select class="container-transactions-select p-1" name="filtro" id="filter">
                            <option value="Data">Data</option>
                            <option value="Valor">Valor</option>
                            <option value="Carteira">Carteira</option>
                        </select>
                        <a class="hover-alter ml-3" onclick="fechaSideModal(); setTimeout(()=>{loadSideModal()}, 500)">
                            <img class="container-transactions-add hover-off my-auto" src="./images/plus.png" alt="">
                            <img class="container-transactions-add my-auto hover-on" src="./images/plus-red.png" alt="">
                        </a>
                    </div>
                </div>

            </div>
        </section>
        <section id="lista" class="container-transactions bg-gray d-flex">
            <div class="col-md-1 col-sm-2"></div>


            <div class="col-md-11 col-sm-10 p-4">
                <div class="side-modal p-5" id="side-modal">

                </div>

                <?php
                $render = "";
                $result = $obj_gastos->lista_gastos();
                if (gettype($result) === "array") {
                    foreach ($result as $key => $value) {
                        $date = date_create($value['data_do_debito']);
                        $render .= "
                        <div class=\"cartao pointer p-3 mr-4 d-block  my-4\" onclick=\"fechaSideModal(); setTimeout(()=>{loadSideModal(
                            " . $value['id_despesa'] . ",
                            '" . $value['tipo'] . "',
                            '👜',
                            '" . $value['titulo'] . "',
                            '" . $value['data_do_debito'] . "',
                             '" . $value['id_carteira'] . "',
                              '" . $value['nome_carteira'] . "',
                               '" . $value['descricao'] . "',
                                '" . number_format($value['valor'], 2, ',', '.') . "')}, 500)\" title=\"trabalho\" >
                            <div class=\"d-flex w-100\">
                                <h4 class=\"cartao  bg-gray my-auto p-2 mx-2\">🥶</h4>
                                <div class=\"my-auto d-flex w-100 justify-content-between\">
                                    <div>
                                        <h4 class=\"my-auto font-purple\">" . $value['tipo'] . "</h4>
                                        <small class=\"my-auto font-gray\">Data do recebimento: " . date_format($date, 'd/m/Y') . "</small>
                                    </div>
                                    <div class=\"my-auto\">
                                        <h4 class=\"font-red number my-auto mr-5\">R$" . number_format($value['valor'], 2, ',', '.') . "</h4>
                                    </div>
                                </div>
                            </div>
                            <hr class=\"my-4\">
                            <div class=\"m-2 pr-4\">
                            <div class=\"d-flex w-100 justify-content-between my-auto\">
                            <h5 class=\"font-purple font-weight-bold\">" . $value['titulo'] . "</h5>
                            <p class=\"font-white bg-purple p-2 rounded my-auto\">" . $value['nome_carteira'] . "</p>
                            </div>
                            <p class=\"font-weight-bold my-auto\">Descrição:</p>
                            <p class=\"font-gray\">" . $value['descricao'] . "</p>
                            </div>

                        </div>";
                    }
                } else {
                    $render .= "";
                }
                echo $render;
                ?>


            </div>


            </div>
        </section>
        </section>
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

            function cadCategoria() {
                let nome = document.getElementById('nome').value;
                nome = nome.replace(['\'', '\\', '>', '<', '"'], '');

                let modalSpan = document.getElementById('span-status');

                let url = `cad_categoria.php?ajax=true&nome=${nome}&tipo=1&id_usuario=<?php echo $_SESSION['id_usuario'] ?>`;

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
                            console.log("Erro salvar categoria");
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
                            console.log("Erro listar categoria");
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
                            listaCategoria(1);
                        } else {
                            console.log("Erro listar categoria");
                        }

                    }
                }
                xhr.send();
            }

            function updateTextRecorrente() {
                let val = document.getElementById('frecorrente').value;
                document.getElementById('spanRecorrente').innerText = "A despesa se repete por: " + val + " mes(es)";
            }
            var span = document.getElementById('saveStatus');
            if (typeof saveStatus == "string") {
                span.innerText = saveStatus;
                if (saveStatus.search("sucesso") > -1) {
                    span.classList.add('alert-success');
                } else {
                    span.classList.add('alert-danger');
                }
            }


            //Toastr
            toastr.options.closeButton = true;
            toastr.options.closeMethod = 'fadeOut';
            toastr.options.closeDuration = 300;
            toastr.options.closeEasing = 'swing';
            toastr.options.preventDuplicates = true;

            const id_usuario = <?php echo $_SESSION['id_usuario']; ?>

            //Requisições
            function loadSideModal(id = '',
                categoria = '',
                icone = '💸',
                titulo = '',
                data = '',
                id_carteira = '',
                carteira = 'Selecione',
                descricao = '',
                valor = '') {
                let sideModal = document.getElementById('side-modal');

                sideModal.innerHTML = `
                <form id="form-gasto" onsubmit="event.preventDefault(); saveGasto();">
                <div class="d-flex">
                        <input class="d-none" type="text" id="form-id" name="form-id" value="${id}">
                        <h3 class="p-3 bg-gray my-auto mr-2 cartao" id="form-icon" onclick="hiddenShowEmojiKeyboard()">${icone}</h3>
                        <input class="form-control font-title-modal" type="text" id="form-title" name="form-title" value="${titulo}" max="20" required>
                    </div>
                    <hr/>
                    <div class="d-block mt-5">
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Categoria:</p>
                            <select id="form-categoria" name="form-categoria" class="form-control col-sm-4" required>
                                <option value="${categoria}" selected>${categoria}</option>
                            </select>
                            <a type="button" data-toggle="modal" data-target="#ModalCadCategoria" class="btn bg-green ml-2" onclick="listaCategoria(1);">✚</a>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Data do recebimento:</p>
                            <input class="form-control col-sm-4" id="form-data" name="form-data" type="date" value="${data}" min="2000-01-01" required>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Carteira:</p>
                            <select id="form-carteira" name="form-carteira" class="form-control col-sm-4" required>
                                <option value="${id_carteira}">${carteira}</option>
                            </select>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Descrição:</p>
                            <textarea class="form-control col-sm-8" id="form-descricao" placeholder="Adicione mais detalhes sobre o gasto..." rows="4" max="50" required>${descricao}</textarea>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Valor total:</p>
                            <input class="form-control number col-sm-6 font-red font-weight-bold" placeholder="R$" type="text" id="form-valor"  onfocus="ValidaCampos.MoedaUnitarioQuantidade('#form-valor', 2);" value="${valor}"  required>
                        </div>
                        
                        <div class="d-flex my-5 col-sm-12">
                            <a class="btn btn-white col-6 mx-2" onclick="fechaSideModal()">Cancelar</a>
                            <button submit="form-gasto" class="btn btn-success col-6 mx-2">Salvar</button>
                        </div>
                    </div>
                    </form>
                `;

                carregarCategorias();
                carregarCarteiras();

                $('#side-modal').fadeIn(500).css({
                    'margin-right': '0'
                });


            }

            function formatavalor() {
                let valor = document.getElementById('form-valor');
                valor.value = valor.value.toLocaleString('pt-br', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }

            const saveGasto = async () => {

                let id = document.getElementById('form-id').value;
                let icon = document.getElementById('form-icon').innerText;
                let title = document.getElementById('form-title').value;
                let categoria = document.getElementById('form-categoria').value;
                let data = document.getElementById('form-data').value;
                let carteira = document.getElementById('form-carteira').value;
                let descricao = document.getElementById('form-descricao').value;
                let valor = document.getElementById('form-valor').value;

                if (id > 0) {
                    console.log(id);
                }
                console.log(icon);
                console.log(title);
                console.log(categoria);
                console.log(data);
                console.log(carteira);
                console.log(descricao);
                console.log(valor);

                try {

                    var myHeaders = new Headers();
                    myHeaders.append("post", `id_usuario=${id_usuario}&id=${id}&titulo=${title}&categoria=${categoria}&data=${data}&carteira=${carteira}&descricao=${descricao}&valor=${valor}`);
                    //&icon=${icon}

                    var formdata = new FormData();
                    formdata.append("id_usuario", id_usuario);
                    if (id > 0) {
                        formdata.append("id", id);
                    }
                    //formdata.append("icon", encodeURIComponent(icon));
                    formdata.append("titulo", title);
                    formdata.append("categoria", categoria);
                    formdata.append("data", data);
                    formdata.append("carteira", carteira);
                    formdata.append("descricao", descricao);
                    formdata.append("valor", valor);



                    const response = await fetch(`../controller/gastos_controller.php`, {
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

                const response = await fetch(`../controller/categoria_controller.php?id_usuario=${id_usuario}&funcao=listartudo&tipo=1`, requestOptions)
                const resultJson = await response.json();

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

            function hiddenShowEmojiKeyboard(visible = "default") {
                let keyEmoji = document.getElementById('emojis');
                if (keyEmoji.style.display == "block" || visible == false) {
                    keyEmoji.style.display = "none";
                } else if (keyEmoji.style.display == "none" || visible == true) {
                    keyEmoji.style.display = "block"
                }
            }

            function fechaSideModal() {
                $('#side-modal').css({
                    'margin-right': '-55%'
                }).fadeOut(500);
            }

            function trocaEmojiForm(value) {
                document.getElementById('form-icon').innerHTML = value;
                hiddenShowEmojiKeyboard(false);
            }


            function excluir(id) {
                if (confirm("Deseja mesmo excluir esse gasto?")) {
                    window.location.href = window.location.href + `?id=${id}`
                }
            }

            function editar(id) {
                window.location.href = `cad_gasto.php?id=${id}`
            }

            //Gerador do emoji keybord
            $("#emojis").disMojiPicker()
            $("#emojis").picker(emoji => trocaEmojiForm(emoji));
            twemoji.parse(document.getElementById('emojis'));
        </script>
</body>

</html>