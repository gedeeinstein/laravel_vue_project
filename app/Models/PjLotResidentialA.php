<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotResidentialA extends Model
{
    protected $table = 'pj_lot_residential_a';
    protected $appends = [ 'land_category' ];

    protected $fillable = [
        'exists_land_residential',
        'parcel_city',
        'parcel_city_extra',
        'parcel_town',
        'parcel_number_first',
        'parcel_number_second',
        'parcel_land_category',
        'parcel_size',
        'parcel_size_survey',
        'pj_property_id',
        'pj_lot_residential_b_id'
    ];

    public function property(){
        return $this->belongsTo('App\Models\PjProperty', 'pj_property_id');
    }
    public function common(){
      return $this->hasOne('App\Models\PjLotCommon');
    }
    public function use_districts(){
        return $this->hasMany('App\Models\PjLotResidentialParcelUseDistrict');
    }
    public function build_ratios(){
        return $this->hasMany('App\Models\PjLotResidentialParcelBuildRatio');
    }
    public function floor_ratios(){
        return $this->hasMany('App\Models\PjLotResidentialParcelFloorRatio');
    }
    public function residential_owners(){
        return $this->hasMany('App\Models\PjLotResidentialOwner');
    }
    public function residential_b(){
        return $this->hasOne('App\Models\PjLotResidentialB', 'id', 'pj_lot_residential_b_id');
    }
    public function residential_purchase()
    {
        return $this->hasOne('App\Models\PjLotResidentialPurchase');
    }
    public function residentialB(){
        return $this->hasOne('App\Models\PjLotResidentialB', 'id', 'pj_lot_residential_b_id');
    }
    public function mas_residential(){
        return $this->hasOne('App\Models\MasLotResidential');
    }

    // ----------------------------------------------------------------------
    // Master value land category data accessor
    // ----------------------------------------------------------------------
    public function getLandCategoryAttribute(){
        if( $this->parcel_land_category ){
            // --------------------------------------------------------------
            return MasterValue::where( 'type', 'land_category' )->find( $this->parcel_land_category );
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------
}
