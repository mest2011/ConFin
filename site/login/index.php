<?php
include_once "../business/connection.php";
include_once "../business/security.php";
session_start();

if (isset($_POST["user"]) and isset($_POST["password"])) {
    $user = $_POST["user"];
    $passsword = $_POST["password"];
    validate($user, $passsword);
}else{
    header("Location: ../home_page/index.php");
}


function validate($user, $password){
    $db = new Db();

    $sec = new Security();

    $user = $sec->fix_string($user);
    $password = $sec->fix_string($password);
    //echo "<h1>{$user}<h1><br/> <h1>{$password}<h1>";

    $conn = $db->connect();
    $sql = "SELECT * FROM tb_usuario WHERE usuario = '".$user."' and senha = '".$password."' LIMIT 1;";

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
        header("Location: ../home_page/index.php?erro=userorpasswordinvalide");
    }


}




?>