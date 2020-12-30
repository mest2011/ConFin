<?php
include_once "../business/connection.php";
session_start();

if (isset($_POST["user"]) and isset($_POST["password"])) {
    $user = $_POST["user"];
    $passsword = $_POST["password"];
    validate($user, $passsword);
}else{
    header("Location: ../home_page/index.php");
}


function validate($user, $passsword){
    $db = new Db();

    $conn = $db->connect();
    $sql = "SELECT * FROM tb_usuario WHERE usuario = '".$user."' and senha = '".$passsword."';";

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