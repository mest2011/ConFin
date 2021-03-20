<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../classes/Gasto.php";
include_once "../controller/gastos_controller.php";
include_once "../controller/categoria_controller.php";

if (isset($_GET['id'])) {
    $gasto = new Gasto();
    $gastos_controller = new Gastos($_SESSION['id_usuario']);
    $gasto = $gastos_controller->retorna_gasto($_GET['id']);
}

if (isset($_POST['valor'])) {
    $gasto = new Gasto();
    if (isset($_POST['id_despesa'])) {
        $gasto->titulo = $_POST['titulo'];
        $gasto->descricao = $_POST['descricao'];
        $gasto->categoria = $_POST['categoria'];
        $gasto->data = $_POST['data'];
        $gasto->valor = $_POST['valor'];
        $gasto->id_despesa = $_POST['id_despesa'];
        $gasto->id_usuario = $_SESSION['id_usuario'];
        $gasto->id_carteira = $_POST['carteira'];

        $gastos_controller = new Gastos($gasto->id_usuario);
        echo "<script>var saveStatus ='" . $gastos_controller->atualizarGasto($gasto) . "';</script>";
    } else {

        $gasto->titulo = $_POST['titulo'];
        $gasto->descricao = $_POST['descricao'];
        $gasto->categoria = $_POST['categoria'];
        $gasto->data = $_POST['data'];
        $gasto->valor = $_POST['valor'];
        $gasto->id_despesa = 0;
        $gasto->id_usuario = $_SESSION['id_usuario'];
        if (!isset($_POST['qtd_recorrente'])) $_POST['qtd_recorrente'] = 1;
        $gasto->qtd_recorrente = $_POST['qtd_recorrente'];
        $gasto->id_carteira = $_POST['carteira'];

        $gastos_controller = new Gastos($gasto->id_usuario);
        echo "<script>var saveStatus ='" . $gastos_controller->salvarGasto($gasto) . "';</script>";
    }
}


?>



<link rel="stylesheet" href="../view/css/form.css">

<title>Lista de gastos mensais</title>
<style>
    form {
        display: grid;
    }

    label {
        font-weight: 900;
    }
</style>
<script src="js/jquery-3.2.1.slim.min.js"></script>
</head>

<body>
    <section class="body">
        <?php include "imports/menu_lateral.php"; ?>
        <section class="main conteudo">
            <header>
                <h1>Cadastro de gasto</h1>
            </header>
            <main>
                <form id="form-despesa" method="POST" action="cad_gasto.php" class="form form-group">
                    <?php if (isset($_GET['id'])) echo "<input type='text'  name='id_despesa' style='display:none;' value='{$gasto->id_despesa}'>" ?>
                    <label class="form-check-label" for="fcategoria">Categoria
                        <a type="button" data-toggle="modal" data-target="#ModalCadCategoria" class="btn btn-Info" onclick="listaCategoria(1);">+</a>
                    </label>
                    <select class="form-control" id="fcategoria" name="categoria" required>
                        <option value="Outros" disabled selected>Selecione</option>
                        <?php
                        $categoria_con = new CategoriaController($_SESSION['id_usuario']);

                        foreach ($categoria_con->listaCategoria(1) as $key => $value) {
                            echo "<option value=\"{$value['nome_categoria']}\" ";
                            echo ">{$value['nome_categoria']}</option>";
                        }
                        ?>
                    </select>
                    <label class="form-check-label" for="ftitulo">Titulo</label>
                    <input class="form-control" id="ftitulo" type="text" value="<?php if (isset($_GET['id'])) echo $gasto->titulo; ?>" placeholder="Insira o titulo da transação" name="titulo" maxlength="20" required></Input>
                    <label class="form-check-label" for="fdescricao">Descrição</label>
                    <input class="form-control" id="fdescricao" type="text" value="<?php if (isset($_GET['id'])) echo $gasto->descricao; ?>" placeholder="Insira a descrição da transferência" name="descricao" maxlength="50">
                    <label class="form-check-label" for="fcarteira">Carteira</label>
                    <select class="form-control" id="fcarteira" name="carteira" required <?php //if (isset($_GET['id'])){echo "disabled";}
                                                                                            ?>>
                        <?php
                        $gastos_controller = new Gastos($_SESSION['id_usuario']);

                        foreach ($gastos_controller->listarCarteiras() as $key => $value) {
                            echo "<option value=\"{$value['id_carteira']}\" ";
                            if (isset($_GET['id']) and $gasto->id_carteira === $value['id_carteira']) {
                                echo "selected";
                            }
                            echo ">{$value['nome_carteira']}</option>";
                        }
                        ?>

                    </select>
                    <label class="form-check-label" for="fdata">Data</label>
                    <input class="form-control" id="fdata" type="date" value="<?php if (isset($_GET['id'])) echo $gasto->data; ?>" placeholder="Data do débito" name="data" required>
                    <label class="form-check-label" for="fvalor">Valor</label>
                    <input class="form-control" id="fvalor" type="text" value="<?php if (isset($_GET['id'])) echo $gasto->valor; ?>" pattern="[0-9]{0,3}?.?[0-9]{0,3}?.?[0-9]{1,3},?[0-9]{0,2}" placeholder="Valor do débito: R$ 00,00" name="valor" required>
                    <!-- <label class="form-check-label" for="frecorrente">Despesa recorrente?</label> -->
                    <!--  <input class="form-control" id="frecorrente" type="range" name="qtd_recorrente" value="1" min="1" max="12" onchange="updateTextRecorrente()"> -->
                    <!-- <p id="spanRecorrente"></p> -->
                    <span id="saveStatus" class="alert "></span>

                    <button name="submit" class="bnt btn-success">Salvar</button>
                </form>
            </main>
            <footer></footer>
        </section>
    </section>

    <div class="modal fade" id="ModalCadCategoria" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Nova Categoria</h3>
                    <button type="button" class="close modal-btn-fechar" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-body-categoria" class="modal-body">
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
                    <button id="modal-btn-fechar" type="button" class="btn modal-btn-fechar btn-danger">Fechar</button>
                    <button id="modal-btn-salvar" type="button" class="btn btn-success" name="submit" form="formCadCategoria" onclick="cadCategoria();">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.modal-btn-fechar').click(function() {
            $('#ModalCadCategoria').modal('toggle');
            location.reload();
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
    </script>


    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php include "imports/js.php"; ?>
</body>

</html>