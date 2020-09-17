<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockFinance extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_finances';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'total_interest_rate',
        'expected_interest_rate',
        'banking_fee',
        'banking_fee_memo',
        'stamp',
        'stamp_memo'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the finance belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the finance belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
