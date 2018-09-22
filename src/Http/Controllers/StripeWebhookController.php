<?php
namespace Dameety\Paybox\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Dameety\Paybox\Events\SubscriptionDeleted;
use Dameety\Paybox\Events\InvoicePaymentSucceeded;
use Laravel\Cashier\Http\Controllers\WebhookController;

class StripeWebhookController extends WebhookController
{
    /**
     * Handling a cancelled user subscription when renewing fails
     *
     * @param  array $payload
     * @return Response
     */
    public function handleCustomerSubscriptionDeleted(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            event(new SubscriptionDeleted($user));
        }
        return parent::handleCustomerSubscriptionDeleted($payload);
    }

    /**
     * Handle a invoice payment succeeds.
     *
     * @param  array $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            event(new InvoicePaymentSucceeded($user));
        }

        return new Response('Webhook Handled', 200);
    }
}