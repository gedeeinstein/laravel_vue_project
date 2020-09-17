<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjStockCostRow as CostRow;
use App\Models\PjStockProcurement as Purchase;
use App\Models\PjStockRegister as Registration;
use App\Models\PjStockFinance as Finance;
use App\Models\PjStockTax as Tax;
use App\Models\PjStockConstruction as Construction;
use App\Models\PjStockSurvey as Survey;
use App\Models\PjStockOther as Other;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStockCost extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stock_cost_parents';
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock cost rows
    // ----------------------------------------------------------------------
    public function rows(){
        return $this->hasMany( CostRow::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
    public function entries(){ return $this->rows(); } // Alias of rows
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock procurements / purchases
    // ----------------------------------------------------------------------
    public function procurements(){
        return $this->hasOne( Purchase::class );
    }
    // ----------------------------------------------------------------------
    public function purchases(){ return $this->procurements(); } // Alias of procurements
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock registers / registrations
    // ----------------------------------------------------------------------
    public function registers(){
        return $this->hasOne( Registration::class );
    }
    // ----------------------------------------------------------------------
    public function registrations(){ return $this->registers(); } // Alias of registers
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock finances
    // ----------------------------------------------------------------------
    public function finances(){
        return $this->hasOne( Finance::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock taxes
    // ----------------------------------------------------------------------
    public function taxes(){
        return $this->hasOne( Tax::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock constructions
    // ----------------------------------------------------------------------
    public function constructions(){
        return $this->hasOne( Construction::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock surveys
    // ----------------------------------------------------------------------
    public function surveys(){
        return $this->hasOne( Survey::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock others
    // ----------------------------------------------------------------------
    public function others(){
        return $this->hasOne( Other::class, 'pj_stock_cost_parent_id' );
    }
    // ----------------------------------------------------------------------
}
