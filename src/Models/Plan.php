<?php
namespace Dameety\Paybox\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Plan extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name', 'interval', 'amount', 'identifier', 'features', 'slug'
    ];

    protected $hidden = [
        'id', 'updated_at'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
