<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjProperty extends Model
{
    protected $fillable = [
        'school_primary',
        'school_primary_distance',
        'school_juniorhigh',
        'school_juniorhigh_distance',
        'registry_size',
        'registry_size_status',
        'survey_size',
        'survey_size_status',
        'fixed_asset_tax_route_value',
        'transportation',
        'transportation_station',
        'transportation_time',
        'basic_fire_protection',
        'height_district',
        'height_district_use',
        'restriction_extra',
        'basic_cultural_property_reserves',
        'basic_cultural_property_reserves_name',
        'basic_district_planning',
        'basic_district_planning_name',
        'basic_scenic_area',
        'basic_landslide',
        'basic_residential_land_development',
        'project_id'
    ];

    /**
     * Basic relation project table
     *
     */
    public function project(){
        return $this->belongsTo('App\Models\Project');
    }
    public function owners(){
        return $this->hasMany('App\Models\PjPropertyOwner');
    }

    /**
     * Assist A relation
     *
     */
    public function pj_lot_residential_a(){
        return $this->hasMany('App\Models\PjLotResidentialA');
    }
    public function pj_lot_road_a(){
        return $this->hasMany('App\Models\PjLotRoadA');
    }
    public function pj_lot_building_a(){
        return $this->hasMany('App\Models\PjLotBuildingA');
    }
    public function residentials(){
        return $this->hasMany('App\Models\PjLotResidentialA');
    }

    /**
     * Assist B relation
     *
     */
    public function restrictions(){
        return $this->hasMany('App\Models\PjPropertyRestriction');
    }

    public function buildings(){
        return $this->hasMany('App\Models\PjLotBuildingA');
    }

    public function roads(){
        return $this->hasMany('App\Models\PjLotRoadA');
    }
}
