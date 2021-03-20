<?php
include_once "../database/crud.php";
include_once "../classes/Feedback.php";

class FeedbackBus extends Crud{
    
    public static function createFeedback($obj_feedback){
        if ($obj_feedback->anonimo === 0) {
            $sql = "INSERT INTO tb_feedback (id_usuario, titulo, descricao)
                    VALUES (
                        {$obj_feedback->id_usuario},
                        '{$obj_feedback->titulo}',
                        '{$obj_feedback->descricao}'
                    );";
        }else{
            $sql = "INSERT INTO tb_feedback (id_usuario, titulo, descricao, anonimo)
                    VALUES (
                        {$obj_feedback->id_usuario},
                        '{$obj_feedback->titulo}',
                        '{$obj_feedback->descricao}',
                        {$obj_feedback->anonimo}
                    );";
        }
        
        if (parent::create($sql)) {
            return "Seu comentario foi salvo com sucesso! Obrigado pelo feedback! ;)";
        } else {
            return "Erro : Tivemos um problema ao salvar seu comentario!";
        }
        
        
    }
    
    
}  
?>