<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Tests\TestCase;
use Dameety\Paybox\Tests\Utils\User;
use Dameety\Paybox\Tests\Utils\Invoice;

class InvoiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function can_get_user_invoices()
    {
        $mock = \Mockery::mock(StripeGateway::class);

        $invoices = [new Invoice];
        $mock->invoices = collect($invoices);

        $mock->shouldReceive('getUserInvoices')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);

        $user = new User;
        $user->name = 'ade';
        $user->email = 'email@sp.com';
        $user->password = 'mine';
        $user->save();

        $res = $this->actingAs($user)->get('ajax/invoices/show');

        $res->assertStatus(200);
    }

    /** @test */
    public function can_get_user_invoices_by_id()
    {
        $mock = \Mockery::mock(StripeGateway::class);

        $invoices = [new Invoice];
        $mock->invoices = collect($invoices);

        $mock->shouldReceive('getUserInvoices')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('error')
            ->once()
            ->andReturnNull();

        $this->app->instance(StripeGateway::class, $mock);
        $this->app->instance(App\User::class, User::class);

        $user = new User;
        $user->name = 'ade';
        $user->email = 'email@sp.com';
        $user->password = 'mine';
        $user->save();

        $res = $this->actingAs($user)->get('ajax/invoice/' . $user->id . '/show');

        $res->assertStatus(200);
    }
}