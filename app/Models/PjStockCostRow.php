<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockCostRow extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_cost_rows';
    protected $fillable = array( 'cost_id', 'cost_type', 'name', 'cost', 'memo' );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which this model belongs to
    // ----------------------------------------------------------------------
    public function cost(){
        return $this->belongsTo( StockCost::class , 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function parent(){ return $this->cost(); } // Alias of cost parent
    // ----------------------------------------------------------------------
}
