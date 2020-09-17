<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockRegister extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_registers';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'transfer_of_ownership',
        'transfer_of_ownership_memo',
        'mortgage_setting',
        'fixed_assets_tax',
        'fixed_assets_tax_date',
        'loss',
        'loss_memo'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the register belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the register belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
