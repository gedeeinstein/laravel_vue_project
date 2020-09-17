<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasLotResidential extends Model
{
    protected $guarded = [
        'id',
    ];

    public function use_districts(){
        return $this->hasMany('App\Models\MasLotResidentialParcelUseDistrict');
    }
    public function build_ratios(){
        return $this->hasMany('App\Models\MasLotResidentialParcelBuildRatio');
    }
    public function floor_ratios(){
        return $this->hasMany('App\Models\MasLotResidentialParcelFloorRatio');
    }
}
