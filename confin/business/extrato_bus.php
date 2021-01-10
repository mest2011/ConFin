<?php
include_once "../database/crud.php";
include_once "../classes/Extrato.php";

class ExtratoBus extends Crud
{

    public static function buscar($id_usuario, $dt_inicio = "0", $dt_fim = "0")
    {
        $sql = "SELECT titulo, tipo, descricao, valor, tb_ganho.data_do_credito AS data FROM tb_ganho WHERE id_usuario = {$id_usuario} 
        UNION
        SELECT titulo, tipo, descricao, valor, tb_despesa.data_do_debito AS data FROM tb_despesa WHERE id_usuario = {$id_usuario}  ORDER BY data DESC";
        
        return parent::read($sql);
    }
}
