<?php

namespace Dameety\Paybox\Events;

class SubscriptionChanged
{
    public $user;
    public $plan;

    public function __construct($user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }
}