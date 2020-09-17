<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    public $timestamps = false;

    //protected $appends  = ['display_name'];

    protected $fillable =[
        'user_id',
        'activity',
        'detail',
        'email',
        'ip',
        'access_time'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User')->withDefault([
            'nickname' => '-',
            'full_kana_name' => '-',
        ]);
    }
    public function getDisplayNameAttribute()
    {
        return $this->admin()->first()->display_name;
    }
}
