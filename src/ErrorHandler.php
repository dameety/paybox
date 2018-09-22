<?php

namespace Dameety\Paybox;

use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Events\Errors\Card;
use Dameety\Paybox\Events\Errors\RateLimit;
use Dameety\Paybox\Events\Errors\ApiConnection;
use Dameety\Paybox\Events\Errors\InvalidRequest;
use Dameety\Paybox\Events\Errors\Authentication;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ErrorHandler
{
    protected $id;
    protected $message;
    protected $exception;
    protected $exceptionClass;

    public function __construct ($e, $id)
    {
        $this->id = $id;
        $this->exception = $e;
    }

    protected $expectedExceptions = [
        \Stripe\Error\Base::class,
        \Stripe\Error\Card::class,
        \Stripe\Error\RateLimit::class,
        \Stripe\Error\ApiConnection::class,
        \Stripe\Error\InvalidRequest::class,
        \Stripe\Error\Authentication::class
    ];

    public function respond()
    {
        $this->getExceptionClass();

        $this->logIt();

        if ($this->exception instanceof \Stripe\Error\Card) {
            event(new Card($this->exception));
            return $this->message;
        }
        if ($this->exception instanceof \Stripe\Error\RateLimit) {
            event(new RateLimit($this->exception));
            return 'Could not perform operation. Please try again.';
        }
        if ($this->exception instanceof \Stripe\Error\InvalidRequest) {
            event(new InvalidRequest($this->exception));
            return $this->message;
        }
        if ($this->exception instanceof \Stripe\Error\ApiConnection) {
            event(new ApiConnection($this->exception));
            return 'Could not perform operation. Please try again.';
        }
        if ($this->exception instanceof \Stripe\Error\Authentication) {
            event(new Authentication($this->exception));
            return 'Could not perform operation. Please try again.';
        }
    }

    /**
     * indentify which class the thrown exception belongs to in
     * $expectedExceptions, if not found, just throw it again
     *
     */
    public function getExceptionClass()
    {
        $this->exceptionClass = get_class($this->exception);

        if (!in_array($this->exceptionClass, $this->expectedExceptions)) {
            throw new $this->exception;
        }
    }

    /**
     * log the exception to the database
     *
     */
    public function logIt()
    {
        $err = $body['error'];
        $this->message = $err['message'];
        $body = $this->exception->getJsonBody();

        Error::create([
            'name' => $err['type'],
            'user' => $this->id,
            'message' => $err['message'],
            'slug' => SlugService::createSlug(
                Error::class, 'slug', $err['type']
            )
        ]);
    }
}