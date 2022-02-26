<?php

declare(strict_types=1);

namespace App\Manager\Store;

use App\Entity\Store\Comment;
use App\Entity\Store\Product;
use App\Event\Store\CommentCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ProductManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function addComment(Product $product, Comment $comment)
    {
        $product->addComment($comment);

        $this->em->persist($comment);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new CommentCreated($comment));
    }
}
