<?php

namespace App\Models;

use App\Models\MasSection;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class MasMemo extends Model
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
