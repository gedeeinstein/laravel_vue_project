<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockOther extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_others';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'referral_fee',
        'referral_fee_memo',
        'eviction_fee',
        'eviction_fee_memo',
        'water_supply_subscription',
        'water_supply_subscription_memo'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the model belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the model belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
