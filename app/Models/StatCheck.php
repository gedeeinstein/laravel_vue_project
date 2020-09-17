<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatCheck extends Model
{
    protected $fillable = [
        'project_id',
        'screen',
        'status',
        'memo'
    ];

    public function project(){
        return $this->belongsTo('App\Models\Project');
    }

}
