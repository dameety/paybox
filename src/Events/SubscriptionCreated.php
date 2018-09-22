<?php

namespace Dameety\Paybox\Events;

class SubscriptionCreated
{
    public $user;
    public $planId;

    public function __construct($user, $planId)
    {
        $this->user = $user;
        $this->planId = $planId;
    }
}