<?php

include_once "../database/crud.php";
include_once "../classes/Gasto.php";

class GastoBus extends Crud
{

    static function salvarNovoGasto($id_usuario, $obj_gasto)
    {
        $obj_gasto->valor = str_replace('.', '', $obj_gasto->valor);
        $obj_gasto->valor = str_replace(',', '.', $obj_gasto->valor);
        // if ($obj_gasto->qtd_recorrente > 1) {
        //     $sql = "INSERT INTO tb_despesa (titulo, descricao, tipo, data_do_debito, valor, id_usuario, qtd_recorrente, contador_recorrente)
        //     VALUES ('{$obj_gasto->titulo}', '{$obj_gasto->descricao}', '{$obj_gasto->categoria}', '{$obj_gasto->data}', 
        //     {$obj_gasto->valor}, {$obj_gasto->id_usuario}, {$obj_gasto->qtd_recorrente}, 1)";

        //     parent::create($sql);

        //     $id_recorrente = parent::read("SELECT id_despesa FROM tb_despesa WHERE id_usuario = {$obj_gasto->id_usuario} AND qtd_recorrente = {$obj_gasto->qtd_recorrente} AND titulo = '{$obj_gasto->titulo}' and valor = {$obj_gasto->valor} ORDER BY id_despesa desc limit 1")[0]['id_despesa'];
        //     parent::update("UPDATE tb_despesa SET id_recorrente = {$id_recorrente} WHERE id_despesa = {$id_recorrente}");

        //     for ($contador=2; $contador <= $obj_gasto->qtd_recorrente; $contador++) { 
        //         $sql = "INSERT INTO tb_despesa (titulo, descricao, tipo, data_do_debito, valor, id_usuario, qtd_recorrente, contador_recorrente, id_recorrente)
        //         VALUES ('{$obj_gasto->titulo}', '{$obj_gasto->descricao}', '{$obj_gasto->categoria}', DATE_ADD('{$obj_gasto->data}', INTERVAL ".($contador-1)." MONTH), 
        //         {$obj_gasto->valor}, {$obj_gasto->id_usuario}, {$obj_gasto->qtd_recorrente}, {$contador}, {$id_recorrente})";

        //         parent::create($sql);
        //     }

        //     self::atualizaSaldo(($obj_gasto->valor * $obj_gasto->qtd_recorrente), $obj_gasto->id_carteira);

        // } else {
        $sql = "INSERT INTO tb_despesa (titulo, descricao, tipo, data_do_debito, valor, id_usuario, id_carteira, icone, comprovante)
                    VALUES ('{$obj_gasto->titulo}', '{$obj_gasto->descricao}', '{$obj_gasto->categoria}', '{$obj_gasto->data}', {$obj_gasto->valor}, $id_usuario, $obj_gasto->id_carteira, '&\#x{$obj_gasto->icone}', '{$obj_gasto->comprovante}');";

        self::atualizaSaldo(($obj_gasto->valor), $obj_gasto->id_carteira);

        if (parent::create($sql)) {
            return "Gasto salvo com sucesso!";
        } else {
            return "Erro : Erro ao salvar o gasto!";
        }
        //}
    }

    static function atualizaGasto($obj_gasto)
    {
        $obj_gasto->valor = str_replace('.', '', $obj_gasto->valor);
        $obj_gasto->valor = str_replace(',', '.', $obj_gasto->valor);

        //Retira o valor antigo do usuario
        $valor_antigo = parent::read("SELECT valor FROM tb_despesa WHERE id_despesa = {$obj_gasto->id_despesa} limit 1;")[0]['valor'];
        self::atualizaSaldo(($valor_antigo / -1), $obj_gasto->id_carteira);


        //Atualiza saldo com o valor novo da despesa
        self::atualizaSaldo(($obj_gasto->valor), $obj_gasto->id_carteira);

        $sql = "UPDATE tb_despesa SET 
                 titulo = '{$obj_gasto->titulo}',
                 descricao = '{$obj_gasto->descricao}',
                 tipo = '{$obj_gasto->categoria}',
                 data_do_debito = '{$obj_gasto->data}',
                 valor = {$obj_gasto->valor}, 
                 icone = '&\#x{$obj_gasto->icone}' ";
        if ($obj_gasto->comprovante <> null) {
            $sql .= ",
                    comprovante = '{$obj_gasto->comprovante}' ";
        }
        $sql .= "  
                 WHERE id_despesa = {$obj_gasto->id_despesa};";

        if (parent::update($sql)) {
            return "Dados atualizados!";
        } else {
            return "Erro : Erro ao atualizar o gasto!";
        }
    }

    static function excluirGasto($id)
    {
        $sql = "UPDATE tb_despesa SET status = 0 WHERE id_despesa = $id;";
        $obj_carteira = parent::read("SELECT valor, id_carteira from tb_despesa where id_despesa = {$id} limit 1;")[0];
        self::atualizaSaldo(($obj_carteira['valor'] / -1), $obj_carteira['id_carteira']);

        if (parent::delete($sql)) {
            return "Gasto deletado!";
        } else {
            return "Erro : Erro ao deletadar o gasto!";
        }
    }

    static function buscarListaGastosFuturos($id_usuario)
    {
        $sql = "SELECT * FROM tb_despesa WHERE id_usuario = " . $id_usuario . "
        AND data_do_debito > '" . date('Y') . "-" . date('m') . "-" . date('d') . "' AND status = 1 order by data_do_debito asc;";

        return parent::read($sql);
    }

    static function buscaGasto($id, $id_usuario)
    {
        $sql = "SELECT comprovante, id_despesa, titulo, id_carteira, tipo, descricao, format(valor,2,'de_DE') as valor, DATE_FORMAT(data_do_debito, '%d/%m/%Y') as as data_do_debito_ptbr, data_do_debito, (select nome_carteira from tb_carteira where id_carteira = tb_despesa.id_carteira limit 1) AS nome_carteira, icone FROM tb_despesa WHERE id_usuario = " . $id_usuario . "
        AND id_despesa = {$id} AND status = 1 LIMIT 1;";


        $result = parent::read($sql)[0];


        $obj_gasto  =  self::converteGasto($result);
        return $obj_gasto;
    }

    static function buscarListaGastosMes($id_usuario, $dt_ini = null, $dt_fim = null)
    {
        
        $sql = "SELECT comprovante, id_despesa, id_carteira, titulo, tipo, descricao, format(valor,2,'de_DE') as valor, DATE_FORMAT(data_do_debito, '%d/%m/%Y') as data_do_debito_ptbr, data_do_debito, (select nome_carteira from tb_carteira where id_carteira = tb_despesa.id_carteira limit 1) AS nome_carteira, icone FROM tb_despesa WHERE id_usuario = {$id_usuario} ";

        if ($dt_ini <> null and $dt_fim <> null) {
            $sql .= " AND data_do_debito >= '{$dt_ini}' AND data_do_debito <= '{$dt_fim}' ";
        } else {
            $sql .= " AND data_do_debito >= '" . date('Y') . "-" . date('m') . "-01'
            AND data_do_debito <= '" . date('Y') . "-" . date('m') . "-31'";
        }

        $sql .= " AND status = 1 ORDER BY data_do_debito desc;";

        $result = parent::read($sql);
        if ($result == "0 dados encontrados") {
            return false;
        } else {
            return $result;
        }
    }

    static function buscarTotalGastos($id_usuario)
    {
        $sql = "SELECT SUM(valor) as total_gastos FROM tb_despesa WHERE id_usuario = " . $id_usuario . "
        AND data_do_debito >= '" . date('Y') . "-" . date('m') . "-01'
        AND data_do_debito <= '" . date('Y') . "-" . date('m') . "-31' AND status = 1;";
        $result = parent::read($sql);
        if ($result[0]['total_gastos'] > 0) {
            return $result[0]['total_gastos'];
        } else {
            return "00.00";
        }
    }


    private static function atualizaSaldo($valor, $id_carteira)
    {
        $saldo_atual = parent::read("SELECT saldo FROM tb_carteira WHERE id_carteira = {$id_carteira} LIMIT 1;")[0]['saldo'];
        $saldo_atualizado = $saldo_atual - $valor;
        $sql = "UPDATE tb_carteira SET saldo = {$saldo_atualizado} WHERE id_carteira = {$id_carteira};";
        return parent::update($sql);
    }

    private static function converteGasto($array)
    {
        if (isset($array[1])) {
            return "not implemented";
            foreach ($array as $key => $value) {
            }
        } else {
            $obj_gasto = new Gasto();

            $obj_gasto->titulo = $array['titulo'];
            $obj_gasto->descricao = $array['descricao'];
            $obj_gasto->categoria = $array['tipo'];
            $obj_gasto->data = $array['data_do_debito'];
            $obj_gasto->valor = number_format($array['valor'], 2, ',', '.');
            $obj_gasto->id_despesa = $array['id_despesa'];
            $obj_gasto->id_usuario = $array['id_usuario'];
            $obj_gasto->id_carteira = $array['id_carteira'];

            return $obj_gasto;
        }
    }
}
