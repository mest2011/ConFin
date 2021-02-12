<?php
include_once "../database/crud.php";
include_once "../classes/Ganho.php";

class GanhoBus extends Crud
{

    public static function createGanho($id_usuario, $obj_ganho)
    {
        $obj_ganho->valor = str_replace('.', '', $obj_ganho->valor);
        $obj_ganho->valor = str_replace(',', '.', $obj_ganho->valor);
        $sql = "INSERT INTO tb_ganho (titulo, descricao, tipo, valor, data_do_credito, id_usuario, id_carteira, icone)
                VALUES ('{$obj_ganho->titulo}', '{$obj_ganho->descricao}', '{$obj_ganho->categoria}', {$obj_ganho->valor}, '{$obj_ganho->data_do_credito}', $id_usuario, {$obj_ganho->id_carteira}, '&\#x{$obj_ganho->icone}');";

        self::atualizaSaldo($obj_ganho->valor, $obj_ganho->id_carteira);

        if (parent::create($sql)) {
            return "Ganho salvo com sucesso!";
        } else {
            return $sql; //"Erro : Erro ao salvar o ganho!";
        }
    }

    public static function readGanho($id_usuario, $id_ganho = null)
    {

        if ($id_ganho === null) {
            $sql = "SELECT id_ganho, titulo, id_carteira, tipo, descricao, format(valor,2,'de_DE') as valor, DATE_FORMAT(data_do_credito, '%d/%m/%Y') as data_do_credito_ptbr, data_do_credito, icone, (select nome_carteira from tb_carteira where id_carteira = tb_ganho.id_carteira limit 1) AS nome_carteira FROM tb_ganho WHERE id_usuario = {$id_usuario} AND data_do_credito >= '" . date('Y') . "-" . date('m') . "-01'
            AND data_do_credito <= '" . date('Y') . "-" . date('m') . "-31' and status = 1 order by data_do_credito DESC;";
            return parent::read($sql);
        } else {
            $sql = "SELECT id_ganho, titulo, id_carteira, tipo, descricao, format(valor,2,'de_DE') as valor, DATE_FORMAT(data_do_credito, '%d/%m/%Y') as data_do_credito_ptbr, data_do_credito, icone,(select nome_carteira from tb_carteira where id_carteira = tb_ganho.id_carteira limit 1) AS nome_carteira FROM tb_ganho WHERE id_usuario = {$id_usuario}  and status = 1 and id_ganho = {$id_ganho} LIMIT 1";
            $obj_ganho = self::converteGanho(parent::read($sql)[0]);
            return $obj_ganho;
        }
    }

    public static function updateGanho($obj_ganho)
    {
        try {
            $obj_ganho->valor = str_replace('.', '', $obj_ganho->valor);
            $obj_ganho->valor = str_replace(',', '.', $obj_ganho->valor);

            //Retira valor antigo do saldo
            $valor_antigo = parent::read("SELECT valor FROM tb_ganho WHERE id_ganho = {$obj_ganho->id_ganho} limit 1;")[0]['valor'];

            self::atualizaSaldo(($valor_antigo / -1), $obj_ganho->id_carteira);

            //Insere valor novo ao saldo
            self::atualizaSaldo($obj_ganho->valor, $obj_ganho->id_carteira);

            $sql = "UPDATE tb_ganho SET
                titulo = '{$obj_ganho->titulo}',
                descricao = '{$obj_ganho->descricao}',
                tipo = '{$obj_ganho->categoria}',
                valor = {$obj_ganho->valor},
                data_do_credito = '{$obj_ganho->data_do_credito}',
                icone = '&\#x{$obj_ganho->icone}' 
                WHERE id_ganho = {$obj_ganho->id_ganho}";

            if (parent::update($sql)) {
                return "Dados atualizados!";
            } else {
                return "Erro : Erro ao atualizar o ganho!";
            }
        } catch (\Throwable $th) {
            return "Erro : Erro ao atualizar o ganho (sql)!";
        }
    }

    public static function deleteGanho($id_ganho)
    {

        $id_usuario = parent::read("SELECT id_usuario FROM tb_ganho where id_ganho = {$id_ganho} limit 1;")[0]['id_usuario'];
        $obj_ganho = parent::read("SELECT valor, id_carteira from tb_ganho where id_ganho = {$id_ganho} limit 1;")[0];
        self::atualizaSaldo(($obj_ganho['valor'] / -1), $obj_ganho['id_carteira']);

        $sql = "UPDATE tb_ganho SET status = 0 WHERE id_ganho = {$id_ganho};";

        if (parent::delete($sql)) {
            return "Gasto deletado!";
        } else {
            return "Erro : Erro ao deletadar o gasto!";
        }
    }



    private static function atualizaSaldo($valor, $id_carteira)
    {

        $saldo_atual = parent::read("SELECT saldo FROM tb_carteira WHERE id_carteira = {$id_carteira} LIMIT 1;")[0]['saldo'];
        $saldo_atualizado = $saldo_atual + $valor;
        $sql = "UPDATE tb_carteira SET saldo = {$saldo_atualizado} WHERE id_carteira = {$id_carteira};";
        return parent::update($sql);
    }



    private static function converteGanho($array)
    {
        if (isset($array[1])) {
            return "not implemented";
            foreach ($array as $key => $value) {
            }
        } else {
            $obj_gasto = new Ganho();

            $obj_gasto->titulo = $array['titulo'];
            $obj_gasto->descricao = $array['descricao'];
            $obj_gasto->categoria = $array['tipo'];
            $obj_gasto->data_do_credito = $array['data_do_credito'];
            $obj_gasto->valor = number_format($array['valor'], 2, ',', '.');
            $obj_gasto->id_ganho = $array['id_ganho'];
            $obj_gasto->id_usuario = $array['id_usuario'];
            $obj_gasto->id_carteira = $array['id_carteira'];

            return $obj_gasto;
        }
    }
}
