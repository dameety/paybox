<?php
namespace Dameety\Paybox\Http\Controllers;

use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Http\Controllers\Controller;

class ErrorsController extends Controller
{
    /**
     * get all errors in application
     */
    public function index()
    {
        return view('paybox::errors.index', [
            'total' => Error::all()->count(),
            'errors' => Error::latest('created_at')->simplePaginate(10)
        ]);
    }
}