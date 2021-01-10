<?php
include_once "../database/crud.php";
include_once "../classes/Extrato.php";

class ExtratoBus extends Crud
{

    public static function buscar($id_usuario, $dt_inicio = "0", $dt_fim = "0")
    {
        $sql = "SELECT titulo, tipo, descricao, valor, tb_ganho.data_do_credito AS data, (select nome_carteira from tb_carteiras where id_carteira = tb_ganho.id_carteira limit 1) AS nome_carteira FROM tb_ganho WHERE id_usuario = {$id_usuario} AND status = 1
        UNION
        SELECT titulo, tipo, descricao, (valor * (-1)) AS valor, tb_despesa.data_do_debito AS data, (select nome_carteira from tb_carteiras where id_carteira = tb_despesa.id_carteira limit 1) AS nome_carteira FROM tb_despesa WHERE id_usuario = {$id_usuario} AND status = 1 ORDER BY data DESC";
        
        return parent::read($sql);
    }
}
