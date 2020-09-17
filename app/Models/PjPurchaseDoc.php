<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPurchaseDoc extends Model
{
    protected $guarded = [
      'id',
    ];

    public function purchase_target()
    {
      return $this->belongsTo('App\Models\PjPurchaseTarget', 'pj_purchase_target_id');
    }

    public function target(){
      return $this->belongsTo('App\Models\PjPurchaseTarget', 'pj_purchase_target_id');
    }

    public function optional_memos()
    {
      return $this->hasMany('App\Models\PjPurchaseDocOptionalMemo');
    }
}
