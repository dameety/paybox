<?php

namespace Dameety\Paybox\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;

use Dameety\Paybox\Http\Controllers\Controller;

class PlansController extends Controller
{
    protected $stripeGateway;

    public function __construct(StripeGateway $stripeGateway)
    {
        $this->stripeGateway = $stripeGateway;
    }

    public function index()
    {
        return response()->json(['plans' => Plan::all()]);
    }

    /**
     * delete a plan from app and stripe
     *
     * @param string $slug
     */
    public function destroy(string $slug)
    {
        $plan = Plan::findBySlug($slug);

        $res = $this->stripeGateway->deletePlan($plan->identifier);
        if ($res->error()) {
            return response()->json(['error' => $res->err->getMessage()], 422);
        }

        $plan->delete();

        return response()->json(['deleted' => true]);
    }
}