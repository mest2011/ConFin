<?php
include_once "../database/crud.php";
include_once "../classes/Extrato.php";

class ExtratoBus extends Crud
{

    public static function buscar($id_usuario, $dt_inicio = "0", $dt_fim = "0")
    {

        $sql = "SELECT 'Tranferência' as titulo, 'Entre carteiras' as tipo, concat('Conta origem: ', c.nome_carteira , '<br> Conta destino: ', d.nome_carteira) AS descricao, format(valor,2,'de_DE') valor,  convert(tt.data_criacao, date) AS data, DATE_FORMAT(tt.data_criacao, '%d/%m/%Y') as data_ptbr, concat(c.nome_carteira,' => ',d.nome_carteira) AS nome_carteira, '📬' as icone FROM tb_transferencia AS tt
                JOIN tb_carteira c ON c.id_carteira = tt.id_carteira_origem
                JOIN tb_carteira d ON d.id_carteira = tt.id_carteira_destino
                 WHERE tt.id_usuario = {$id_usuario}
                UNION
                SELECT titulo, tipo, descricao,format(valor,2,'de_DE') valor, tb_ganho.data_do_credito AS data, DATE_FORMAT(data_do_credito, '%d/%m/%Y') as data_ptbr, (select nome_carteira from tb_carteira where id_carteira = tb_ganho.id_carteira limit 1) AS nome_carteira, icone FROM tb_ganho WHERE id_usuario = {$id_usuario} AND status = 1
                UNION
                SELECT titulo, tipo, descricao, format((valor * (-1)),2,'de_DE') AS valor, tb_despesa.data_do_debito AS data, DATE_FORMAT(data_do_debito, '%d/%m/%Y') as data_ptbr, (select nome_carteira from tb_carteira where id_carteira = tb_despesa.id_carteira limit 1) AS nome_carteira, icone FROM tb_despesa WHERE id_usuario = {$id_usuario} AND status = 1 ORDER BY data DESC;";
        
        return parent::read($sql);
    }
}
