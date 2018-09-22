<?php
namespace Dameety\Paybox\Http\Controllers;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\Models\Subscription;
use Dameety\Paybox\Http\Controllers\Controller;

class PlanSubscriptionsController extends Controller
{
    /**
     * show all subscriptions that belong to a plan
     */
    public function index($planSlug)
    {
        $plan = Plan::findBySlug($planSlug);
        $subs = Subscription::where('stripe_plan', $plan->identifier)->latest('created_at')->simplePaginate(10);

        return view('paybox::plan-subscriptions.index', [
            'plan' => $plan,
            'total' => $subs->count(),
            'subscriptions' => $subs
        ]);
    }
}