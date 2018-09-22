<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Events\SubscriptionResumed;
use Dameety\Paybox\Http\Controllers\Controller;
use Dameety\Paybox\Events\SubscriptionCancelled;

class CancelledUserSubscriptionsController extends Controller
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
     * Cancel a subscription
     */
    public function store(Request $request)
    {
        $res = $this->stripeGateway->cancelSubscription($this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionCancelled($this->user));
        return response()->json(['success' => true]);
    }

    /**
     * check if user has canncelled the subscriptopn
     *
     */
    public function show()
    {
        $response = [
            'isCancelled' => false,
            'hasSubscription' => false
        ];

        if ( $this->user->subscribed('main') ) {
            $response['hasSubscription'] = true;
            if ($this->user->subscription('main')->onGracePeriod()) {
                $response['isCancelled'] = true;
            }
        } else {
            $response['hasSubscription'] = false;
        }

        return response()->json($response);
    }

    /**
     * Resume a cancelled subscripton
     */
    public function destroy(Request $request, string $planName = '')
    {
        $res = $this->stripeGateway->resumeSubscription($this->user);
        if ($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        event(new SubscriptionResumed($this->user));
        return response()->json(['success' => true]);
    }
}