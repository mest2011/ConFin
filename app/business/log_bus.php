<?php
include_once "../database/crud.php";

class LogBus extends Crud
{
    static function createLog($id_usuario, $descricao, $ip)
    {
        if (strlen($descricao) < 4) return "Erro : Descrição de log muito curto!";
        if (strlen($descricao) > 200) return "Erro : Descrição de log muito longo!";

        $sql = "INSERT INTO tb_log (id_usuario, descricao, ip) VALUES ({$id_usuario}, '{$descricao}', '{$ip}');";

        if (parent::create($sql)) {
            return "Log salvo com sucesso!";
        } else {
            return "Erro ao salvar o log!";
        }
    }

    static function readLog($id_usuario, $limit, $filtro = null)
    {
        try {
            if($id_usuario <> 2 or $id_usuario <> 1){
                $id_usuario = " id_usuario = {$id_usuario} ";
            }else{
                $id_usuario = "";
            }
            if($filtro <> null) {
                $filtro = "WHERE descricao like '%{$filtro}%'";
            }
            $sql = "SELECT (SELECT nome FROM tb_pessoa WHERE tb_pessoa.id_pessoa = (SELECT id_pessoa FROM tb_usuario WHERE tb_usuario.id_usuario = tb_log.id_usuario LIMIT 1) LIMIT 1) as nome ,
            (SELECT usuario FROM tb_usuario WHERE tb_usuario.id_usuario = tb_log.id_usuario LIMIT 1) AS email,
             DATE_FORMAT(datahora, '%H:%i %d/%m/%Y') AS `Data do ultimo acesso`,
             DATEDIFF(datahora, CURRENT_TIMESTAMP()) AS tempo
             
            FROM tb_log   {$filtro} ORDER BY id_log DESC LIMIT {$limit};";
    
            $result = parent::read($sql);
    
            if(!$result){
                return "Erro ao buscar log!";
            }else{
                return $result;
            }
        } catch (\Throwable $th) {
            return "Erro ao buscar log!";
        }
        
    }

}
