<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasFinanceUnit as Unit;
use App\Models\MasFinanceExpense as Expense;
use App\Models\MasFinanceReturnBank as ReturnBank;
use App\Models\MasFinancePurchaseContractor as Contractor;
use App\Models\MasFinancePurchaseAccountMain as Account;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasFinance extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'mas_finances';
    protected $guarded = array( 'id', 'project_id' );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Project which this table belongs to
    // ----------------------------------------------------------------------
    public function project(){
        return $this->belongsTo( Project::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // It has many mas_finance_units
    // ----------------------------------------------------------------------
    public function units(){
        return $this->hasMany( Unit::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // has many mas_finance_purchase_account_mains
    // ----------------------------------------------------------------------
    public function accounts(){
        return $this->hasMany( Account::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // has many mas_finance_expenses
    // ----------------------------------------------------------------------
    public function expenses(){
        return $this->hasMany( Expense::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // It has many mas_finance_purchase_contractors
    // ----------------------------------------------------------------------
    public function contractors(){
        return $this->hasMany( Contractor::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // It has one mas_finance_return_banks
    // ----------------------------------------------------------------------
    public function returnBank(){
        return $this->hasOne( ReturnBank::class );
    }
    // --------------------------------returnBank--------------------------------------

}
