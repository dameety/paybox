<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Tests\TestCase;

class PlanTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /** @test */
    public function can_get_correct_plan_total()
    {
        Plan::create([
            'name' => 'one',
            'amount' => '1000',
            'interval' => 'week',
            'identifier' => 'one-01'
        ]);
        Plan::create([
            'name' => 'two',
            'amount' => '2000',
            'interval' => 'week',
            'identifier' => 'two-01'
        ]);
        Plan::create([
            'name' => 'three',
            'amount' => '3000',
            'interval' => 'week',
            'identifier' => 'three-01'
        ]);

        $total = Plan::all()->count();
        $this->assertEquals($total, 3);
    }

    /** @test */
    public function loadPlanCreatePage()
    {
        $response = $this->get(route('plan.create'));

        $response->assertViewIs('Paybox::plans.create')
            ->assertStatus(200)
            ->assertSee('New Plan');
    }

    /** @test */
    public function canLoadIndexPageWithPlans()
    {
        $response = $this->get(route('plan.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function plan_can_be_created()
    {
        $mock = \Mockery::mock(StripeGateway::class);
        $mock   ->shouldReceive('createPlan')
                ->once()
                ->andReturnSelf()
                ->shouldReceive('error')
                ->once()
                ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        //act
        $res = $this->post(route('plan.store', [
            'name' => 'Test level level2',
            'amount' => 50000,
            'interval' => 'week',
            'identifier' => 'Test-level-level-2',
            'slug' => 'test-level-level2'
        ]));

        $res->assertRedirect( route('plan.create') )
            ->assertSessionHas('plan-created', 'The plan was created successfully.');

        // assert plan saved to db
        $plan = Plan::findBySlug('test-level-level2');
        $this->assertEquals($plan->name, 'Test level level2');
        $this->assertEquals($plan->amount, 50000);
        $this->assertEquals($plan->interval, 'week');
        $this->assertEquals($plan->identifier, 'Test-level-level-2');
    }

    /** @test */
    public function can_delete_a_plan()
    {
        $plan = Plan::create([
            'name' => 'deletable plan',
            'amount' => 1000000,
            'interval' => 'month',
            'identifier' => 'identifie',
            'slug' => 'deletable-plan'
        ]);

        $mock = \Mockery::mock(StripeGateway::class);
        $mock->shouldReceive('deletePlan')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $res = $this->delete('ajax/plan/' . $plan->slug . '/delete');

        $res    ->assertJson(['deleted' => true])
                ->assertStatus(200);

        //assert that the plan is deleted
        $plan = Plan::findBySlug(($plan->slug));
        $this->assertEquals($plan, null);
    }
}