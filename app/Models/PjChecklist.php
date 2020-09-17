<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PjSheet as Sheet;

class PjChecklist extends Model {
    
    protected $table = 'pj_checklists';
    protected $fillable = [
        'pj_sheet_id',
        'breakthrough_rate',
        'effective_area',
        'loan_borrowing_amount',
        'procurement_brokerage_fee',
        'resale_brokerage_fee',
        'sales_area',
        'building_demolition_work',
        'demolition_work_of_retaining_wall',
        'construction_work',
        'driveway',
        'realistic_division',
        'type_of_building',
        'asbestos',
        'many_trees_and_stones',
        'big_storeroom',
        'hard_to_enter',
        'water_draw_count',
        'new_road_type',
        'new_road_width',
        'new_road_length',
        'side_groove',
        'side_groove_length',
        'fill',
        'no_fill',
        'retaining_wall',
        'retaining_wall_height',
        'retaining_wall_length',
        'development_cost',
        'main_pipe_is_distant',
        'road_sharing',
        'traffic_excavation_consent'
    ];

    public function sheet(){
        return $this->belongsTo( Sheet::class );
    }

}
