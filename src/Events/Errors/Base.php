<?php

namespace Dameety\Paybox\Events\Errors;

abstract class Base
{
    public $exception;

    public function __construct ($exception)
    {
        $this->exception = $exception;
    }
}