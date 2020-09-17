<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPropertyRestriction extends Model
{
    protected $fillable = [
        'restriction_id',
        'pj_property_id'
    ];

    public function property(){
        return $this->belongsTo('App\Models\PjProperty');
    }
}
