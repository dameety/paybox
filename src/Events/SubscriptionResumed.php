<?php

namespace Dameety\Paybox\Events;

class SubscriptionResumed
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}