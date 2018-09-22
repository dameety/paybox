<?php

namespace Dameety\Paybox\Events;

class SubscriptionChanged extends BaseEvent
{
    public $plan;

    public function __construct($plan, $user)
    {
        parent::__construct($user);
        $this->plan = $plan;
    }
}