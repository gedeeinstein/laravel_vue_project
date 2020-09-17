<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
use App\Models\PjStockCost as StockCost;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockTax extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_taxes';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'property_acquisition_tax',
        'property_acquisition_tax_memo',
        'the_following_year_the_city_tax',
        'the_following_year_the_city_tax_memo'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the tax belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost which the tax belongs to
    // ----------------------------------------------------------------------
    public function stockCost(){
        return $this->belongsTo( StockCost::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function additional(){ return $this->stockCost(); } // Alias of stock cost
    // ----------------------------------------------------------------------
}
