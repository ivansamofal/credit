<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(private readonly MailerInterface $mailer) {}

    public function sendEmail(): void
    {
        $email = (new Email())
            ->from('')
            ->to('')
            ->subject('')
            ->text('You has received a new credit');
        $this->mailer->send($email);
    }

    public function sendSms()
    {
        //todo
    }
}
