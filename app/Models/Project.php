<?php
// --------------------------------------------------------------------------
namespace App\Models;
// --------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
// --------------------------------------------------------------------------
use App\Models\PjBasicQA as BasicQA;
use App\Models\PjAdditionalQa as AdditionalQA;
// --------------------------------------------------------------------------
use App\Models\PjMemo as Memo;
use App\Models\PjSheet as Sheet;
use App\Models\PjExpense as Expense;
use App\Models\PjProperty as Property;
use App\Models\PjPurchase as Purchase;
use App\Models\PjPurchaseSale as Sale;
use App\Models\RequestInspection as Inspection;
// --------------------------------------------------------------------------
use App\Models\MasBasic as Basic;
use App\Models\MasSetting as Setting;
use App\Models\MasSection as Section;
// --------------------------------------------------------------------------
use App\Models\MasFinance as Finance;
use App\Models\MasFinanceUnit as Unit;
use App\Models\MasFinanceRepayment as Repayment;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class Project extends Model {
    // ----------------------------------------------------------------------
    use SoftDeletes;
    use HasRelationships;
    // ----------------------------------------------------------------------
    protected $table = 'projects';
    protected $appends = ['url'];
    // ----------------------------------------------------------------------
    protected $fillable = [
        'title',
        'overall_area',
        'building_area',
        'usage_area',
        'building_coverage_ratio',
        'floor_area_ratio',
        'estimated_closing_date',
        'port_pj_info_number',
        'port_contract_number'
    ];
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function basicQa(){ // Basic Q&A
        return $this->hasOne( BasicQA::class );
    }
    // ----------------------------------------------------------------------
    public function question(){ // Alias of basicQA
        return $this->hasOne( BasicQA::class );
    }
    // ----------------------------------------------------------------------
    public function additionalQas(){ // Additional Q&A
        return $this->hasMany( AdditionalQA::class );
    }
    // ----------------------------------------------------------------------
    public function additionals(){ // Alias of additionalQA
        return $this->hasMany( AdditionalQA::class );
    }
    // ----------------------------------------------------------------------
    public function sheets(){ // Project sheets
        return $this->hasMany( Sheet::class )->orderBy( 'tab_index', 'asc' );
    }
    // ----------------------------------------------------------------------
    public function withBudgetSheet(){ // With reflecting-to-budget sheet only
        return $this->sheets()->where( 'is_reflecting_in_budget', 1 );
    }
    // ----------------------------------------------------------------------
    public function usageArea(){
        return $this->belongsTo('App\Models\MasterValue');
    }
    // ----------------------------------------------------------------------
    public function property(){ // Project property
        return $this->hasOne( Property::class );
    }
    // ----------------------------------------------------------------------
    public function stat_checks(){
        return $this->hasMany('App\Models\StatCheck');
    }
    // ----------------------------------------------------------------------
    public function purchase(){
        return $this->hasOne( Purchase::class );
    }
    // ----------------------------------------------------------------------
    public function finance(){
        return $this->hasOne( Finance::class );
    }
    // ----------------------------------------------------------------------
    public function expense(){
        return $this->hasOne( Expense::class );
    }
    // ----------------------------------------------------------------------
    public function setting(){
        return $this->hasOne( Setting::class );
    }
    // ----------------------------------------------------------------------
    public function purchaseSale(){
        return $this->hasOne( Sale::class );
    }
    // ----------------------------------------------------------------------
    public function memos(){
        return $this->hasMany( Memo::class );
    }
    // ----------------------------------------------------------------------
    public function inspection(){
        return $this->hasMany( Inspection::class );
    }
    // ----------------------------------------------------------------------
    public function basic(){
        return $this->hasOne( Basic::class );
    }
    // ----------------------------------------------------------------------
    public function mas_basic(){
        return $this->hasOne( Basic::class );
    }
    // ----------------------------------------------------------------------
    public function sections(){ // Indirect relation to MasSection
        return $this->hasMany( Section::class );
    }
    // ----------------------------------------------------------------------
    public function units(){    
        $travel = array( Finance::class );
        return $this->hasManyDeep( Unit::class, $travel );
    }
    // ----------------------------------------------------------------------
    public function repayments(){
        $travel = array( Finance::class, Unit::class );
        return $this->hasManyDeep( Repayment::class, $travel );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Generate project management page URLs
    // ----------------------------------------------------------------------
    public function withURL($page = 'all'){
        // ------------------------------------------------------------------
        $projectID = $this->attributes['id'];
        $pages = collect([
            'sheet'             => 'project.sheet',
            'assistA'           => 'project.assist.a',
            'assistB'           => 'project.assist.b',
            'purchaseSale'      => 'project.purchases-sales',
            'purchase'          => 'project.purchase',
            'purchaseCreate'    => 'project.purchase.create',
            'purchaseContract'  => 'project.purchase.contract',
            'contractCreate'    => 'project.purchase.target.contract.create',
            'expense'           => 'project.expense',
            'ledger'            => 'project.ledger',
            // master (B) URL
            'basic'             => 'master.basic',
            'finance'           => 'master.finance',
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Map all routes related to project
        // ------------------------------------------------------------------
        $urls = $pages->map( function( $path, $name ) use( $projectID ){
            // --------------------------------------------------------------
            if( 'contractCreate' === $name ){
                $params = array( 'project' => $projectID, 'target' => 1 );
                return route( $path, $params );
            }
            // --------------------------------------------------------------
            elseif( 'purchaseCreate' === $name ){
                $params = array( 'project' => $projectID, 'purchase_target' => 1 );
                return route( $path, $params );
            }
            // --------------------------------------------------------------
            return route( $path, $projectID );
            // --------------------------------------------------------------
        });

        // ------------------------------------------------------------------
        // Return object all url or single given key url
        // ------------------------------------------------------------------
        return ( $page === 'all' ) ? (object) $urls->all() : $urls->get($page);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // URL attribute accessor
    // ----------------------------------------------------------------------
    public function getUrlAttribute(){
        return $this->withURL('all');
    }
    // ----------------------------------------------------------------------

}
