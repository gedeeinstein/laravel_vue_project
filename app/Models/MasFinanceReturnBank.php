<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MasFinanceReturnBank extends Model {

    protected $guarded = [
        'id'
    ];

    public function finance(){
        return $this->belongsTo('App\Models\MasFinance');
    }

}
