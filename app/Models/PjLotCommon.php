<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotCommon extends Model
{
    protected $guarded = [
      'id'
    ];

    public function contractors(){
      return $this->hasMany('App\Models\PjLotContractor');
    }

    public function residential_a()
    {
      return $this->belongsTo('App\Models\PjLotResidentialA', 'pj_lot_residential_a_id');
    }

    public function road_a()
    {
      return $this->belongsTo('App\Models\PjLotRoadA', 'pj_lot_road_a_id');
    }

    public function building_a()
    {
      return $this->belongsTo('App\Models\PjLotBuildingA', 'pj_lot_building_a_id');
    }

    public function property()
    {
      return $this->belongsTo('App\Models\PjProperty', 'pj_property_id');
    }
}
