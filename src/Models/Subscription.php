<?php
namespace Dameety\Paybox\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $dates = [
        'created_at',
        'trial_ends_at'
    ];
}