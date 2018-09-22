<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Http\Controllers\Controller;
use Dameety\Paybox\Events\SubscriptionCardUpdated;

class PaymentInfoController extends Controller
{
    protected $user;
    protected $stripeGateway;

    public function __construct(StripeGateway $stripeGateway)
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->stripeGateway = $stripeGateway;
    }

    /**
     * update the user card
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        $request->validate(['stripeToken' => 'required']);

        $res = $this->stripeGateway->updateCard($request->stripeToken, $this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionCardUpdated($this->user));
        return response()->json(['success' => true]);
    }

    /**
     * check if the user can update payment info
     * how? only users with active subscriptions can update their payment info
     * @return void
     */
    public function show()
    {
        if ($this->user->subscribed('main')) {

            return response()->json([
                'hasSubscription' => true,
                'cardBrand' => $this->user->card_brand,
                'lastFour' => $this->user->card_last_four
            ]);

        }

        return response()->json(['hasSubscription' => false]);
    }
}