<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Dameety\Paybox\Events\Errors\Card;
use Dameety\Paybox\Exceptions\ErrorHandler;
use Dameety\Paybox\Events\Errors\RateLimit;
use Dameety\Paybox\Events\Errors\ApiConnection;
use Dameety\Paybox\Events\Errors\InvalidRequest;
use Dameety\Paybox\Events\Errors\Authentication;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ErrorHandlerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /** @test */
    public function canHandleInvalidRequestException()
    {
        $mock = \Mockery::mock(\Stripe\Error\InvalidRequest::class);
        $mock   ->shouldReceive('getJsonBody')
                ->once()
                ->andReturn([
                    'error' => [
                        'type' => 'Invalid request exception.',
                        'message' => 'the params you supplied is invalid'
                    ]
                ]);

        $handler = new ErrorHandler($mock, $id = 1);
        $response = $handler->respond();

        $error = Error::all()[0];

        Event::assertDispatched (InvalidRequest::class);
        $this ->assertEquals(
            'the params you supplied is invalid', $response
        );
        $this->assertEquals($error->name, 'Invalid request exception.');
        $this->assertEquals($error->message, 'the params you supplied is invalid');
        $this->assertEquals($error->user, $id);
    }

    /** @test */
    public function canHandleCardException()
    {
        $mock = \Mockery::mock(\Stripe\Error\Card::class);
        $mock   ->shouldReceive('getJsonBody')
                ->once()
                ->andReturn([
                    'error' => [
                        'type' => 'Card exception.',
                        'message' => 'the card is not correct'
                    ]
                ]);

        $handler = new ErrorHandler($mock, $id = 2);
        $response = $handler->respond();

        $error = Error::all()[0];

        Event::assertDispatched (Card::class);
        $this ->assertEquals('the card is not correct', $response);
        $this->assertEquals($error->name, 'Card exception.');
        $this->assertEquals($error->message, 'the card is not correct');
        $this->assertEquals($error->user, $id);
    }

    /** @test */
    public function canHandleRatelimitException()
    {
        $mock = \Mockery::mock(\Stripe\Error\RateLimit::class);
        $mock   ->shouldReceive('getJsonBody')
                ->once()
                ->andReturn([
                    'error' => [
                        'type' => 'Rate limit exception.',
                        'message' => 'too many api requests. limit reached.'
                    ]
                ]);

        $handler = new ErrorHandler($mock, $id = 11);
        $response = $handler->respond();

        $error = Error::all()[0];

        Event::assertDispatched (RateLimit::class);
        $this ->assertEquals(
            'Could not perform operation. Please try again.', $response
        );
        $this->assertEquals($error->name, 'Rate limit exception.');
        $this->assertEquals($error->message, 'too many api requests. limit reached.');
        $this->assertEquals($error->user, $id);
    }

    /** @test */
    public function canHandleApiConnectionException()
    {
        $mock = \Mockery::mock(\Stripe\Error\ApiConnection::class);
        $mock   ->shouldReceive('getJsonBody')
                ->once()
                ->andReturn([
                    'error' => [
                        'type' => 'Api connection exception.',
                        'message' => 'Could not connect, check internet connection.'
                    ]
                ]);

        $handler = new ErrorHandler($mock, $id = 3);
        $response = $handler->respond();

        $error = Error::all()[0];

        Event::assertDispatched (ApiConnection::class);
        $this ->assertEquals(
            'Could not perform operation. Please try again.', $response
        );
        $this->assertEquals($error->name, 'Api connection exception.');
        $this->assertEquals($error->message, 'Could not connect, check internet connection.');
        $this->assertEquals($error->user, $id);
    }

    /** @test */
    public function canHandleAuthenticationException()
    {
        $mock = \Mockery::mock(\Stripe\Error\Authentication::class);
        $mock   ->shouldReceive('getJsonBody')
                ->once()
                ->andReturn([
                    'error' => [
                        'type' => 'Authentication exception.',
                        'message' => 'Api key is required to authenticated you.'
                    ]
                ]);

        $handler = new ErrorHandler($mock, $id = 4);
        $response = $handler->respond();

        $error = Error::all()[0];

        Event::assertDispatched (Authentication::class);
        $this->assertEquals(
            'Could not perform operation. Please try again.',
            $response
        );
        $this->assertEquals($error->name, 'Authentication exception.');
        $this->assertEquals($error->message, 'Api key is required to authenticated you.');
        $this->assertEquals($error->user, $id);
    }
}