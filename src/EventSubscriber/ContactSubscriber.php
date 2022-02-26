<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\ContactCreated;
use App\Mailer\ContactMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ContactSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ContactMailer $mailer,
        private RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreated::class => [
                ['sendEmail', 10],
                ['addFlash', 0],
            ],
        ];
    }

    public function sendEmail(ContactCreated $event): void
    {
        $contact = $event->getContact();

        $this->mailer->send($contact);
    }

    public function addFlash(ContactCreated $event): void
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();

        $flashBag->add('success', 'Merci, votre demande de contact a bien été prise en compte !');
    }
}
