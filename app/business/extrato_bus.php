<?php
include_once "../database/crud.php";
include_once "../classes/Extrato.php";

class ExtratoBus extends Crud
{

    public static function buscar($id_usuario, $parametros = [])
    {
        try {
            $filter = [ 0 => "", 1 => "", 2 => ""];

            if ($parametros['categoria']) {
                if ($parametros['categoria'] == "Entre carteiras") {
                    $filter = [
                        0 => "",
                        1 => " AND 1=0",
                        2 => " AND 1=0"
                    ];
                }else{
                    $filter = [
                        0 => " AND 1=0",
                        1 => " AND tipo='{$parametros['categoria']}'",
                        2 => " AND tipo='{$parametros['categoria']}'"
                    ];
                }
            }

            if ($parametros['carteira']) {
                if ($parametros['carteira'] == "Entre carteiras") {
                    $filter = [
                        0 => "",
                        1 => " AND 1=0",
                        2 => " AND 1=0"
                    ];
                }else{
                    $filter = [
                        0 => " AND 1=0",
                        1 => " AND id_carteira={$parametros['carteira']}",
                        2 => " AND id_carteira={$parametros['carteira']}"
                    ];
                }
            }

            if ($parametros['dt_ini'] == null and $parametros['dt_fim'] == null) {
                $parametros['dt_ini'] = "" . date('Y') . "-" . date('m') . "-01";
                $parametros['dt_fim'] = "" . date('Y') . "-" . date('m') . "-31";
            }
            $dateFilter = [
                0 => " and tt.data_criacao >= '{$parametros['dt_ini']}' and tt.data_criacao <= '{$parametros['dt_fim']}' ",
                1 => " and data_do_credito >= '{$parametros['dt_ini']}' and data_do_credito <= '{$parametros['dt_fim']}' ",
                2 => " and data_do_debito >= '{$parametros['dt_ini']}' and data_do_debito <= '{$parametros['dt_fim']}' "
            ];

            $sql = "SELECT 
            'Tranferência' as titulo, 
            'Entre carteiras' as tipo, 
            concat('Conta origem: ', c.nome_carteira , 
            '<br> Conta destino: ', d.nome_carteira) AS descricao, 
            format(valor,2,'de_DE') valor,  convert(tt.data_criacao, date) AS data, 
            DATE_FORMAT(tt.data_criacao, '%d/%m/%Y') as data_ptbr, 
            concat(c.nome_carteira,' => ',d.nome_carteira) AS nome_carteira, 
            '📬' as icone 
            FROM tb_transferencia AS tt
            JOIN tb_carteira c ON c.id_carteira = tt.id_carteira_origem
            JOIN tb_carteira d ON d.id_carteira = tt.id_carteira_destino
            WHERE tt.id_usuario = {$id_usuario}
            {$dateFilter[0]} {$filter[0]}
            UNION
            
            SELECT 
                titulo, 
                tipo, 
                descricao,
                format(valor,2,'de_DE') valor, 
                tb_ganho.data_do_credito AS data, 
                DATE_FORMAT(data_do_credito, '%d/%m/%Y') as data_ptbr, 
                (select nome_carteira from tb_carteira where id_carteira = tb_ganho.id_carteira limit 1) AS nome_carteira, 
                icone 
            FROM tb_ganho 
            WHERE id_usuario = {$id_usuario} AND status = 1
            {$dateFilter[1]} {$filter[1]}
            UNION
            
            SELECT 
                titulo, 
                tipo, 
                descricao, 
                format((valor * (-1)),2,'de_DE') AS valor, 
                tb_despesa.data_do_debito AS data, 
                DATE_FORMAT(data_do_debito, '%d/%m/%Y') as data_ptbr, 
                (select nome_carteira from tb_carteira where id_carteira = tb_despesa.id_carteira limit 1) AS   nome_carteira, icone 
            FROM tb_despesa 
            WHERE id_usuario = {$id_usuario} AND status = 1 
            {$dateFilter[2]} {$filter[2]}
            ORDER BY data DESC;";

            $result = parent::read($sql);

            if ($result == "0 dados encontrados") {
                return false;
            } else {
                return $result;
            }

            return;
        } catch (\Throwable $th) {
            return "Erro ao buscar dados, tente mais tarde por favor!";
        }
    }
}
