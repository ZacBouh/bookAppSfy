<?php 

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService 
{
    public function __construct(
        private MailerInterface $mailerInterface,
        private $sendToZac,
        private $zetrashEmail
    ){}

    public function sendEmail(string $message) {
        
        $email = (new Email())
        ->from('zacharie.bouhay@gmail.com')
        ->to($this->zetrashEmail)
        ->subject('Sent with Symfony')
        ->html(" <p>Message from book app : $message</p> ");

        $this->mailerInterface->send($email);
    }
}