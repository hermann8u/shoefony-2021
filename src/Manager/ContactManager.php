<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Contact;
use App\Event\ContactCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ContactManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(Contact $contact): void
    {
        $this->em->persist($contact);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new ContactCreated($contact));
    }
}
