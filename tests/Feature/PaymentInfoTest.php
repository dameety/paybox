<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Tests\TestCase;
use Dameety\Paybox\Tests\Utils\User;
use Illuminate\Support\Facades\Event;
use Dameety\Paybox\Events\SubscriptionCardUpdated;

class PaymentInfoTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = new User();

        Event::fake();
    }

    /** @test */
    public function canUpdatePaymentInfo()
    {
        $data = ['stripeToken' => 'test_token'];

        $response = $this->actingAs($this->user)->post(('/ajax/payment-info/update'), $data);

        Event::assertDispatched(SubscriptionCardUpdated::class);
        $response   ->assertStatus(200)
                    ->assertJson(['success' => true]);
    }
}