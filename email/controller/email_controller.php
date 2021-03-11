<?php
include_once "../business/email_bus.php";

if (isset($_GET['function'])) {
    if ($_GET['function'] == 'descadastrar' and isset($_POST['email'])) {
       $emailcontroller = new EmailController();
       $motivo_temp = (isset($_POST['motivo'])? $_POST['motivo'] : "");
       print_r(json_encode($emailcontroller->descadastrar($_POST['email'], $motivo_temp)));
    }
}



class EmailController{
    public function descadastrar($email, $motivo = ""){
        return EmailBus:: descadastrar($email, $motivo);
    }
}