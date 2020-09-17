<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStock as Stock;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockProcurement extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_procurements';
    protected $fillable = array(
        'pj_stocking_id',
        'pj_stock_cost_parent_id',
        'price',
        'price_tsubo_unit',
        'brokerage_fee',
        'eviction_fee_memo',
        'brokerage_fee_type',
        'brokerage_fee_memo'
    );
    // ----------------------------------------------------------------------
    // Append accessor columns
    // ----------------------------------------------------------------------
    protected $appends = array( 'absolute_fee' );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet stocking which the procurement belongs to
    // ----------------------------------------------------------------------
    public function stock(){
        return $this->belongsTo( Stock::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Custom brokerage absolute fee accessor
    // Turn possible negative fee value into absolute positive
    // ----------------------------------------------------------------------
    public function getAbsoluteFeeAttribute(){
        return abs( $this->brokerage_fee );
    }
    // ----------------------------------------------------------------------
}
