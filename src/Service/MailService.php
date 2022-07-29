<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(
        private MailerInterface $mailer
    )
    {
        $this->templatedEmail = new TemplatedEmail();
    }


    public function sendMail(
        string $from,
        string $to,
        string $subject,
        string $message,
        ?string $bcc = null
    )
    {
        $email = (new Email())
            ->from(Address::create($from))
                ->to(Address::create($to))
                ->subject($subject)
                ->html($message);
            if (!empty($bcc)) {
                $email->bcc(Address::create($bcc));
            }


        $this->mailer->send($email);

    }
}
