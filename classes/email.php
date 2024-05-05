<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarEmail() {
        //CREAR EL OBJETO DE EMAIL
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('miguel@credimast.com');
        $mail->addAddress('miguel@credimast.com', 'appsalon.com');
        $mail->Subject = 'Verifique sua conta';

        //SET HTML
        $mail->isHTML(TRUE);
        $mail->Charset = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Olá " . $this->nombre . "</strong> Você criou sua conta no appSalon, Você só precisa confirmar clicando no link a seguir</p>";
        $contenido .= "<p>aperte aqui: <a href='".$_ENV['APP_URL']."/confirmar-cuenta?token=" . $this->token . "'>Verificar Conta</a><p>";
        $contenido .= "<p>Se você não solicitou esta conta, você pode ignorar a mensagem</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //ENVIAR EL EMAIL
        $mail->send();
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('miguel@credimast.com');
        $mail->addAddress('miguel@credimast.com', 'appsalon.com');
        $mail->Subject = 'Reestablece tu Password';

        //SET HTML
        $mail->isHTML(TRUE);
        $mail->Charset = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Olá " . $this->nombre . "</strong> Você solicitou a redefinição de sua senha, segue o link abaixo para fazer isso.</p>";
        $contenido .= "<p>Aperte aqui: <a href='".$_ENV['APP_URL']."/recuperar?token=" . $this->token . "'>Redefinir Senha</a><p>";
        $contenido .= "<p>Se você não solicitou esta conta, você pode ignorar a mensagem</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //ENVIAR EL EMAIL
        $mail->send();
    }
}