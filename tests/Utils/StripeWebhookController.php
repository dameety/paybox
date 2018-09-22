<?php
namespace Dameety\Paybox\Tests\Utils;

use Dameety\Paybox\Http\Controllers\StripeWebhookController as WebhookController;

class StripeWebhookController extends WebhookController
{
    protected function getUserByStripeId($id)
    {
        return new User();
    }
}