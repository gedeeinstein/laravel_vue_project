<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherAdditionalQaCheck extends Model
{
    protected $fillable = [
        'category_id',
        'question',
        'input_type',
        'choices',
        'status',
        'order'
    ];

    protected $appends = ['type_name', 'options'];

    public function category(){
        return $this->belongsTo('App\Models\OtherAdditionalQaCategory')->orderBy('order', 'asc');
    }

    // input type name acessor
    public function getTypeNameAttribute(){
        $types = config('const.QA_INPUT_TYPES');
        foreach( $types as $id => $type ){
            if ($this->input_type == $id) {
                return $type;
            }
        }
    }

    public function getOptionsAttribute(){
        return json_decode( $this->choices );
    }

    public function answer(){
        return $this->hasOne( 'App\Models\PjAdditionalQa', 'question_id' );
    }

    public function answers(){
        return $this->hasMany( 'App\Models\PjAdditionalQa', 'question_id' );
    }
}
