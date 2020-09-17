<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjLotResidentialOwner extends Model
{
    protected $fillable = [
        'share_denom',
        'share_number',
        'other',
        'other_denom',
        'other_number',
        'pj_property_owners_id',
        'pj_lot_residential_a_id'
    ];

    public function pj_lot_residential_a(){
        return $this->belongsTo('App\Models\PjLotResidentialA');
    }

    public function property_owner()
    {
        return $this->belongsTo('App\Models\PjPropertyOwner', 'pj_property_owners_id');
    }
}
