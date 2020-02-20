<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require PATH_TO_MAILER.'/src/Exception.php';
require PATH_TO_MAILER.'/src/PHPMailer.php';
require PATH_TO_MAILER.'/src/SMTP.php';

class Mailer extends PHPMailer
{
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        //Server settings
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = ADDRESS_FROM;                     // SMTP username
        $this->mail->Password   = ADDRESS_PASS;                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $this->mail->Port       = 587;                                    // TCP port to connect to
        $this->mail->isHTML(true);

        //Recipients
        $this->mail->setFrom(ADDRESS_FROM, 'GFL.TEST.v2');
    }

    public function sendEmailAdmin($arrayData = array())
    {
        $this->sendTo(ADDRESS_ADMIN)
            ->mailContent($arrayData);
        return $this->mail->send();
    }

    public function sendTo($address, $name = '')
    {
        $this->mail->addAddress($address, $name);     // Add a recipient
        return $this;
    }

    public function mailContent($arrayData)
    {
        if(!empty($arrayData))
        {
            $this->mail->Subject = $arrayData['subject'];
            $this->mail->Body = $arrayData['body'];
            $this->mail->AltBody = $arrayData['altBody'];

            return $this;
        }
        // Content DEFAULT
        // Set email format to HTML
        $this->mail->Subject = 'Тест';
        $this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $this;
    }
}

function sendEmailAdmin($info)
{
    $mail = new Mailer();

    if($mail->sendEmailAdmin($info))
        return TRUE;
}

