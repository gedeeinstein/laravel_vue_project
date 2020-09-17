<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\PjPurchaseSale;
use App\Models\PjPurchaseSaleBuyerStaff;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjPurchaseSaleSeeder extends Seeder {
	// ----------------------------------------------------------------------
	public function run(){
		$projects = Project::all();
		$projects->each( function( $project ){
			// --------------------------------------------------------------
			$append = (object) array( 'project_id' => $project->id );
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Assign company organizer and realestate explainer
			// --------------------------------------------------------------
			$companies = Company::where( 'kind_in_house', true )->get();
			if( !$companies->isEmpty()){
				// ----------------------------------------------------------
				$organizer = $companies->random();
				$append->company_id_organizer = $organizer->id;
				// ----------------------------------------------------------

				// ----------------------------------------------------------
				// Realestate explainer
				// ----------------------------------------------------------
				$query = User::where( 'company_id', $organizer->id );
				$users = $query->where( 'real_estate_notary_registration', true )->get();
				// ----------------------------------------------------------
				if( !$users->isEmpty()){
					$explainer = $users->random();
					$append->organizer_realestate_explainer = $explainer->id;
				}
				// ----------------------------------------------------------
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			factory( PjPurchaseSale::class )->create( (array) $append );
			// --------------------------------------------------------------
		});
		// ---------------------------------------------------------------------

		// ---------------------------------------------------------------------
		// create buyerstaff data
		// ---------------------------------------------------------------------
		$companies = Company::with("users")->where('kind_in_house', 1)->get(); // get data company where kind in house = 1

		if (!$companies->isEmpty()) {
			$purchase_sales = PjPurchaseSale::all();
			foreach ($purchase_sales as $key => $purchase_sale) {
				$random_number = rand(1, 5); // set how many buyerstaff data will be create
				for ($i=0; $i < $random_number; $i++) {

					// get 1 random user from companies
					// ---------------------------------------------------------
					$random_user = $companies->map(function ($item, $key) {
					    return $item->users;
					})->flatten(2)->random(1);
					// ---------------------------------------------------------

					// create buyerstaff data
					// ---------------------------------------------------------
					PjPurchaseSaleBuyerStaff::create([
						"pj_purchase_sale_id" => $purchase_sale->id,
						"user_id" => $random_user[0]->id,
					]);
					// ---------------------------------------------------------
				}
			}
		}
		// ---------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
