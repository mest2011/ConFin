<?php
include_once "../database/crud.php";
include_once "../classes/Usuario.php";

class UsuarioBus extends Crud
{

    public static function createUsuario($id_usuario, $obj_user)
    {
        if (isset($id_usuario, $obj_user->id_usuario)) {
            if ($obj_user->id_usuario == $id_usuario) {
                $sql = "UPDATE tb_pessoa SET ";

                if (isset($obj_user->nome)) $sql .= " nome = '" . $obj_user->nome . "',";
                if (isset($obj_user->cpf)) $sql .= " cpf = '" . $obj_user->cpf . "',";
                if (isset($obj_user->email)) $sql .= " email = '" . $obj_user->email . "',";
                if (isset($obj_user->endereco)) $sql .= " endereco = '" . $obj_user->endereco . "',";
                if (isset($obj_user->cidade)) $sql .= " cidade = '" . $obj_user->cidade . "',";
                if (isset($obj_user->pais)) $sql .= " pais = '" . $obj_user->pais . "',";
                if (isset($obj_user->foto)) $sql .= " foto = '" . $obj_user->foto . "',";
                if (isset($obj_user->datanascimento)) $sql .= " data_nascimento = '" . $obj_user->datanascimento  . "',";
                // if (isset($obj_user->usuario)) $sql .= " usuario = '" . $obj_user->usuario . "',";
                // if (isset($obj_user->senha)) $sql .= " senha = '" . $obj_user->senha . "',";

                $sql = substr($sql, 0, -1);

                $sql .= " WHERE id_pessoa = {$id_usuario};";

                if (parent::update($sql)) {
                    return "Usuario atualizado com sucesso!";
                } else {
                    return "Erro ao atualizar o usuario!";
                }
            } else {
                return "Erro id da sessao eh diferente do id informado!";
            }
        } else {
            return "Criação de usuario não implementada!";
            $sql = "INSERT INTO tb_pessoa";
        }
    }

    public static function readUsuario($id_usuario)
    {
        $sql = "SELECT * FROM tb_pessoa AS p INNER JOIN tb_usuario AS u 
        ON p.id_pessoa = u.id_pessoa 
        WHERE u.id_usuario = {$id_usuario} LIMIT 1";
        return parent::read($sql);
    }


    public static function deleteUsuario($id_usuario)
    {
        $sql = "DELETE tb_pessoa WHERE id_pessoa = {$id_usuario};";

        if (parent::delete($sql)) {
            return "Usuario deletado!";
        } else {
            return "Erro : Erro ao deletadar o usuario!";
        }
    }
}
