<?php

namespace Dameety\Paybox\Http\Controllers\Ajax;

use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Http\Controllers\Controller;

class ErrorsController extends Controller
{
    /**
     * delete an error
     *
     * @param [type] $slug
     */
    public function destroy($slug)
    {
        Error::findBySlug($slug)->delete();

        return response()->json(['deleted' => true]);
    }
}