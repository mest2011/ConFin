<?php
include_once 'connection.php';
class Crud
{

    static function create($sql)
    {
        try {
            $db = new Db();

            $conn = $db->connect();

            $result = $conn->query($sql);

            if ($result === TRUE) {
                $resposta = "Dado(s) salvo(s) com sucesso!";
            } else {
                $resposta = "Erro ao salvar o(s) dado(s)";
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = "Erro ao salvar o(s) dado(s)";
            return $resposta;
        }
    }

    static function read($sql)
    {
        try {
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
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = "Erro ao buscar o(s) dado(s)";
            return $resposta;
        }
    }

    static function update($sql)
    {
        try {
            $db = new Db();

            $conn = $db->connect();

            $result = $conn->query($sql);

            if ($result === TRUE) {
                $resposta = "Dado(s) atualizado(s) com sucesso!";
            } else {
                $resposta = "Erro ao atualizar o(s) dado(s)";
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = "Erro ao salvar o(s) dado(s)";
            return $resposta;
        }
    }

    static function delete($sql)
    {
        try {
            $db = new Db();

            $conn = $db->connect();

            $result = $conn->query($sql);

            if ($result === TRUE) {
                $resposta = "Dado(s) excluido(s) com sucesso!";
            } else {
                $resposta = "Erro ao excluir o(s) dado(s)";
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = "Erro ao salvar o(s) dado(s)";
            return $resposta;
        }
    }
}
