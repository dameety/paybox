<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Tests\TestCase;
use Dameety\Paybox\Tests\Utils\User;
use Illuminate\Support\Facades\Event;
use Dameety\Paybox\Models\Subscription;
use Dameety\Paybox\Events\SubscriptionChanged;
use Dameety\Paybox\Events\SubscriptionCreated;
use Dameety\Paybox\Events\SubscriptionResumed;
use Dameety\Paybox\Events\SubscriptionCancelled;
use Dameety\Paybox\Events\SubscriptionCardUpdated;

class SubscriptionTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Event::fake();
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function can_get_correct_subscriptions_total()
    {
        $sub = new Subscription;
        $sub->user_id = 1;
        $sub->name = 'master plan';
        $sub->stripe_id = 'subjdadidia8sosoosido9';
        $sub->stripe_plan = 'master-plan-01';
        $sub->quantity = 1;
        $sub->save();

        $sub = new Subscription;
        $sub->user_id = 2;
        $sub->name = 'master plan2';
        $sub->stripe_id = 'subjdaddsfdsidia8sosoosido9';
        $sub->stripe_plan = 'master-plan-02';
        $sub->quantity = 1;
        $sub->save();

        $sub = new Subscription;
        $sub->user_id = 3;
        $sub->name = 'master plan3';
        $sub->stripe_id = 'subjdadidia8sosoosido9adafdca';
        $sub->stripe_plan = 'master-plan-03';
        $sub->quantity = 1;
        $sub->save();

        $total = Subscription::all()->count();
        $this->assertEquals($total, 3);
    }

    /** @test */
    public function can_Create_new_Subscription()
    {
        //mock stripeGateway
        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('newSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $plan = Plan::create([
            'name' => 'box',
            'amount' => 500000,
            'interval' => 'month',
            'identifier' => 'boxx-01',
            'slug' => 'box'
        ]);

        $response = $this->post('ajax/user-subscription/store', [
            'planName' => $plan->name,
            'stripeToken' => 'test_token'
        ]);

        Event::assertDispatched(SubscriptionCreated::class);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function canCancelSubscription()
    {
        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('cancelSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $response = $this->post(
            '/ajax/cancelled-subscription/store'
        );

        Event::assertDispatched(SubscriptionCancelled::class);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function canSwapASubscription()
    {
        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('swapSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $newPlan = Plan::create([
            'name' => 'map',
            'amount' => 50000,
            'interval' => 'week',
            'identifier' => 'map-02',
            'slug' => 'map'
        ]);

        $response = $this->patch(
            'ajax/user-subscription/' . $newPlan->name . '/update'
        );

        Event::assertDispatched(SubscriptionChanged::class);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function canResumeSubscription()
    {
        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('resumeSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $response = $this->delete(
            '/ajax/cancelled-subscription/destroy'
        );

        Event::assertDispatched(SubscriptionResumed::class);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function canResumeAndSwapSubscription()
    {
        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('resumeSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull()
            ->shouldReceive('swapSubscription')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $newPlan = Plan::create([
            'name' => 'map',
            'amount' => 50000,
            'interval' => 'week',
            'identifier' => 'map-02',
            'slug' => 'map'
        ]);

        $res = $this->post('/ajax/subscriptions/store', [
            'planName' => $newPlan->name
        ]);

        Event::assertDispatched(SubscriptionResumed::class);
        Event::assertDispatched(SubscriptionChanged::class);
        $res->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function canGetSubscriptionsOnSubscriptionIndexPage()
    {
        $sub = new Subscription;
        $sub->user_id = 1;
        $sub->name = 'master plan';
        $sub->stripe_id = 'subjdadidia8sosoosido9';
        $sub->stripe_plan = 'master-plan-01';
        $sub->quantity = 1;
        $sub->save();

        $allSubs = Subscription::all();
        $pSubs = Subscription::latest('created_at')->simplePaginate(10);
        $sub = $pSubs[0]; //get first one in the list

        //act
        $res = $this->get(route('subscriptions.index'));
        $res->assertStatus(200)
            ->assertSee((string)$allSubs->count());

        $this->assertEquals($allSubs->count(), 1);
        $this->assertEquals($sub->name, 'master plan');
        $this->assertEquals($sub->stripe_id, 'subjdadidia8sosoosido9');
        $this->assertEquals($sub->stripe_plan, 'master-plan-01');
        $this->assertEquals($sub->quantity, 1);
    }

    /** @test */
    public function canGetAllSubscriptionsOnAPlan()
    {
        $plan = Plan::create([
            'amount' => 100,
            'name' => 'Easyy',
            'currency' => config('paybox.currency.code'),
            'interval' => 'month',
            'identifier' => 'easy-2',
            'slug' => 'easyy'
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

        $res = $this->get(route('subscriptions.show', ['id' => $sub->id]));
        $res->assertStatus(200)
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