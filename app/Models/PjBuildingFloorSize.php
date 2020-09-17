<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjBuildingFloorSize extends Model
{
    protected $fillable = [
        'floor_size',
        'pj_lot_building_a_id'
    ];

    public function pj_lot_building_a(){
        return $this->belongsTo('App\Models\PjLotBuildingA');
    }
}
