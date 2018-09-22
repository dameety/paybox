<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Events\SubscriptionResumed;
use Dameety\Paybox\Events\SubscriptionChanged;
use Dameety\Paybox\Http\Controllers\Controller;

class SubscriptionsController extends Controller
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
     * storing a new subscription
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $res = $this->stripeGateway->resumeSubscription($this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionResumed($this->user));

        $plan = Plan::where('name', $request->planName)->first();

        $res = $this->stripeGateway->swapSubscription($plan->identifier, $this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionChanged($this->user, $plan));
        return response()->json(['success' => true]);
    }
}