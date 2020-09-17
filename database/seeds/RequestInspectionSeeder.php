<?php
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\RequestInspection as Inspection;
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class RequestInspectionSeeder extends Seeder {
	// ----------------------------------------------------------------------
	public function run(){
		DB::transaction( function(){
			// --------------------------------------------------------------
			$projects = Project::all();
			$projects->each( function( $project ){
				// ----------------------------------------------------------
				$append = array( 'project_id' => $project->id );
				factory( Inspection::class )->create( $append );
				// ----------------------------------------------------------
			});
			// --------------------------------------------------------------
		});
	}
	// ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------