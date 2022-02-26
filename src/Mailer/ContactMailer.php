<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\Contact;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class ContactMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private string $contactEmailAddress,
    ) {
    }
    
    public function send(Contact $contact): void
    {
        $email = (new Email())
            ->from($contact->getEmail())
            ->to($this->contactEmailAddress)
            ->subject('Une nouvelle demande de contact a eu lieu')
            ->html($this->twig->render('email/contact.html.twig', [
                'contact' => $contact,
            ]))
        ;
        
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }
}
