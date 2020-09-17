<?php
// --------------------------------------------------------------------------
namespace App\Models;
// --------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// --------------------------------------------------------------------------
use App\Models\User;
use App\Models\Project;
use App\Models\Company;
use App\Models\PjPurchaseSalePjMemo as Memo;
use App\Models\PjPurchaseSaleBuyerStaff as BuyerStaff;

// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjPurchaseSale extends Model {
    // ----------------------------------------------------------------------
    protected $table = 'pj_purchase_sales';
    // ----------------------------------------------------------------------
	protected $fillable = [
		'project_id',
		// ------------------------------------------------------------------
		'company_id_organizer', 'organizer_realestate_explainer',
		'project_address', 'project_address_extra', 'project_type', 'project_status',
		// ------------------------------------------------------------------
		'offer_route', 'offer_date',
		'registry_size', 'registry_size_status',
		'survey_size', 'survey_size_status',
		'project_size', 'project_size_status',
		// ------------------------------------------------------------------
		'purchase_price',
		// ------------------------------------------------------------------
		'project_urbanization_area',
		'project_urbanization_area_status',
		'project_urbanization_area_sub',
        'project_urbanization_area_date',
        // ------------------------------------------------------------------
	];
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
	public function project(){
		return $this->belongsTo( Project::class );
    }
    // ----------------------------------------------------------------------
	public function organizer(){
		return $this->belongsTo( Company::class, 'company_id_organizer' );
    }
    // ----------------------------------------------------------------------
	public function explainer(){
		return $this->belongsTo( User::class, 'organizer_realestate_explainer' );
	}
	// ----------------------------------------------------------------------
	public function buyerStaffs(){
		return $this->hasMany( BuyerStaff::class );
	}
	// ----------------------------------------------------------------------
	public function memos(){
		return $this->hasMany( Memo::class );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
