<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingLedger extends Model
{
    protected $fillable = [
        'pj_purchase_target_id',
        'pj_purchase_sale_id',
        'sale_contract_id',
        'ledger_type',
        'status'
    ];
}
