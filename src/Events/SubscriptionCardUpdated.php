<?php

namespace Dameety\Paybox\Events;

class SubscriptionCardUpdated
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}