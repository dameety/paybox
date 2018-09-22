<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Http\Requests\PlanRequest;
use Dameety\Paybox\Events\SubscriptionCreated;
use Dameety\Paybox\Events\SubscriptionChanged;
use Dameety\Paybox\Http\Controllers\Controller;

class UserSubscriptionsController extends Controller
{
    protected $user;
    protected $stripeGateway;

    public function __construct (StripeGateway $stripeGateway)
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->stripeGateway = $stripeGateway;
    }

    /**
     * getting a users subscription status
     *
     * @param string $planName
     */
    public function show(string $planName)
    {
        $response = [
            'hasSubscription' => false,
            'planSubscribedTo' => '',
            'isCancelled' => false
        ];

        if ($this->user->subscribed('main')) {
            $response['hasSubscription'] = true;
        }

        $plan = Plan::where('name', $planName)->first();

        if ($this->user->subscribedToPlan($plan->identifier, 'main')) {
            $response['planSubscribedTo'] = $planName;
        }

        if ($this->user->subscribed('main')) {
            if ($this->user->subscription('main')->onGracePeriod()) {
                $response['isCancelled'] = true;
            }
        } else {
            $response['isCancelled'] = false;
        }

        return response()->json($response);
    }

    /**
     * Creating a new subscription
     *
     * @param Request $request
     */
    public function store(PlanRequest $request)
    {
        $plan = Plan::where('name', $request->planName)->first();
        if(!$plan) {
            return response()->json(['message' => 'Could not find plan with name'], 404);
        }

        $res = $this->stripeGateway->newSubscription($request->stripeToken,  $plan->identifier, $this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionCreated($this->user, $plan->identifier));
        return response()->json(['success' => true]);
    }

    /**
     * Swapping a user's subscription
     *
     * @param [string] $planName
     */
    public function update($planName)
    {
        $plan = Plan::where('name', $planName)->first();
        if (!$plan) {
            return response()->json(['message' => 'Could not find plan.'], 404);
        }

        $res = $this->stripeGateway->swapSubscription($plan->identifier, $this->user);
        if($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionChanged($this->user, $plan->identifier));
        return response()->json(['success' => true]);
    }
}