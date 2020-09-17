<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project as Project;

class PjBasicQA extends Model {
    
    protected $table = 'pj_basic_qas';
    protected $fillable = [
        'project_id',
        'soil_contamination',
        'cultural_property',
        'district_planning',
        'building_use_restrictions',
        'minimum_area',
        'difference_in_height',
        'retaining_wall',
        'retaining_wall_location',
        'retaining_wall_breakage',
        'water',
        'water_supply_pipe',
        'water_meter',
        'sewage',
        'cross_other_land',
        'insufficient_capacity',
        'telegraph_pole_hindrance',
        'telegraph_pole_move',
        'telegraph_pole_high_cost',
        'width_of_front_road',
        'plus_popular',
        'plus_high_price_sale',
        'plus_others',
        'minus_landslide_etc',
        'minus_psychological_defect',
        'minus_others',
        'fixed_survey',
        'fixed_survey_season',
        'fixed_survey_reason',
        'contact_requirement'
    ];

    public function project(){
        return $this->belongsTo( Project::class );
    }

}
