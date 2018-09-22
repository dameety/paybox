<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\Tests\TestCase;
use Dameety\Paybox\Models\Subscription;

class PlanSubscriptionsTest extends TestCase
{
    /** @test */
    public function loadIndexPage()
    {
        Plan::create([
            'amount' => 100,
            'name' => 'Easyy',
            'currency' => config('paybox.currency.code'),
            'interval' => 'month',
            'identifier' => 'easy-2'
        ]);
        $plan = Plan::first();

        $response = $this->get(route('plan.subscriptions', ['slug' => $plan->slug]));
        $response   ->assertStatus(200)
                    ->assertSee($plan->name);
    }

    /** @test */
    public function canGetAllSubscriptionsOnAPlan()
    {
        $plan = Plan::create([
            'amount' => 100,
            'name' => 'Easyy',
            'currency' => config('paybox.currency.code'),
            'interval' => 'month',
            'identifier' => 'easy-2'
        ]);

        $sub = new Subscription;
        $sub->user_id = 1;
        $sub->name = 'master plan';
        $sub->stripe_id = 'subjdadidia8sosoosido9';
        $sub->stripe_plan = $plan->identifier;
        $sub->quantity = 1;
        $sub->save();

        $sub = new Subscription;
        $sub->user_id = 2;
        $sub->name = 'second master plan';
        $sub->stripe_id = 'subjdadiksk9dia8sosoosido9';
        $sub->stripe_plan = $plan->identifier;
        $sub->quantity = 2;
        $sub->save();

        // $total
        $subs = Subscription::where('stripe_plan', $plan->identifier)->latest('created_at')->simplePaginate(10);

        $firstSub = $subs[0];
        $secondSub = $subs[1];

        $res = $this->get(route('plan.subscriptions', ['slug' => $plan->slug]));
        $res    ->assertStatus(200)
                ->assertSee($plan->name)
                ->assertSee($firstSub->user_id)
                ->assertSee($firstSub->quantity)
                ->assertSee($firstSub->stripe_id)
                ->assertSee($secondSub->user_id)
                ->assertSee($secondSub->quantity)
                ->assertSee($secondSub->stripe_id);

        $this->assertEquals($plan->name, 'Easyy');

        $this->assertEquals($subs->count(), 2);

        $this->assertEquals($firstSub->name, 'master plan');
        $this->assertEquals($firstSub->stripe_id, 'subjdadidia8sosoosido9');
        $this->assertEquals($firstSub->stripe_plan, $plan->identifier);
        $this->assertEquals($firstSub->quantity, 1);

        $this->assertEquals($secondSub->name, 'second master plan');
        $this->assertEquals($secondSub->stripe_id, 'subjdadiksk9dia8sosoosido9');
        $this->assertEquals($secondSub->stripe_plan, $plan->identifier);
        $this->assertEquals($secondSub->quantity, 2);
    }
}