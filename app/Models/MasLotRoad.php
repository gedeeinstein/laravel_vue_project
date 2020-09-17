<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasLotRoad extends Model
{
    protected $guarded = [
        'id',
    ];

    public function use_districts(){
        return $this->hasMany('App\Models\MasLotRoadParcelUseDistrict');
    }
    public function build_ratios(){
        return $this->hasMany('App\Models\MasLotRoadParcelBuildRatio');
    }
    public function floor_ratios(){
        return $this->hasMany('App\Models\MasLotRoadParcelFloorRatio');
    }
}
