<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjPurchaseDoc as Doc;
use App\Models\PjPurchase as Purchase;
use App\Models\PjPurchaseResponse as Response;
use App\Models\PjPurchaseContract as Contract;
use App\Models\PjPurchaseTargetBuilding as Building;
use App\Models\PjPurchaseTargetContractor as Contractor;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjPurchaseTarget extends Model {
    // ----------------------------------------------------------------------
    protected $fillable = [
		'pj_purchase_id',
        'purchase_price',
        'purchase_deposit',
        'purchase_not_create_documents',
    ];
    // ----------------------------------------------------------------------
    protected $guarded = [ 'id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function purchase(){
      return $this->belongsTo( Purchase::class, 'pj_purchase_id' );
    }
    // ----------------------------------------------------------------------
    public function purchase_contract(){
      return $this->hasOne( Contract::class );
    }
    // ----------------------------------------------------------------------
    public function purchase_doc(){
      return $this->hasOne( Doc::class );
    }
    // ----------------------------------------------------------------------
    public function doc(){
      return $this->hasOne( Doc::class );
    }
    // ----------------------------------------------------------------------
    public function purchase_response(){
        return $this->hasOne( Response::class );
    }
    // ----------------------------------------------------------------------
    public function contract(){
        return $this->hasOne( Contract::class );
    }
    // ----------------------------------------------------------------------
    public function purchase_target_contractors(){
      return $this->hasMany( Contractor::class );
    }
    // ----------------------------------------------------------------------
    public function purchase_target_buildings(){
      return $this->hasMany( Building::class );
    }
    // ----------------------------------------------------------------------
    public function buildings(){
        return $this->hasMany( Building::class );
    }
    // ----------------------------------------------------------------------
    public function response(){
        return $this->hasOne( Response::class );
    }
    // ----------------------------------------------------------------------
}
