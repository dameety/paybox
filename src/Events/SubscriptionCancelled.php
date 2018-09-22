<?php

namespace Dameety\Paybox\Events;

class SubscriptionCancelled
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}