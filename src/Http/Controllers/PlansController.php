<?php
namespace Dameety\Paybox\Http\Controllers;

use Dameety\Paybox\Models\Plan;
use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Http\Requests\PlanRequest;

use Dameety\Paybox\Http\Controllers\Controller;

class PlansController extends Controller
{
    protected $stripeGateway;

    public function __construct(StripeGateway $stripeGateway)
    {
        $this->stripeGateway = $stripeGateway;
    }

    /**
     * load all the plans in db
     *
     * @return void
     */
    public function index()
    {
        return view('paybox::plans.index', [
            'plans' => Plan::latest('created_at')->get()
        ]);
    }

    public function create()
    {
        return view('paybox::plans.create');
    }

    /**
     * Store a new plan in db
     *
     * @param PlanRequest $request
     */
    public function store(PlanRequest $request)
    {
        $res = $this->stripeGateway->createPlan( $request->all() );

        if ($res->error()) {
            return back()->with('stripeError', $res->error()->getMessage());
        }

        Plan::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'interval' => $request->interval,
            'identifier' => $request->identifier,
            'features' => $request->features
        ]);

        return redirect()->route('plan.create')
        ->with('plan-created', 'The plan was created successfully.');
    }
}
