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
                        <div class=\"cartao pointer p-3 mr-4 d-block  my-4\" onclick=\"fechaSideModal(); setTimeout(()=>{loadSideModal('👜', '" . $value['titulo'] . "','" . $value['data_do_debito'] . "', '" . $value['nome_carteira'] . "', '" . $value['descricao'] . "', '" . number_format($value['valor'], 2, ',', '.') . "')}, 500)\" title=\"trabalho\" >
                            <div class=\"d-flex w-100\">
                                <h4 class=\"cartao  bg-gray my-auto p-2 mx-2\">🥶</h4>
                                <div class=\"my-auto d-flex w-100 justify-content-between\">
                                    <div>
                                        <h4 class=\"my-auto font-purple\">" . $value['titulo'] . "</h4>
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
                                    <p class=\"font-weight-bold my-auto\">Descrição:</p>
                                    <p class=\"font-white bg-purple p-2 rounded my-auto\">" . $value['nome_carteira'] . "</p>
                                </div>
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
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <?php include "imports/js.php"; ?>

        <script>
            img.draggable;

            function loadSideModal(icone = '👜', titulo = '', data = '', carteira = 'Carteira', descricao = '', valor = '') {
                let sideModal = document.getElementById('side-modal');

                sideModal.innerHTML = `
                <div class="d-flex">
                        <h3 class="p-3 bg-gray my-auto mr-2 cartao" id="form-icon" onclick="hiddenShowEmojiKeyboard()">${icone}</h3>
                        <input class="form-control font-title-modal" type="text" name="titulo" value="${titulo}">
                    </div>
                    <hr/>
                    <div class="d-block mt-5">
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Data do recebimento:</p>
                            <input class="form-control col-sm-4" type="date" value="${data}">
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Carteira:</p>
                            <select class="form-control col-sm-4">
                                <option name="${carteira}" value="${carteira}" selected>${carteira}</option>
                            </select>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Descrição:</p>
                            <textarea class="form-control col-sm-8" placeholder="Adicione mais detalhes sobre o gasto..." rows="4">${descricao}</textarea>
                        </div>
                        <div class="d-flex my-4">
                            <p class="col-sm-4">Valor total:</p>
                            <input class="form-control col-sm-6 font-red font-weight-bold" placeholder="R$" type="text" value="${valor}">
                        </div>
                        
                        <div class="d-flex my-5 col-sm-12">
                            <button class="btn btn-light col-6 bg-white border-white" onclick="fechaSideModal()">Cancelar</button>
                            <button class="btn btn-success col-6 ">Salvar</button>
                        </div>
                    </div>
                `;

                $('#side-modal').fadeIn(500).css({
                    'margin-right': '0'
                });


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
        </script>
        <script src="./lib/js/twemoji.min.js"></script>
        <script src="./lib/js/DisMojiPicker.js"></script>
        <script>
            $("#emojis").disMojiPicker()
            $("#emojis").picker(emoji => trocaEmojiForm(emoji));
            twemoji.parse(document.getElementById('emojis'));
        </script>
</body>

</html>