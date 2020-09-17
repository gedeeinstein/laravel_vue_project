<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MasFinanceExpense extends Model {

    protected $guarded = [
        'id'
    ];

    public function finance(){
        return $this->belongsTo('App\Models\MasFinance');
    }

    // Mutator to format date to Y/m/d
    public function getDateAttribute($value){
        $date = $value;
        if ($value) $date = Carbon::parse($value)->format('Y/m/d');
        return $date;
    }

    // Mutator to format payperiod to Y/m
    public function getPayperiodAttribute($value){
        $date = $value;
        if ($value) $date = Carbon::parse($value)->format('y/m');
        return $date;
    }

}
