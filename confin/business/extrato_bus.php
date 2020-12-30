<?php
include_once "../database/crud.php";
include_once "../classes/Extrato.php";

class ExtratoBus extends Crud
{

    public static function buscar($id_usuario, $dt_inicio = "0", $dt_fim = "0")
    {
        $array_extrato = array();
        $sql = "SELECT * FROM tb_ganho where id_usuario = {$id_usuario} order by data_do_credito desc;";
        $ganho = parent::read($sql);
        $sql = "SELECT * FROM tb_despesa where id_usuario = {$id_usuario} order by data_do_debito desc;";
        $gasto = parent::read($sql);

        if (gettype($ganho) === "array") {
            foreach ($ganho as $key => $value) {
                $obj_extrato = new Extrato();
                $obj_extrato->id_usuario = $value['id_usuario'];
                $obj_extrato->titulo = $value['titulo'];
                $obj_extrato->descricao = $value['descricao'];
                $obj_extrato->categoria = $value['tipo'];
                $obj_extrato->valor = number_format($value['valor'], 2, ',', '.');
                $obj_extrato->data = $value['data_do_credito'];
                array_push($array_extrato,  $obj_extrato);
            }
        }
        if (gettype($gasto) === "array") {
            foreach ($gasto as $key => $value) {
                $obj_extrato = new Extrato();
                $obj_extrato->id_usuario = $value['id_usuario'];
                $obj_extrato->titulo = $value['titulo'];
                $obj_extrato->descricao = $value['descricao'];
                $obj_extrato->categoria = $value['tipo'];
                $obj_extrato->valor = "-" . strval(number_format($value['valor'], 2, ',', '.'));
                $obj_extrato->data = $value['data_do_debito'];
                array_push($array_extrato,  $obj_extrato);
            }
        }
        
        return $array_extrato;
    }
}
