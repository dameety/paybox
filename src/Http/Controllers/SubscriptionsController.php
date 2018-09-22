<?php
namespace Dameety\Paybox\Http\Controllers;

use Illuminate\Http\Request;
use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\Models\Subscription;
use Dameety\Paybox\Http\Controllers\Controller;

class SubscriptionsController extends Controller
{
    /**
     * list all subscriptions in the application
     *
     * @return void
     */
    public function index()
    {
        $subscriptions = Subscription::latest('created_at')->simplePaginate(4);

        return view('paybox::subscriptions.index', [
            'total' => Subscription::all()->count(),
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * show all subscriptions that belong to a plan
     *
     * @param [type] $id
     */
    public function show($id)
    {
        $sub = Subscription::where('id', $id)->first();
        $plan = Plan::where('identifier', $sub->stripe_plan)->first();

        $subs = Subscription::where('stripe_plan', $plan->identifier)->latest('created_at')->simplePaginate(10);

        return view('paybox::plan-subscriptions.index', [
            'plan' => $plan,
            'total' => Subscription::where('stripe_plan', $plan->identifier)->latest('created_at')->count(),
            'subscriptions' => $subs
        ]);
    }
}