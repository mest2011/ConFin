<?php
include_once "../database/crud.php";

class EmailBus extends Crud{
    public static function descadastrar($email, $motivo){
        $email = addslashes($email);
        $motivo = addslashes($motivo);

        $sql = "UPDATE tb_pessoa SET receber_email = 0, motivo_nao_email = 
        CONCAT(IFNULL(motivo_nao_email, '') , '{$motivo} <br>') 
        WHERE email = '{$email}';";

        if(parent::update($sql)){
            return "Pronto, seu email não esta mais em nossa lista de envio!";
        }else{
            return "Agora você não recebera mais nossos e-mail!";
        }
        
    }
}