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
                $resposta = true;
            } else {
                $resposta = false;
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = false;
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
            $resposta = false;
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
                $resposta = true;
            } else {
                $resposta = false;
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = false;
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
                $resposta = true;
            } else {
                $resposta = false;
            }

            $db->close($conn);
            return $resposta;
        } catch (\Throwable $th) {

            $db->close($conn);
            $resposta = false;
            return $resposta;
        }
    }
}
