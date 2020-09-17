<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockConstruction extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_constructions';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'building_demolition',
        'building_demolition_memo',
        'retaining_wall_demolition',
        'retaining_wall_demolition_memo',
        'transfer_electric_pole',
        'transfer_electric_pole_memo',
        'waterwork_construction',
        'waterwork_construction_memo',
        'fill_work',
        'fill_work_memo',
        'retaining_wall_construction',
        'retaining_wall_construction_memo',
        'road_construction',
        'road_construction_memo',
        'side_groove_construction',
        'side_groove_construction_memo',
        'construction_work_set',
        'construction_work_set_memo',
        'location_designation_application_fee',
        'location_designation_application_fee_memo',
        'development_commissions_fee',
        'development_commissions_fee_memo',
        'cultural_property_research_fee',
        'cultural_property_research_fee_memo'
    );
    // ----------------------------------------------------------------------

    
    // ----------------------------------------------------------------------
    // Project sheet stocking which the construction belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the construction belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
