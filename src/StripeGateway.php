<?php

namespace Dameety\Paybox;

use Stripe\Stripe;

class StripeGateway
{

    public $invoices;

    /**
     * Any exception thrown during request to stripe
     */
    public $err;

    public function error()
    {
        return $this->err;
    }

    public function createPlan(array $data)
    {
        Stripe::setApiKey(config('services.stripe.key'));

        try {
            \Stripe\Plan::create([
                'amount' => (int)$data['amount'],
                'interval' => $data['interval'],
                'name' => $data['name'],
                "currency" => config('paybox.currency.code'),
                'id' => $data['identifier']
            ]);
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function deletePlan($id)
    {
        Stripe::setApiKey(config('services.stripe.key'));

        try {
            $plan = \Stripe\Plan::retrieve($id);
            $plan->delete();
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function newSubscription(string $token, string $id, $user)
    {
        try {
            if(config('paybox.offerTrial') > 0) {
                $subscription = $user->newSubscription('main', $id);
                $subscription->trialDays(config('paybox.offerTrial'))
                            ->create($token, ['email' => $user->email]);
            } else {
                $subscription = $user->newSubscription('main', $id);
                $subscription->create($token, [
                    'email' => $user->email,
                ]);
            }
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function swapSubscription(string $id, $user)
    {
        try {
            $user->subscription('main')->swap($id);
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function cancelSubscription($user)
    {
        try {
            $user->subscription('main')->cancel();
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function resumeSubscription($user)
    {
        try {
            $user->subscription('main')->resume();
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function updateCard(string $token, $user)
    {
        try {
            $user->updateCard($token);
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }

    public function getUserInvoices ($user)
    {
        try {
            $this->invoices = $user->invoices();
        } catch (\Stripe\Error\Base $e) {
            $this->err = $e;
        }
        return $this;
    }
}