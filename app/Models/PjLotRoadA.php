<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotRoadA extends Model
{
    protected $table = 'pj_lot_road_a';

    protected $fillable = [
        'exists_road_residential',
        'parcel_city',
        'parcel_city_extra',
        'parcel_town',
        'parcel_number_first',
        'parcel_number_second',
        'parcel_land_category',
        'parcel_size',
        'parcel_size_survey',
        'pj_property_id',
        'pj_lot_road_b_id'
    ];

    public function property(){
        return $this->belongsTo('App\Models\PjProperty', 'pj_property_id');
    }
    public function common(){
      return $this->hasOne('App\Models\PjLotCommon');
    }
    public function use_districts(){
        return $this->hasMany('App\Models\PjLotRoadParcelUseDistrict');
    }
    public function build_ratios(){
        return $this->hasMany('App\Models\PjLotRoadParcelBuildRatio');
    }
    public function floor_ratios(){
        return $this->hasMany('App\Models\PjLotRoadParcelFloorRatio');
    }
    public function road_owners(){
        return $this->hasMany('App\Models\PjLotRoadOwner');
    }
    public function road_b(){
        return $this->hasOne('App\Models\PjLotRoadB', 'id', 'pj_lot_road_b_id');
    }
    public function road_purchase()
    {
      return $this->hasOne('App\Models\PjLotRoadPurchase');
    }
    public function mas_road(){
        return $this->hasOne('App\Models\MasLotRoad');
    }
}
