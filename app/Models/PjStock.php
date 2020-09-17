<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjSheet as Sheet;
use App\Models\PjStockProcurement as Purchase;
use App\Models\PjStockRegister as Registration;
use App\Models\PjStockFinance as Finance;
use App\Models\PjStockTax as Tax;
use App\Models\PjStockConstruction as Construction;
use App\Models\PjStockSurvey as Survey;
use App\Models\PjStockOther as Other;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjStock extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_stockings';
    protected $fillable = array( 'pj_sheet_id' );
    // ----------------------------------------------------------------------

    
    // ----------------------------------------------------------------------
    // Project sheet which the stock belongs to
    // ----------------------------------------------------------------------
    public function sheet(){
        return $this->belongsTo( Sheet::class );
    }
    // ----------------------------------------------------------------------
    

    // ----------------------------------------------------------------------
    // Project stock procurements / purchases
    // ----------------------------------------------------------------------
    public function procurements(){
        return $this->hasOne( Purchase::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------
    public function purchases(){ return $this->procurements(); } // Alias of procurements
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock registers / registrations
    // ----------------------------------------------------------------------
    public function registers(){
        return $this->hasOne( Registration::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------
    public function registrations(){ return $this->registers(); } // Alias of registers
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock finances
    // ----------------------------------------------------------------------
    public function finances(){
        return $this->hasOne( Finance::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock taxes
    // ----------------------------------------------------------------------
    public function taxes(){
        return $this->hasOne( Tax::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock constructions
    // ----------------------------------------------------------------------
    public function constructions(){
        return $this->hasOne( Construction::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock surveys
    // ----------------------------------------------------------------------
    public function surveys(){
        return $this->hasOne( Survey::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Project stock others
    // ----------------------------------------------------------------------
    public function others(){
        return $this->hasOne( Other::class, 'pj_stocking_id' );
    }
    // ----------------------------------------------------------------------
}
