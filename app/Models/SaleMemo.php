<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleMemo extends Model
{
    protected $fillable = [
        'mas_section_id',
        'content',
        'author',
        'is_deleted',
    ];

    public function section(){
        return $this->belongsTo( MasSection::class );
    }

    public function user(){
        return $this->belongsTo( User::class, 'author', 'id' );
    }
}
