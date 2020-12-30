<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../../vendor/autoload.php';



class Email{
    protected $_to;  
    protected $_name;  
    protected $_subject;
    protected $_message;
    protected $_headers;


    function __construct($to, $name, $subject, $message){
        $this->_to = $to;
        $this->_name = $name;
        $this->_subject = $subject;
        $this->_message = $message;
        $this->_headers = "From: contato@mesttech.com.br";
        $this->send_email();

    }

    function send_email(){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'contato@mesttech.com.br';
        $mail->Password = 'vDq@hckq+3kQ';
        $mail->setFrom('contato@mesttech.com.br', 'Confin');
        //$mail->addReplyTo('mest2011mest@gmail.com', 'Your Name');
        $mail->addAddress($this->_to, $this->_name);
        $mail->Subject = $this->_subject;
        // $mail->msgHTML(file_get_contents('message.html'), __DIR__);
        $mail->isHTML(true);
        $mail->Body = $this->_message;
        //$mail->addAttachment('test.txt');
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
        // ini_set('display_errors', 1);
        // error_reporting(E_ALL);
        // $from = $this->_from;
        // $to = $this->_to;
        // $subject = $this->_subject;
        // $message = $this->_message;
        // $headers = $this->_headers;
        // mail($to, $subject, $message, $headers);
        // echo "The email message was sent.";
    }
}
