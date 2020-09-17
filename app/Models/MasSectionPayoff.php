<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasSectionPayoff extends Model
{
    protected $guarded = [
        'id',
    ];

    public function section()
    {
        return $this->belongsTo('App\Models\MasSection');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
