<?php
namespace Dameety\Paybox\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Error extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name', 'message', 'user', 'action', 'slug'
    ];

    protected $hidden = [
        'id', 'updated_at'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}