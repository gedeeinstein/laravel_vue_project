<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPropertyOwner extends Model
{
    protected $fillable = [
        'name',
        'pj_property_id'
    ];

    public function property(){
        return $this->belongsTo('App\Models\PjProperty');
    }
    public function road_a()
    {
        return $this->hasOne('App\Models\PjLotRoadOwner', 'pj_properties_owner_id');
    }
}
