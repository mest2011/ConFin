<?php
include_once "../business/security.php";
include_once "../business/cadastro_bus.php";
include_once "../class/pessoa.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['checkExist'], $_GET['campo'])) {
    findEquals($_GET['checkExist'], $_GET['campo']);
} else {
    $pessoa = validaCampos();
    if ($pessoa === false) {
        print_r(json_encode("Erro ao realizar o cadastro, campos invalidos!"));
    } else {
        if (!salvarUsuario($pessoa)) {
            print_r(json_encode('Erro ao salvar seus dados! <br>Por favor, tente mais tarde!'));
        } else {
            print_r(json_encode('Usuario salvo com sucesso!'));
        }
    }
}


function findEquals($valor, $campo)
{
    try {

        $db = new Db();
        $conn = $db->connect();


        $sql = "SELECT id_pessoa FROM tb_pessoa WHERE {$campo} = '" . $valor . "'";

        $result = $conn->query($sql);
        $lines = $result->num_rows;

        if ($lines > 0) {
            print_r(json_encode("O {$campo}:<br> <b>{$valor}</b><br> não pode ser usado, pois ja está em uso!"));
        }
        $db->close($conn);
    } catch (\Throwable $th) {
        $db->close($conn);
    }

    exit();
}

function validaCampos()
{
    $pessoa = new Pessoa();
    $sec = new Security();
    if (
        isset($_POST['name'], $_POST['lastName'], $_POST['email'], $_POST['date_of_birth'], $_POST['password'])
    ) {
        $pessoa->nome = $_POST['name'] . " " . $_POST['lastName'];
        $pessoa->email = $_POST['email'];
        $pessoa->datanascimento = $_POST['date_of_birth'];
        $pessoa->usuario = $sec->fix_string($_POST['email']);
        $pessoa->senha = $sec->fix_string($_POST['password']);
        return $pessoa;
    } else {
        return false;
    }
}

function salvarUsuario($obj_usuario)
{
    return CadastroUsuario::salvarUsuario($obj_usuario);
}
