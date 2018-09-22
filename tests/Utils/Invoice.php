<?php
namespace Dameety\Paybox\Tests\Utils;

use Illuminate\Support\Carbon;

class Invoice
{
    public $id;

    public function total() {
        //
    }

    public function date()
    {
        return Carbon::createFromTimestampUTC(123);
    }
}