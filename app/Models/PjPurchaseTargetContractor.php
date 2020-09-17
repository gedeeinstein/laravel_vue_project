<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPurchaseTargetContractor extends Model
{
    protected $fillable = [
		  'pj_lot_contractor_id',
      'pj_purchase_target_id',
      'user_id',
    ];
    
    public function purchase_target()
    {
      return $this->belongsTo('App\Models\PjPurchaseTarget');
    }

    public function contractor()
    {
      return $this->belongsTo('App\Models\PjLotContractor', 'pj_lot_contractor_id');
    }
}
