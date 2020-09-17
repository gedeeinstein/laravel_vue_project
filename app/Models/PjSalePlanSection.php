<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjSale as Sale;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjSalePlanSection extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_sale_plan_sections';
    protected $fillable = array(
        'pj_sale_plan_id',
        'divisions_number',
        'reference_area',
        'planned_area',
        'unit_selling_price',
        'unit_price',
        'brokerage_fee',
        'brokerage_fee_type'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet sale-plan where this section belongs to
    // ----------------------------------------------------------------------
    public function plan(){
        return $this->belongsTo( Sale::class );
    }
    // ----------------------------------------------------------------------
}
