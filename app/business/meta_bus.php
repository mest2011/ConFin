<?php
include_once "../database/crud.php";
include_once "../classes/Meta.php";

class MetaBus extends Crud
{

    static public function salvar($obj_meta)
    {
        $vld = MetaBus::validaCampo($obj_meta);
        $obj_meta->valor = MetaBus::converteDouble($obj_meta->valor);
        if ($vld) return $vld;

        if ($obj_meta->id_meta <= 0) {
            $sql = "INSERT INTO tb_meta (id_carteira, id_usuario, titulo, descricao, valor, dt_limite) 
                    VALUES (
                        {$obj_meta->id_carteira}, 
                        {$obj_meta->id_usuario}, 
                        '{$obj_meta->titulo}', 
                        '{$obj_meta->descricao}', 
                        {$obj_meta->valor}, 
                        '{$obj_meta->dt_limite}'
                    );";
            if (parent::create($sql)) {
                return "Meta salva com sucesso!";
            } else {
                return "Erro ao salvar meta!";
            }
        } else {
            $sql = "UPDATE tb_meta SET 
                        id_carteira = {$obj_meta->id_carteira},  
                        titulo = '{$obj_meta->titulo}', 
                        descricao = '{$obj_meta->descricao}', 
                        valor = {$obj_meta->valor}, 
                        dt_limite = '{$obj_meta->dt_limite}'
                    WHERE id_meta = {$obj_meta->id_meta};
                ;";
            if (parent::update($sql)) {
                return "Meta atualizada com sucesso!";
            } else {
                return "Erro ao atualizadar meta!";
            }
        }
    }

    static public function listar($id_usuario)
    {
        $sql = "SELECT *, tm.descricao AS descricao_meta,
        DATE_FORMAT(dt_limite, '%d/%m/%Y') as dt_limite_ptbr,
        SUM(valor - saldo) AS falta, 
        CAST(((if(saldo >= 0,saldo,0) / valor)*100) as UNSIGNED)   AS porcentagem_alcancada
        FROM tb_meta AS tm
        INNER JOIN tb_carteira AS tc ON tc.id_carteira = tm.id_carteira
         WHERE tm.id_usuario = {$id_usuario} AND tm.status = 1;";
        return parent::read($sql)[0];
    }

    static public function deletar($id_meta)
    {
        $sql = "UPDATE tb_meta SET `status`=b'0' WHERE  `id_meta`={$id_meta};";

        return parent::update($sql);
    }

    static private function validaCampo($obj_meta)
    {
        if ($obj_meta->id_carteira <= 0) {
            return "Erro : Carteira inválida!";
        }
        if ($obj_meta->id_usuario <= 0) {
            return "Erro : Usuário inválido!";
        }
        if (strlen($obj_meta->titulo) > 50) {
            return "Erro : Titulo da meta muito extensa!";
        }
        if (strlen($obj_meta->descricao) > 200) {
            return "Erro : Descrição da meta muito extensa!";
        }
        if ($obj_meta->valor < 0) {
            return "Erro : Valor da meta muito baixo!";
        }
        if ($obj_meta->valor > 10000000) {
            return "Erro : Valor da meta muito alto!";
        }
    }

    static public function converteDouble($valor)
    {
        if (strpos($valor, ",") !== false) {
            $valor = str_replace('.', '', $valor);
            return $valor = str_replace(',', '.', $valor);
        }
        return $valor;
    }
}
