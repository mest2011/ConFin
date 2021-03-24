<?php

include_once "../database/crud.php";

class CarteiraBus extends Crud
{

    public static function createCarteira($obj_carteira)
    {
        $sql = "INSERT INTO tb_carteira ( id_usuario, nome_carteira, cor, descricao, poupanca)
                VALUES ( {$obj_carteira->id_usuario}, '{$obj_carteira->nome_carteira}', '{$obj_carteira->cor}', '{$obj_carteira->descricao}', {$obj_carteira->poupanca});";
        //echo "<h1>alert('$sql')</h1>";

        if (parent::create($sql)) {
            return "Carteira salva com sucesso!";
        } else {
            return "Erro ao salvar a carteira!";
        }
    }

    public static function readCarteira($id_usuario, $id_carteira = null)
    {
        if ($id_carteira === null) {
            $sql = "SELECT id_carteira, nome_carteira, format(saldo, 2, 'de_DE') as saldo, cor, descricao, poupanca FROM tb_carteira WHERE id_usuario = {$id_usuario} AND status = 1 ORDER BY nome_carteira ASC;";
            $result = parent::read($sql);
        } else {
            $sql = "SELECT  id_carteira, nome_carteira, format(saldo, 2, 'de_DE') as saldo, cor, descricao , poupanca FROM tb_carteira WHERE id_usuario = {$id_usuario}  and status = 1 and id_carteira = {$id_carteira} LIMIT 1";
            $obj_carteira = self::converteCarteira(parent::read($sql)[0]);
            $result = $obj_carteira;
        }

        if (!$result) {
            return "Erro ao buscar a(s) carteira(s)!";
        } else {
            return $result;
        }
    }

    public static function readCarteiraPoupanca($id_usuario)
    {
        $sql = "SELECT id_carteira, nome_carteira, format(saldo, 2, 'de_DE') as saldo, cor, descricao, poupanca FROM tb_carteira WHERE id_usuario = {$id_usuario} AND status = 1 AND poupanca = 1 ORDER BY nome_carteira ASC;";
        $result = parent::read($sql);


        if (!$result) {
            return "Erro ao buscar a(s) carteira(s)!";
        } else {
            return $result;
        }
    }

    public static function updateCarteira($obj_carteira)
    {

        $sql = "UPDATE tb_carteira SET
                nome_carteira = '{$obj_carteira->nome_carteira}',
                cor = '{$obj_carteira->cor}',
                descricao = '{$obj_carteira->descricao}',
                poupanca = {$obj_carteira->poupanca}
                WHERE id_carteira = {$obj_carteira->id_carteira}";
        return $sql;

        if (parent::update($sql)) {
            return "Carteira atualizada com sucesso!";
        } else {
            return "Erro ao atualizar a carteira!";
        }
    }

    public static function deleteCarteira($id_carteira)
    {
        $sql = "UPDATE tb_carteira SET status = 0 WHERE id_carteira = {$id_carteira};";

        if (parent::delete($sql)) {
            return "Carteira deletada com sucesso!";
        } else {
            return "Erro ao deletar a carteira!";
        }
    }

    public static function transferirValorEntreCarteiras($id_usuario, $id_carteira_origem, $id_carteira_destino, $valor)
    {
        if ($id_carteira_origem === $id_carteira_destino) return "Carteiras iguais! Para fazer transferências, selecione carteiras diferentes!";

        if ($valor == 0 || (float)$valor < 0) return "Valor inválido!";

        try {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
            $sql = "INSERT INTO `tb_transferencia` (`id_usuario`, `id_carteira_origem`, `id_carteira_destino`, `valor`) VALUES ({$id_usuario}, {$id_carteira_origem}, {$id_carteira_destino}, {$valor});";
            parent::create($sql);

            $sql = "UPDATE tb_carteira SET saldo = (saldo - {$valor}) WHERE id_carteira = {$id_carteira_origem} AND status = 1;";
            parent::update($sql);

            $sql = "UPDATE tb_carteira SET saldo = (saldo + {$valor}) WHERE id_carteira = {$id_carteira_destino} AND status = 1;";
            parent::update($sql);

            return "Transferência concluida com sucesso!";
        } catch (\Throwable $th) {
            return "Erro ao fazer a rotina de transferencia!";
        }
    }

    private static function converteCarteira($array)
    {
        try {
            if (isset($array[1])) {
                return "not implemented";
                foreach ($array as $key => $value) {
                }
            } else {
                if (!isset($array['id_carteira'])) {
                    return "Carteira nao existe!";
                }
                $obj_gasto = new Carteira();

                $obj_gasto->id_carteira = $array['id_carteira'];
                $obj_gasto->id_usuario = $array['id_usuario'];
                $obj_gasto->nome_carteira = $array['nome_carteira'];
                $obj_gasto->saldo = number_format($array['saldo'], 2, ',', '.');

                return $obj_gasto;
            }
        } catch (\Throwable $th) {
            return "Carteira nao existe!";
        }
    }
}
