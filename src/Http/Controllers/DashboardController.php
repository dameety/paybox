<?php
namespace Dameety\Paybox\Http\Controllers;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Models\Subscription;
use Dameety\Paybox\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected function index()
    {
        return view('paybox::dashboard', [
            'totalPlans' => Plan::all()->count(),
            'totalErrors' => Error::all()->count(),
            'totalSubscriptions' => Subscription::all()->count()
        ]);
    }
}