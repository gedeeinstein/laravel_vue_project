<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPurchaseResponse extends Model
{
    protected $guarded = [
      'id',
    ];

    public function purchase_target()
    {
      return $this->belongsTo('App\Models\PjPurchaseTarget');
    }

    public function target(){
        return $this->belongsTo('App\Models\PjPurchaseTarget');
    }
}
