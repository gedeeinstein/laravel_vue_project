<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
use App\Models\CompanyBank as Bank;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CompanyBankAccount extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'companies_bank_accounts';
    // ----------------------------------------------------------------------
    protected $fillable = [
        'company_id',
        'bank_id',
        'index',
        'account_kind',
        'account_number'
    ];
    // ----------------------------------------------------------------------
    public function company(){
        return $this->belongsTo( Company::class );
    }
    // ----------------------------------------------------------------------
    public function bank(){
        return $this->belongsTo( Bank::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------