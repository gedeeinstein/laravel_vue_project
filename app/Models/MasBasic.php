<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasBasic extends Model
{
    protected $guarded = [
        'id',
    ];

    public function restrictions(){
        return $this->hasMany('App\Models\MasBasicRestriction');
    }
}
