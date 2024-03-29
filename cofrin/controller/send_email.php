<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../../vendor/autoload.php';



class Email
{
    protected $_to;
    protected $_name;
    protected $_subject;
    protected $_message;
    protected $_headers;


    function __construct($to, $name, $subject, $message)
    {
        $this->_to = $to;
        $this->_name = $name;
        $this->_subject = $subject;
        $this->_message = $message;
        $this->_headers = "From: contato@cofrin.mesttech.com.br";
    }

    function send_email()
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tsl';
            $mail->Username = 'email.de.contato.confin@gmail.com';
            $mail->Password = 'nnBeqy*V$r1u';
            $mail->setFrom('email.de.contato.confin@gmail.com', 'Confin');
            $mail->addAddress($this->_to, $this->_name);
            //$mail->addReplyTo('mest2011mest@gmail.com', 'Your Name');
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
        } catch (\Throwable $th) {
            echo $th;
            return false;
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
