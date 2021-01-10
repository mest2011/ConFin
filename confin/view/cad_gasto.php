<link rel="stylesheet" href="css/bootstrap.min.css">

<?php include "imports/head_parameters.php"; ?>
<?php
include_once "../classes/Gasto.php";
include_once "../controller/gastos_controller.php";

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
                    <label class="form-check-label" for="ftitulo">Titulo</label>
                    <input class="form-control" id="ftitulo" type="text" value="<?php if (isset($_GET['id'])) echo $gasto->titulo; ?>" placeholder="Insira o titulo da transação" name="titulo" maxlength="20" required></Input>
                    <label class="form-check-label" for="fdescricao">Descrição</label>
                    <input class="form-control" id="fdescricao" type="text" value="<?php if (isset($_GET['id'])) echo $gasto->descricao; ?>" placeholder="Insira a descrição da transferência" name="descricao" maxlength="50">
                    <label class="form-check-label" for="fcarteira">Carteira</label>
                    <select class="form-control" id="fcarteira" name="carteira" required <?php if (isset($_GET['id'])){echo "disabled";}?>>
                        <?php
                            $gastos_controller = new Gastos($_SESSION['id_usuario']);
                            
                            foreach ($gastos_controller->listarCarteiras() as $key => $value) {
                                echo "<option value=\"{$value['id_carteira']}\" ";
                                if (isset($_GET['id']) and $gasto->id_carteira === $value['id_carteira']){
                                     echo "selected";
                                    }
                                echo ">{$value['nome_carteira']}</option>";
                            }
                        ?>
                        
                    </select>
                    <label class="form-check-label" for="fcategoria">Categoria</label>
                    <select class="form-control" id="fcategoria" name="categoria" required>
                        <option disabled value="Outros" <?php if (!isset($_GET['id'])) echo "selected"; ?>>Selecione</option>
                        <option value="Casa" <?php if (isset($_GET['id']) and $gasto->categoria === "Casa") echo "selected"; ?>>Casa</option>
                        <option value="Estudos" <?php if (isset($_GET['id']) and $gasto->categoria === "Estudos") echo "selected"; ?>>Estudos</option>
                        <option value="Farmacia" <?php if (isset($_GET['id']) and $gasto->categoria === "Farmacia") echo "selected"; ?>>Farmacia</option>
                        <option value="Mercado" <?php if (isset($_GET['id']) and $gasto->categoria === "Mercado") echo "selected"; ?>>Mercado</option>
                        <option value="Transporte" <?php if (isset($_GET['id']) and $gasto->categoria === "Transporte") echo "selected"; ?>>Transporte</option>
                        <option value="Outros" <?php if (isset($_GET['id']) and $gasto->categoria === "Outros") echo "selected"; ?>>Outros</option>
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
    <script>
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

    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php include "imports/js.php"; ?>
</body>

</html>