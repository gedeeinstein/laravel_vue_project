<?php
// --------------------------------------------------------------------------
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasMemo;
use App\Models\SaleMemo;
use App\Models\SectionSale as Sale;
use App\Models\SaleContract as Contract;
use App\Models\MasSectionPlan as Plan;
use App\Models\MasSectionPayoff as Payoff;
// --------------------------------------------------------------------------
use App\Models\MasFinance as Finance;
use App\Models\MasFinanceUnit as Unit;
use App\Models\MasFinanceRepayment as Repayment;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasSection extends Model {
    // ----------------------------------------------------------------------
    use HasRelationships;
    protected $guarded = [ 'id' ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function plan(){
        return $this->belongsTo( Plan::class, 'mas_section_plan_id' );
    }
    // ----------------------------------------------------------------------
    public function project(){
        return $this->belongsTo( Project::class );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function saleContract(){
        return $this->hasOne( Contract::class );
    }
    // ----------------------------------------------------------------------
    public function contract(){ // Alias
        return $this->hasOne( Contract::class );
    }
    // ----------------------------------------------------------------------
    public function sale(){
        return $this->hasOne( Sale::class );
    }
    // ----------------------------------------------------------------------
    public function memos(){
        return $this->hasMany( MasMemo::class );
    }
    // ----------------------------------------------------------------------
    public function salememos(){
        return $this->hasMany( SaleMemo::class );
    }
    // ----------------------------------------------------------------------

    public function payoffs(){
        return $this->hasMany(Payoff::class, 'mas_section_id');
    }
    // ----------------------------------------------------------------------
    public static function whereProject( $project_id ){
        return self::where( 'project_id', $project_id );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Distant relations to repayment
    // Using third party plugin
    // https://github.com/staudenmeir/eloquent-has-many-deep#hasmany
    // ----------------------------------------------------------------------
    public function repayments(){
        // ------------------------------------------------------------------
        $foreignKeys = array( 'id' ); // Foreign key in Project
        $localKeys = array( 'project_id' ); // Local key in Section model
        $travel = array( Project::class, Finance::class, Unit::class );
        // ------------------------------------------------------------------
        return $this->hasManyDeep( Repayment::class, $travel, $foreignKeys, $localKeys );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
