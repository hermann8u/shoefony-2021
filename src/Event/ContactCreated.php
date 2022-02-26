<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Contact;

final class ContactCreated
{
    public function __construct(private Contact $contact)
    {
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
