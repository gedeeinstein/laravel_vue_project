<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockSurvey extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_surveys';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'fixed_survey',
        'fixed_survey_memo',
        'divisional_registration',
        'divisional_registration_memo',
        'boundary_pile_restoration',
        'boundary_pile_restoration_memo'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the survey belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the survey belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
