<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotRoadParcelBuildRatio extends Model
{
    protected $fillable = [
        'value',
        'pj_lot_road_a_id'
    ];

    public function pj_lot_road_a(){
        return $this->belongsTo('App\Models\PjLotRoadA');
    }
}
