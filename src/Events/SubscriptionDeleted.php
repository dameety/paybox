<?php

namespace Dameety\Paybox\Events;

class SubscriptionDeleted
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}