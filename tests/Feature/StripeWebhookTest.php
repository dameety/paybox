<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Tests\TestCase;
use Dameety\Paybox\Tests\Utils\User;
use Illuminate\Support\Facades\Event;
use Dameety\Paybox\Events\InvoicePaymentSucceeded;
use Dameety\Paybox\Tests\Utils\StripeWebhookController;

class StripeWebhookTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function canHandleInvoicePaymentSucceeded()
    {
        $payload = [
            'data' => [
                'object' => [
                    'customer' => 'cus_123456789'
                ],
            ],
        ];

        $webhookController = new StripeWebhookController();
        $webhookController->handleInvoicePaymentSucceeded($payload);

        Event::assertDispatched(InvoicePaymentSucceeded::class);
    }
}