<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
use App\Models\CompanyBorrower as Borrower;
use App\Models\CompanyBankAccount as Account;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CompanyBank extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'companies_banks';
    // ----------------------------------------------------------------------
    protected $fillable = [
        'company_id',
        'index',
        'name_branch',
        'name_branch_abbreviation'
    ];
    // ----------------------------------------------------------------------
    public function company(){
        return $this->belongsTo( Company::class );
    }
    // ----------------------------------------------------------------------
    public function accounts(){
        return $this->hasMany( Account::class );
    }
    // ----------------------------------------------------------------------
    public function borrowers(){
        return $this->hasMany( Borrower::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
