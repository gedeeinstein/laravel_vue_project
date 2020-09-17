<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjPurchaseTarget as Target;
use App\Models\PjPurchaseContractCreate as ContractCreate;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjPurchaseContract extends Model {
    // ----------------------------------------------------------------------
    protected $guarded = [ 'id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function purchase_detail(){
      return $this->belongsTo('App\Model\PjPurchaseDetail', 'pj_purchase_detail_id');
    }
    // ----------------------------------------------------------------------
    public function purchase_contract_mediations(){
      return $this->hasMany('App\Models\PjPurchaseContractMediation');
    }
    // ----------------------------------------------------------------------
    public function purchase_contract_deposits(){
      return $this->hasMany('App\Models\PjPurchaseContractDeposit');
    }
    // ----------------------------------------------------------------------
    public function purchase_contract_create(){
      return $this->hasOne( ContractCreate::class );
    }
    // ----------------------------------------------------------------------
    public function create(){
      return $this->hasOne( ContractCreate::class );
    }
    // ----------------------------------------------------------------------
    public function target(){
        return $this->belongsTo( Target::class, 'pj_purchase_target_id' );
    }
    // ----------------------------------------------------------------------
    public function deposits(){
      return $this->purchase_contract_deposits();
    }
    // ----------------------------------------------------------------------
    public function mediations(){
      return $this->purchase_contract_mediations();
    }
    // ----------------------------------------------------------------------
}
