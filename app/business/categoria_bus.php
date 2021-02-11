<?php
include_once "../database/crud.php";

class CategoriaBus extends Crud
{
    static function createCategoria($id_usuario, $tipo, $nome)
    {
        if (strlen($nome) < 4) return "Nome de categoria muito curto!";
        if (strlen($nome) > 30) return "Nome de categoria muito longo!";
        if ((double)$tipo > 1 || (double)$tipo < 0) return "Erro! Tipo de categoria inválida!";

        $sql = "INSERT INTO tb_categoria (id_usuario, nome_categoria, tipo) VALUES ({$id_usuario}, '{$nome}', {$tipo});";

        if (parent::create($sql)) {
            return "Categoria salva com sucesso!";
        } else {
            return "Erro ao salvar a categoria!";
        }
    }

    static function readCategoria($id_usuario, $tipo)
    {
        if ((double)$tipo > 1 || (double)$tipo < 0) return "Erro! Tipo de categoria inválida!";

        $sql = "SELECT * FROM tb_categoria WHERE id_usuario = {$id_usuario} and tipo = {$tipo} ORDER BY nome_categoria asc;";

        $result = parent::read($sql);

        if(!$result){
            return "Erro ao buscar categorias";
        }else{
            return $result;
        }
    }

    static function deleteCategoria($id_usuario, $id_categoria)
    {
        if ((double)$id_categoria < 0) return "Erro! Categoria inválida!";
        if (!isset($id_usuario)) return "Erro! Usuario inválido!";

        $sql = "DELETE FROM tb_categoria WHERE id_categoria = {$id_categoria} AND id_usuario = {$id_usuario};";

        if (parent::delete($sql)) {
            return "Categoria deletada com sucesso!";
        } else {
            return "Erro ao deletar a categoria!";
        }
    }
}
