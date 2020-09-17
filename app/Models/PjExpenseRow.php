<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\PjExpense as Expense;
use App\Models\PjStockCostRow as Additional;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class PjExpenseRow extends Model {
    // ----------------------------------------------------------------------
    protected $fillable = [
        'pj_expense_id',
        'name',
        'decided',
        'payperiod',
        'payee',
        'payee_type',
        'note',
        'paid',
        'date',
        'bank',
        'taxfree',
        'status',
        'data_kind',
        'screen_name',
        'screen_index',
        'additional_id',
    ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function expense(){
        return $this->belongsTo( Expense::class );
    }
    // ----------------------------------------------------------------------
    public function whereExpense( $id ){
        return $this->where( 'pj_expense_id', $id );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Mutator to format date to Y/m/d
    // ----------------------------------------------------------------------
    public function getDateAttribute( $value ){
        $date = $value;
        if( $value ) $date = Carbon::parse( $value )->format( 'Y/m/d' );
        return $date;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Mutator to format payperiod to Y/m
    // ----------------------------------------------------------------------
    public function getPayperiodAttribute( $value ){
        $date = $value;
        if( $value ) $date = Carbon::parse( $value )->format( 'y/m' );
        return $date;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function additional(){
        return $this->belongsTo( Additional::class, 'additional_id' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
