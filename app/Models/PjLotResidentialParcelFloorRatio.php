<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotResidentialParcelFloorRatio extends Model
{
    protected $fillable = [
        'value',
        'pj_lot_residential_a_id'
    ];

    public function pj_lot_residential_a(){
        return $this->belongsTo('App\Models\PjLotResidentialA');
    }
}
