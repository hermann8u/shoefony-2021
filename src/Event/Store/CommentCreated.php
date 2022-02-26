<?php

declare(strict_types=1);

namespace App\Event\Store;

use App\Entity\Store\Comment;

final class CommentCreated
{
    public function __construct(
        private Comment $comment,
    ) {
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }
}
