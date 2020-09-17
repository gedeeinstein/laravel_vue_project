<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MasFinanceRepayment extends Model {

    protected $guarded = [
        'id'
    ];

    public function unit(){
        return $this->belongsTo('App\Models\MasFinanceUnit');
    }

    // ----------------------------------------------------------------------
    // Mutator to format date to Y/m/d
    // ----------------------------------------------------------------------
    public function getDateAttribute($value){
        $date = $value;
        if ($value) $date = Carbon::parse($value)->format('Y/m/d');
        return $date;
    }

}
