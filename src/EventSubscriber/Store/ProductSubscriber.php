<?php

declare(strict_types=1);

namespace App\EventSubscriber\Store;

use App\Event\ContactCreated;
use App\Event\Store\CommentCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommentCreated::class => [
                ['addFlash'],
            ],
        ];
    }

    public function addFlash(CommentCreated $event): void
    {
        $session = $this->requestStack->getSession();
        $flashBag = $session->getFlashBag();

        $flashBag->add('success', 'Votre commentaire a été enregistré !');
    }
}
