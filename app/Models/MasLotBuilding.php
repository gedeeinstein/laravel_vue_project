<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasLotBuilding extends Model
{
    protected $guarded = [
        'id',
    ];

    public function building_floors(){
        return $this->hasMany('App\Models\MasLotBuildingFloorSize');
    }
}
