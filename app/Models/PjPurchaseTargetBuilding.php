<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PjPurchaseTargetBuilding extends Model
{
    protected $fillable = [
		  'pj_purchase_target_id',
      'kind',
      'exist_unregistered',
      'purchase_third_person_occupied',
    ];
  
    protected $guarded = [
      'id',
    ];
}
