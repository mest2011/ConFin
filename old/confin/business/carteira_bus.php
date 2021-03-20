<?php

include_once "../database/crud.php";

class CarteiraBus extends Crud
{
    static function carteiras($id_usuario)
    {
        $sql = "SELECT * FROM tb_carteira WHERE id_usuario = {$id_usuario} AND status = 1";
        return parent::read($sql);
    }

    public static function createCarteira($obj_carteira)
    {
        $obj_carteira->saldo = str_replace('.', '', $obj_carteira->saldo);
        $obj_carteira->saldo = str_replace(',', '.', $obj_carteira->saldo);
        $sql = "INSERT INTO tb_carteira (id_carteira, id_usuario, nome_carteira)
                VALUES ({$obj_carteira->id_carteira}, {$obj_carteira->id_usuario}, '{$obj_carteira->nome_carteira}');";
        echo "<script>alert('$sql')</script>";

        return parent::create($sql);
    }

    public static function readCarteira($id_usuario, $id_carteira = null)
    {

        if ($id_carteira === null) {
            $sql = "SELECT * FROM tb_carteira WHERE id_usuario = {$id_usuario} AND status = 1 ORDER BY nome_carteira ASC;";
            return parent::read($sql);
        } else {
            $sql = "SELECT * FROM tb_carteira WHERE id_usuario = {$id_usuario}  and status = 1 and id_carteira = {$id_carteira} LIMIT 1";
            $obj_carteira = self::converteCarteira(parent::read($sql)[0]);
            return $obj_carteira;
        }
    }

    public static function updateCarteira($obj_carteira)
    {
        $obj_carteira->saldo = str_replace('.', '', $obj_carteira->saldo);
        $obj_carteira->saldo = str_replace(',', '.', $obj_carteira->saldo);


        $sql = "UPDATE tb_carteira SET
                nome_carteira = '{$obj_carteira->nome_carteira}'
                WHERE id_carteira = {$obj_carteira->id_carteira}";

        return parent::update($sql);
    }

    public static function deleteCarteira($id_carteira)
    {
        $sql = "UPDATE tb_carteira SET status = 0 WHERE id_carteira = {$id_carteira};";
        return parent::delete($sql);
    }

    public static function transferirValorEntreCarteiras($id_usuario, $id_carteira_origem, $id_carteira_destino, $valor)
    {
        if ($id_carteira_origem === $id_carteira_destino) return "Carteiras iguais! Para fazer transferências, selecione carteiras diferentes!";
        
        if ($valor == 0 || (double)$valor < 0) return "Valor inválido!";

        try {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
            $sql = "INSERT INTO `tb_transferencia` (`id_usuario`, `id_carteira_origem`, `id_carteira_destino`, `valor`) VALUES ({$id_usuario}, {$id_carteira_origem}, {$id_carteira_destino}, {$valor});";
            parent::create($sql);

            $sql = "UPDATE tb_carteira SET saldo = (saldo - {$valor}) WHERE id_carteira = {$id_carteira_origem};";
            parent::update($sql);

            $sql = "UPDATE tb_carteira SET saldo = (saldo + {$valor}) WHERE id_carteira = {$id_carteira_destino};";
            parent::update($sql);

            return "Transferência concluida com sucesso!";
        } catch (\Throwable $th) {
            return "Erro ao fazer a rotina de transferencia!";
        }
    }

    private static function converteCarteira($array)
    {
        if (isset($array[1])) {
            return "not implemented";
            foreach ($array as $key => $value) {
            }
        } else {
            $obj_gasto = new Carteira();

            $obj_gasto->id_carteira = $array['id_carteira'];
            $obj_gasto->id_usuario = $array['id_usuario'];
            $obj_gasto->nome_carteira = $array['nome_carteira'];
            $obj_gasto->saldo = number_format($array['saldo'], 2, ',', '.');

            return $obj_gasto;
        }
    }
}
