<?php
namespace Dameety\Paybox\Tests\Utils;

use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable;

    protected $subscriptions;

    public $isSubscribed = false;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function __construct()
    {
        $this->subscriptions = collect();
    }

    public function newSubscription($subscription = 'main', $plan = null)
    {
        return new Subscription();
    }

    public function subscribed($subscription = 'default', $plan = null)
    {
        return true;
    }

    public function subscription($subscription = 'main')
    {
        return new Subscription();
    }

    public function updateCard($token)
    {
    }
}
