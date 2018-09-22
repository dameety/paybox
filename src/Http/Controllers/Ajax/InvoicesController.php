<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Http\Controllers\Controller;

class InvoicesController extends Controller
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
     * get a users invoice history using the id of the user
     *
     * @param [type] $id
     */
    public function show($id)
    {
        $res = $this->stripeGateway->getUserInvoices(config('paybox.userModel')::find($id));
        if($res->error()) {
            $msg = (new ErrorHandler($res->error(), $this->user->id))->respond();
            return response()->json(['error' => $msg], 422);
        }

        $invoices = [];
        foreach ($res->invoices as $invoice) {
            $invoices[] = [
                'id' => $invoice->id,
                'total' => $invoice->total(),
                'date' => $invoice->date()->toFormattedDateString(),
            ];
        }

        return response()->json(['invoices' => $invoices]);
    }
}