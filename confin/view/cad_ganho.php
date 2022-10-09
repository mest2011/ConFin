<link rel="stylesheet" href="css/bootstrap.min.css">
<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../classes/Ganho.php";
include_once "../controller/ganho_controller.php";
include_once "../controller/categoria_controller.php";

if (isset($_GET['id'])) {
    $ganho = new Ganho();
    $ganho_controller = new Ganhos($_SESSION['id_usuario']);
    $ganho = $ganho_controller->buscarGanho($_GET['id']);
}

if (isset($_POST['valor'])) {
    $ganho = new Ganho();
    if (isset($_POST['id_ganho'])) {
        $ganho->titulo = $_POST['titulo'];
        $ganho->descricao = $_POST['descricao'];
        $ganho->categoria = $_POST['categoria'];
        $ganho->data_do_credito = $_POST['data'];
        $ganho->valor = $_POST['valor'];
        $ganho->id_ganho = $_POST['id_ganho'];
        $ganho->id_usuario = $_SESSION['id_usuario'];
        $ganho->id_carteira = $_POST['carteira'];

        $ganho_controller = new Ganhos($ganho->id_usuario);
        echo "<script>var saveStatus ='" . $ganho_controller->atualizarGanho($ganho) . "';</script>";
    } else {

        $ganho->titulo = $_POST['titulo'];
        $ganho->descricao = $_POST['descricao'];
        $ganho->categoria = $_POST['categoria'];
        $ganho->data_do_credito = $_POST['data'];
        $ganho->valor = $_POST['valor'];
        $ganho->id_ganho = 0;
        $ganho->id_usuario = $_SESSION['id_usuario'];
        $ganho->id_carteira = $_POST['carteira'];

        $ganho_controller = new Ganhos($ganho->id_usuario);
        echo "<script>var saveStatus ='" . $ganho_controller->salvarGanho($ganho) . "';</script>";
    }
}


?>

<link rel="stylesheet" href="../view/css/form.css">

<title>Lista de ganho mensais</title>
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
                <h1>Cadastro de ganhos</h1>
            </header>
            <main>
                <form id="form-ganho" method="POST" action="cad_ganho.php" class="form form-group">
                    <?php if (isset($_GET['id'])) echo "<input type='text'  name='id_ganho' style='display:none;' value='{$ganho->id_ganho}'>" ?>
                    
                    <label class="form-check-label" for="fcategoria">Categoria
                        <a type="button" data-toggle="modal" data-target="#ModalCadCategoria" class="btn btn-Info" onclick="listaCategoria(0);">+</a>
                    </label>
                    <select class="form-control" id="fcategoria" name="categoria" required>
                        <option value="Outros" disabled selected>Selecione</option>
                        <?php
                        $categoria_con = new CategoriaController($_SESSION['id_usuario']);

                        foreach ($categoria_con->listaCategoria(0) as $key => $value) {
                            echo "<option value=\"{$value['nome_categoria']}\" ";
                            echo ">{$value['nome_categoria']}</option>";
                        }
                        ?>
                    </select>
                    <label class="form-check-label" for="ftitulo">Titulo</label>
                    <input class="form-control" id="ftitulo" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->titulo; ?>" placeholder="Insira o titulo da transação" name="titulo" maxlength="20" required></Input>
                    <label class="form-check-label" for="fdescricao">Descrição</label>
                    <input class="form-control" id="fdescricao" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->descricao; ?>" placeholder="Insira a descrição da transferência" name="descricao" maxlength="50">
                    <label class="form-check-label" for="fcarteira">Carteira</label>
                    <select class="form-control" id="fcarteira" name="carteira" required <?php //if (isset($_GET['id'])){echo "disabled";}
                                                                                            ?>>
                        <?php
                        $ganho_controller = new Ganhos($_SESSION['id_usuario']);

                        foreach ($ganho_controller->listarCarteiras() as $key => $value) {
                            echo "<option value=\"{$value['id_carteira']}\" ";
                            if (isset($_GET['id']) and $ganho->id_carteira === $value['id_carteira']) {
                                echo "selected";
                            }
                            echo ">{$value['nome_carteira']}</option>";
                        }
                        ?>

                    </select>
                    <label class="form-check-label" for="fdata">Data</label>
                    <input class="form-control" id="fdata" type="date" value="<?php if (isset($_GET['id'])) echo $ganho->data_do_credito; ?>" placeholder="Data do crédito" name="data" required>
                    <label class="form-check-label" for="fvalor">Valor</label>
                    <input class="form-control" id="fvalor" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->valor; ?>" pattern="[0-9]{0,3}?.?[0-9]{0,3}?.?[0-9]{1,3},?[0-9]{0,2}" placeholder="Valor do crédito: R$ 00,00" name="valor" required>

                    <span id="saveStatus" class="alert "></span>
                    <button name="submit" class="bnt btn-success">Salvar</button>
                </form>
            </main>
        </section>
    </section>
    <footer></footer>

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
                            <input type='text' name='tipo' style='display:none;' value='0'>
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
        $('.modal-btn-fechar').click(function(){
            $('#ModalCadCategoria').modal('toggle');
            location.reload();
        });

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
                        listaCategoria(0);
                    } else {
                        console.log("Erro listar categoria");
                    }

                }
            }
            xhr.send();
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