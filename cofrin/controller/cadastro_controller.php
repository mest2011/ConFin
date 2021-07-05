<?php
include_once "../business/security.php";
include_once "../business/cadastro_bus.php";
include_once "../class/pessoa.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['login_google'])) {
    if(with_google() == false){
        echo 'erro';
    }else {
        $url = './user_controller.php?login_google=true';
        $params = array(
            'userName' => (string)filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING),
            'userEmail' => (string)filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING),
            'userID' => (string)filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING),
            'userToken' => (string)filter_input(INPUT_POST, 'userToken', FILTER_SANITIZE_STRING),
            'userPicture' => (string)filter_input(INPUT_POST, 'userPicture', FILTER_SANITIZE_STRING)
        );
        $response = post_request($url, $params);
        sleep(1);
        echo $response;
    }
    exit();
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

function post_request($url, array $params) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "{$_SERVER['SERVER_NAME']}/confin/cofrin/controller/user_controller.php?login_google=true",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $params
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
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
            return false;
        }else{
            return true;
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

function createObjPessoa(){
    
    $date = new DateTime();
    $pessoal = (object)[];
    
    $pessoal->nome = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
    $pessoal->email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
    $pessoal->usuario = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
    $pessoal->senha = (string)$date->getTimestamp();
    $pessoal->userIDGoogle = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
    $pessoal->userToken = filter_input(INPUT_POST, 'userToken', FILTER_SANITIZE_STRING);
    $pessoal->userPicture = filter_input(INPUT_POST, 'userPicture', FILTER_SANITIZE_STRING);

    return $pessoal;
}

function salvarUsuario($obj_usuario)
{
    return CadastroUsuario::salvarUsuario($obj_usuario);
}

function with_google(){
    return CadastroUsuario::salvarUsuarioGoogle(createObjPessoa());
}