<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        $mail->Subject = 'Confirmá tu cuenta';

        // set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . ' </strong> Has creado tu cuenta en UpTask, debes confirmarla presionando el siguiente enlace</p>';
        $contenido .= '<p>Presiona aquí: <a href="'.  $_ENV['APP_URL']  .'/confirmar?token='. $this->token . '">Confirmar Cuenta</a><p>';
        $contenido .= '<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje.</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;

        // enviar el email

        $mail->send();
    }

    public function enviarInstrucciones(){
        //crear el obj email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');
        $mail->Subject = 'Reestablece tu cuenta';

        // set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . ' </strong> Parece que has olvidado tu contraseña, haz click en el siguiente enlace para reestablecerla</p>';
        $contenido .= '<p>Presiona aquí: <a href="'.  $_ENV['APP_URL']  .'/reestablecer?token='. $this->token . '">Reestablecer contraseña</a><p>';
        $contenido .= '<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje.</p>';
        $contenido .= '</html>';
        $mail->Body = $contenido;

        // enviar el email

        $mail->send();

}
}