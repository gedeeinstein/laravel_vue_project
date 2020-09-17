<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotBuildingA extends Model
{
    protected $table = 'pj_lot_building_a';
    protected $appends = [ 'city' ];

    protected $fillable = [
        'exists_building_residential',
        'parcel_city',
        'parcel_city_extra',
        'parcel_town',
        'parcel_number_first',
        'parcel_number_second',
        'building_number_first',
        'building_number_second',
        'building_number_third',
        'building_usetype',
        'building_attached',
        'building_attached_select',
        'building_date_nengou',
        'building_date_year',
        'building_date_month',
        'building_date_day',
        'building_structure',
        'building_floor_count',
        'building_roof',
        'pj_property_id',
        'pj_lot_building_b_id',
        'pj_lot_building_purchase_id'
    ];

    public function property(){
        return $this->belongsTo('App\Models\PjProperty', 'pj_property_id');
    }

    public function building_floors(){
        return $this->hasMany('App\Models\PjBuildingFloorSize');
    }

    public function building_owners(){
        return $this->hasMany('App\Models\PjLotBuildingOwner');
    }

    public function common(){
        return $this->hasOne('App\Models\PjLotCommon');
    }

    public function building_purchase_create(){
        return $this->hasOne('App\Models\PjLotBuildingPurchaseCreate')->withDefault([
            'id' => null,
            'purchase_equity' => null,
            'purchase_equity_text' => null,
        ]);
    }
    public function mas_building(){
        return $this->hasOne('App\Models\MasLotBuilding');
    }

    public function getCityAttribute(){
        return MasterRegion::find( $this->parcel_city );
    }

    public function productBuilding(){
        return $this->hasOne( 'App\Models\PjPurchaseContractCreateBuilding' );
    }
}
