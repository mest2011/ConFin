<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<?php include "imports/head_parameters.php"; ?>  
<?php
include_once "../classes/Ganho.php";
include_once "../controller/ganho_controller.php";

if (isset($_GET['id'])) {
    $ganho = new Ganho();
    $ganho_controller = new GanhoController($_SESSION['id_usuario']);
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

        $ganho_controller = new GanhoController($ganho->id_usuario);
        echo "<script>var saveStatus ='".$ganho_controller->atualizarGanho($ganho)."';</script>";
    } else {

        $ganho->titulo = $_POST['titulo'];
        $ganho->descricao = $_POST['descricao'];
        $ganho->categoria = $_POST['categoria'];
        $ganho->data_do_credito = $_POST['data'];
        $ganho->valor = $_POST['valor'];
        $ganho->id_ganho = 0;
        $ganho->id_usuario = $_SESSION['id_usuario'];

        $ganho_controller = new GanhoController($ganho->id_usuario);
        echo "<script>var saveStatus ='".$ganho_controller->salvarGanho($ganho)."';</script>";
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
                    <label class="form-check-label" for="ftitulo">Titulo</label>
                    <input class="form-control" id="ftitulo" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->titulo; ?>" placeholder="Insira o titulo da transação" name="titulo" maxlength="20" required></Input>
                    <label class="form-check-label" for="fdescricao">Descrição</label>
                    <input class="form-control" id="fdescricao" type="text" value="<?php if (isset($_GET['id'])) echo $ganho->descricao; ?>" placeholder="Insira a descrição da transferência" name="descricao" maxlength="50">
                    <label class="form-check-label" for="fcategoria">Categoria</label>
                    <select class="form-control" id="fcategoria" name="categoria" required> 
                        <option value="Outros" disabled selected>Selecione</option>
                        <option value="Trabalho" <?php if (isset($_GET['id']) and $ganho->categoria === "Trabalho") echo "selected"; ?>>Trabalho</option>
                        <option value="Freelance" <?php if (isset($_GET['id']) and $ganho->categoria === "Freelance") echo "selected"; ?>>Freelance "Bico"</option>
                        <option value="Loteria" <?php if (isset($_GET['id']) and $ganho->categoria === "Loteria") echo "selected"; ?>>Loteria</option>
                        <option value="Herança" <?php if (isset($_GET['id']) and $ganho->categoria === "Herança") echo "selected"; ?>>Herança</option>
                        <option value="Investimento" <?php if (isset($_GET['id']) and $ganho->categoria === "Investimento") echo "selected"; ?>>Investimento</option>
                        <option value="Outros" <?php if (isset($_GET['id']) and $ganho->categoria === "Outros") echo "selected"; ?>>Outros</option>
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


    <script>
        var span = document.getElementById('saveStatus');
        if(typeof saveStatus == "string"){
            span.innerText = saveStatus;
            if(saveStatus.search("sucesso") > -1){
                span.classList.add('alert-success');
            }else{
                span.classList.add('alert-danger');
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php include "imports/js.php";?>
</body>

</html>