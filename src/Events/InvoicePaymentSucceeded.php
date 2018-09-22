<?php

namespace Dameety\Paybox\Events;

class InvoicePaymentSucceeded
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}