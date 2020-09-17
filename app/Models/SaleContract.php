<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\SaleFee as Fee;
use App\Models\SaleMediation as Mediation;
use App\Models\SaleContractDeposit as Deposit;
use App\Models\SalePurchase as Purchase;
use App\Models\SaleProductResidence as Residence;
use App\Models\MasSection as Section;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class SaleContract extends Model {
    // ----------------------------------------------------------------------
    protected $guarded = [ 'id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function section(){
        return $this->belongsTo( Section::class, 'mas_section_id' );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function mediations(){
        return $this->hasMany( Mediation::class );
    }
    // ----------------------------------------------------------------------
    public function fee(){
        return $this->hasOne( Fee::class );
    }
    // ----------------------------------------------------------------------
    public function deposits(){
        return $this->hasMany( Deposit::class );
    }
    // ----------------------------------------------------------------------
    public function purchases(){
        return $this->hasMany( Purchase::class );
    }
    // ----------------------------------------------------------------------
    public function residences(){
        return $this->hasMany( Residence::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
