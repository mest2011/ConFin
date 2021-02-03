<?php
include_once "../database/crud.php";

class CadastroUsuario extends Crud
{
    static function salvarUsuario($pessoa)
    {
        try {
            $db = new Db();
            $conn = $db->connect();
            $sql = "INSERT INTO tb_pessoa (nome, email, data_nascimento) VALUES 
        ('" . $pessoa->nome . "','" . $pessoa->email . "', '" . $pessoa->datanascimento . "');
        ";
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;

                $sql = "INSERT INTO tb_usuario (usuario, senha, id_pessoa) VALUES ('" . $pessoa->usuario . "', '" . $pessoa->senha . "', 
            $last_id);";

                if ($conn->query($sql) === true) {
                    $last_id = $conn->insert_id;

                    $sql = "INSERT INTO tb_carteira (id_usuario) VALUES ({$last_id});";
                    $conn->query($sql);
                } else {
                    //echo "<script>alert(\"Error: " . $conn->error . "\")</script>";
                }
            } else {
                //echo "<script> alert(\"Error: " . $conn->error . "\");</script>";
                return false;
            }

            $db->close($conn);
            return true;
        } catch (\Throwable $th) {
            $db->close($conn);
            return false;
        }
    }
}
