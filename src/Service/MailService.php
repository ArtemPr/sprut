<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendMail(
        string $from,
        string $to,
        string $subject,
        string $message
    )
    {
        $email = (new Email())
            ->from(new Address($from))
                ->to(new Address($to))
                ->subject($subject)
                ->text($message)
                ->html($message);

        $this->mailer->send($email);
    }
}
