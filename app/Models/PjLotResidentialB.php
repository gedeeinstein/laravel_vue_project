<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotResidentialB extends Model
{
    protected $table = 'pj_lot_residential_b';

    protected $fillable = [
        'fire_protection',
        'fire_protection_same',
        'cultural_property_reserves',
        'cultural_property_reserves_same',
        'cultural_property_reserves_name',
        'district_planning',
        'district_planning_same',
        'district_planning_name',
        'scenic_area',
        'scenic_area_same',
        'landslide',
        'landslide_same',
        'residential_land_development',
        'residential_land_development_same'
    ];

    public function residential_a(){
        return $this->belongsTo('App\Models\PjLotResidentialA');
    }
}
