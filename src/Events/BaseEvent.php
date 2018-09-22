<?php

namespace Dameety\Paybox\Events;

class BaseEvent
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}