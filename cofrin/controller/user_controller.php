<?php

include_once "../database/connection.php";
include_once "../business/security.php";

if (!isset($_SESSION)) {
    session_start();
}

try{
   //Login com google
    if (isset($_GET['login_google'])) {
        $result = with_google(createObjPessoa());
        if($result == false){
            echo 'erro';
        }else{
            echo $result;
        }
        exit();
    } 
}catch (\Throwable $th) {
    echo $th;
}


if (isset($_POST["email"], $_POST["password"])) {
    $user = $_POST["email"];
    $passsword = $_POST["password"];
    validate($user, $passsword);
}else{
    header("Location: ../view/login.html");
}


function validate($user, $password){
    $db = new Db();
    
    $sec = new Security();
    
    $user = $sec->fix_string($user);
    $password = $sec->fix_string($password);
    
    $conn = $db->connect();
    $sql = "SELECT id_usuario, usuario, (SELECT nome FROM tb_pessoa WHERE id_pessoa = tb_usuario.id_pessoa LIMIT 1) as nome, (SELECT foto FROM tb_pessoa WHERE id_pessoa = tb_usuario.id_pessoa LIMIT 1) as foto FROM tb_usuario WHERE usuario = '{$user}' and senha = '{$password}' LIMIT 1;";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION["status"] = "logado";
        while($row = $result->fetch_assoc()){
            $id = $row['id_usuario'];
            $nome = $row['nome'];
            $usuario = $row['usuario'];
            $foto = $row['foto'];
        }
        $_SESSION["id_usuario"] = $id;
        $_SESSION['time'] = date_create();
        $_SESSION['nome'] = $nome;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['foto'] = $foto;
        header("Location: ../../app/view/dashboard.php");
    }else{
        $_SESSION["status"] = "";
        $_SESSION["id_usuario"] = "";
        header("Location: ../view/login.html?erro=userorpasswordinvalide");
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

function with_google($obj_google){
    $db = new Db();
    
    $sec = new Security();
    
    $user = $obj_google->email;
    $user_id_google = $obj_google->userIDGoogle;
    
    $conn = $db->connect();
    $sql = "SELECT id_usuario, usuario, (SELECT nome FROM tb_pessoa WHERE id_pessoa = tb_usuario.id_pessoa LIMIT 1) as nome, (SELECT foto FROM tb_pessoa WHERE id_pessoa = tb_usuario.id_pessoa LIMIT 1) as foto FROM tb_usuario WHERE usuario = '{$user}' and useridgoogle = '{$user_id_google}' LIMIT 1;";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION["status"] = "logado";
        while($row = $result->fetch_assoc()){
            $id = $row['id_usuario'];
            $nome = $row['nome'];
            $usuario = $row['usuario'];
            $foto = $row['foto'];
        }
        $_SESSION["id_usuario"] = $id;
        $_SESSION['time'] = date_create();
        $_SESSION['nome'] = $nome;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['foto'] = $foto;
        return '../../app/view/dashboard.php';
    }else{
        $_SESSION["status"] = "";
        $_SESSION["id_usuario"] = "";
        return false;
    }
}