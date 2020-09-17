<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRegion extends Model
{
    protected $fillable = [
        'prefecture_code',
        'group_code',
        'name',
        'kana',
        'order',
        'type',
        'is_enable',
        'government_designated_city'
    ];
}
