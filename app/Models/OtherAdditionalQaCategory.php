<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherAdditionalQaCategory extends Model
{
    protected $fillable = [
        'name',
        'order'
    ];

    public function questions(){
        return $this->hasMany('App\Models\OtherAdditionalQaCheck', 'category_id');
    }
}
