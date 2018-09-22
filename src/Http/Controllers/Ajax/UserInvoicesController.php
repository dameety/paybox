<?php
namespace Dameety\Paybox\Http\Controllers\Ajax;

use Dameety\Paybox\ErrorHandler;
use Dameety\Paybox\StripeGateway;
use Dameety\Paybox\Http\Controllers\Controller;

class UserInvoicesController extends Controller
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
     * get the invoices of the auth user
     *
     */
    public function show()
    {
        if (! $this->user->subscribed('main')) {
            return response()->json(['invoices' => []]);
        }

        $res = $this->stripeGateway->getUserInvoices($this->user);
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