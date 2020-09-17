<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotContractor extends Model
{
    protected $guarded = [
      'id'
    ];

    public function common()
    {
      return $this->belongsTo('App\Models\PjLotCommon', 'pj_lot_common_id');
    }

    public function purchase_target_contractor()
    {
      return $this->hasOne('App\Models\PjPurchaseTargetContractor');
    }

    public function residential_purchase_create()
    {
      return $this->hasOne('App\Models\PjLotResidentialPurchaseCreate')->withDefault([
        'id' => null,
        'pj_lot_residential_a_id' => null,
        'purchase_equity' => 1,
        'purchase_equity_text' => null,
      ]);
    }

    public function road_purchase_create()
    {
      return $this->hasOne('App\Models\PjLotRoadPurchaseCreate')->withDefault([
        'id' => null,
        'pj_lot_road_a_id' => null,
        'purchase_equity' => 1,
        'purchase_equity_text' => null,
      ]);
    }

    public function building_purchase_create()
    {
      return $this->hasOne('App\Models\PjLotBuildingPurchaseCreate')->withDefault([
        'id' => null,
        'pj_lot_building_a_id' => null,
        'purchase_equity' => 1,
        'purchase_equity_text' => null,
      ]);
    }
}
