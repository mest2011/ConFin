<?php
include_once "../database/crud.php";
include_once "send_email.php";
if (!isset($_SESSION)) {
    session_start();
}
class ResetPsw
{
    protected $id_usuario;

    function validaToken($token)
    {
        $sql = "SELECT token, id_usuario FROM tb_alterar_senha WHERE token = '{$token}' AND status = 1 AND timeDIFF(NOW(), data_criacao) < '00:10:00'";

        $result = $this->read($sql)[0];

        if ($result['token'] === $token) {
            $id_usuario = $result['id_usuario'];
            $_SESSION['user_id'] = $result['id_usuario'];
            return true;
        } else {
            return false;
        }
    }

    function alterarSenha($senha)
    {
        $sql = "UPDATE tb_alterar_senha set status = 0 where id_usuario = {$_SESSION['user_id']}";

        $this->update($sql);

        $sql = "UPDATE tb_usuario set senha = '{$senha}' where id_usuario = {$_SESSION['user_id']}";
        session_destroy();

        return $this->update($sql);
    }

    function solicitaAlteracaoSenha($email)
    {
        $sql = "SELECT id_usuario ,email, usuario, nome FROM tb_pessoa AS tp INNER JOIN tb_usuario AS tu ON tu.id_pessoa = tp.id_pessoa WHERE email = '{$email}' limit 1";

        try {
            $result = $this->read($sql)[0];

            if ($result['email'] === $email) {
                $_token = generateRandomString(20);

                $diretorio = str_replace("\\", "/", $_SERVER['SCRIPT_URI']);

                $sql = "INSERT INTO tb_alterar_senha (token, id_usuario) VALUES ('{$_token}', {$result['id_usuario']});";

                if ($this->create($sql)) {
                    $email = new Email($email, $result['nome'], "Troca de senha", "
                <h1>Cofrin – Seu sistema de controle financeiro</h1>

                <p>Olá {$result['nome']},</p>
                <p>Você solicitou a troca da sua senha?</p>
                <p>Usuário: <b>{$result['usuario']}</b></p>
                <p>Se sim, clique nesse link abaixo e cadastre a nova senha:</p>
                <p><a href='{$diretorio}?token={$_token}' target='_blank'>Alterar senha</a></p>

                <p>Caso não tenha sido você, nos responda esse e-mail, para analisarmos por que recebeu essa solicitação de alteração de senha.</p>
                ");
                    return $email->send_email();
                } else {
                    return false;
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    function read($sql)
    {
        $data = array();

        $db = new Db();

        $conn = $db->connect();

        $result = $conn->query($sql);

        $lines = $result->num_rows;


        if ($lines > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
        } else {
            $data = "0 dados encontrados";
        }

        $db->close($conn);
        return $data;
    }

    function create($sql)
    {
        $db = new Db();

        $conn = $db->connect();

        $result = $conn->query($sql);

        if ($result === TRUE) {
            $resposta = true;
        } else {
            $resposta = false;
        }

        $db->close($conn);
        return $resposta;
    }

    function update($sql)
    {
        $db = new Db();

        $conn = $db->connect();

        $result = $conn->query($sql);

        if ($result === TRUE) {
            $resposta = true;
        } else {
            $resposta = false;
        }

        $db->close($conn);
        return $resposta;
    }
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
