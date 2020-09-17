<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MasFinancePurchaseContractor extends Model {

    protected $guarded = [
        'id',
    ];

    public function finance(){
        return $this->belongsTo('App\Models\MasFinance');
    }

    public function purchaser(){
        return $this->hasOne('App\Models\PjLotContractor', 'id', 'pj_lot_contractor_id');
    }

}
