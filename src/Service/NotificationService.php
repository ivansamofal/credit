<?php

namespace App\Service;

use App\Entity\Client;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(private readonly MailerInterface $mailer) {}

    public function sendEmail(Client $client): void
    {
        $email = (new Email())
            ->from('admin@credit.com')
            ->to($client->getEmail())
            ->subject('New credit')
            ->text('You has received a new credit');
        $this->mailer->send($email);
    }

    public function sendSms()
    {
        //todo
    }
}
