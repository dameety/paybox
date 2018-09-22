<?php

namespace Dameety\Paybox\Events;

class SubscriptionCreated extends BaseEvent
{
    public $planId;

    public function __construct($user, $planId)
    {
        parent::__construct($user);
        $this->planId = $planId;
    }
}