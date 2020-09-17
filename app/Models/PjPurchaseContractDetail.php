<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPurchaseContractDetail extends Model
{
    protected $guarded = [
      'id',
    ];

    public function purchase_contract_deposits()
    {
      return $this->hasMany('App\Models\PjPurchaseContractDeposit');
    }
}
