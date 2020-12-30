<?php
include_once "../business/connection.php";
include_once "../classes/Pessoa.php";

if (isset($_GET['checkExist'])) {
        $data = array();
        $db = new Db();
        $conn = $db->connect();

        $campo= $_GET['checkExist'];
        $value= $_GET['value'];

        if ($campo == "email" or $campo == "cpf") {
            if(strlen($value) > 0){
                $sql = "SELECT id_pessoa FROM tb_pessoa WHERE email = '".$value."' or cpf = '".$value."'";
            }
        }elseif ($campo == "user") {
            $sql = "SELECT id_usuario FROM tb_usuario WHERE usuario = '".$value."'";
        }


        $result = $conn->query($sql);
        $lines = $result->num_rows;

        if($lines > 0){             
            echo "O ". $campo." <b style='color:black'>". $value."</b> não pode ser usado pois ja foi cadastrado antes!";
        }
    
    exit();
}

$result = validaCampos();
if ($result != false) {
    if(!salvarUsuario($result)){
        echo "<script>alert(\"Erro ao salvar seus dados!\");</script>";
    }else{
        echo "<script>alert(\"Usuario salvo com sucesso!\");
        window.location.href = \"../home_page/index.php\";</script>";
    }

}else{
    //echo "sem dados";
}

   
    

function validaCampos(){
    $pessoa = new Pessoa();
    if (isset($_POST['name']) and
        isset($_POST['cpf']) and
        isset($_POST['email']) and
        isset($_POST['address']) and
        isset($_POST['city']) and
        isset($_POST['country']) and
        isset($_POST['date_of_birth']) and
        isset($_POST['user']) and
        isset($_POST['password'])
        ) {
            $pessoa->nome = $_POST['name'];
            $pessoa->cpf = $_POST['cpf'];
            $pessoa->email = $_POST['email'];
            $pessoa->endereco = $_POST['address'];
            $pessoa->cidade = $_POST['city'];
            $pessoa->pais = $_POST['country'];
            $pessoa->datanascimento = $_POST['date_of_birth'];
            $pessoa->usuario = $_POST['user'];
            $pessoa->senha = $_POST['password'];
            return $pessoa;
    }else {
        return false;
    }
}

        


function salvarUsuario($pessoa){
    try {
        $db = new Db();
        $conn = $db->connect();
        $sql = "INSERT INTO tb_pessoa (nome, cpf, email, endereco, cidade, pais, data_nascimento) VALUES 
        ('".$pessoa->nome."','".$pessoa->cpf."','".$pessoa->email."','".$pessoa->endereco."', '".$pessoa->cidade."', '".$pessoa->pais."', '".$pessoa->datanascimento."');
        ";
        echo $sql;
        if($conn->query($sql) === TRUE){
            $last_id = $conn->insert_id;

            $sql = "INSERT INTO tb_usuario (usuario, senha, id_pessoa) VALUES ('".$pessoa->usuario."', '".$pessoa->senha."', 
            $last_id);";

            if($conn->query($sql) === TRUE){
                $last_id = $conn->insert_id;

                $sql = "INSERT INTO tb_saldo (id_usuario) VALUES ({$last_id});";
                $conn->query($sql);
            }else {
                echo "<script> alert(\"Error: ". $conn->error."\");</script>";
                return false;
            }
        } else {
            echo "<script> alert(\"Error: ". $conn->error."\");</script>";
            return false;
        }

        $db->close($conn);
        return true;
    } catch (\Throwable $th) {
        $db->close($conn);
        echo "<h1>erro ao salvar</h1>";
        return false;
    }
}