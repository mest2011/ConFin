<?php

include_once "../database/connection.php";
include_once "../business/security.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST["email"], $_POST["password"])) {
    $user = $_POST["email"];
    $passsword = $_POST["password"];
    validate($user, $passsword);
}else{
    header("Location: ../view/login.html");
}


function validate($user, $password){
    //echo "<h1>{$user}<h1><br/> <h1>{$password}<h1>";
    $db = new Db();
    
    $sec = new Security();
    
    $user = $sec->fix_string($user);
    $password = $sec->fix_string($password);
    
    $conn = $db->connect();
    $sql = "SELECT * FROM tb_usuario WHERE usuario = '{$user}' and senha = '{$password}' LIMIT 1;";
    
    //echo "<h1>{$sql}</h1>";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION["status"] = "logado";
        while($row = $result->fetch_assoc()){
            $id = $row['id_usuario'];}
        $_SESSION["id_usuario"] = $id;
        $_SESSION['time'] = date_create();
        header("Location: ../../confin/view/dashboard.php");
    }else{
        $_SESSION["status"] = "";
        $_SESSION["id_usuario"] = "";
        header("Location: ../view/login.html?erro=userorpasswordinvalide");
    }


}
