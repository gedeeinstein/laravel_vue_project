<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjSale as Sale;
use App\Models\PjSalePlanSection as Section;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjSalePlan extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_sale_plans';
    protected $fillable = array(
        'pj_sale_id',
        'tab_index',
        'plan_name',
        'plan_creater',
        'plan_memo',
        'export',
        'reference_area_total',
        'planned_area_total',
        'unit_selling_price_total',
        'gross_profit_plan',
        'gross_profit_total_plan'
    );
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sheet sale where this sale-plan belongs to
    // ----------------------------------------------------------------------
    public function sale(){
        return $this->belongsTo( Sale::class );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project sale plans
    // ----------------------------------------------------------------------
    public function sections(){
        return $this->hasMany( Section::class );
    }
    // ----------------------------------------------------------------------
}
