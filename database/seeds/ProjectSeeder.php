<?php
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasterValue;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ProjectSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        // Create projects
        // ------------------------------------------------------------------
        $counter = 1; $previousDate = null;
        $projects = factory( Project::class, 30 )->create();
        $districts = MasterValue::where('type', 'usedistrict')->where('masterdeleted', 0)->get();
        // ------------------------------------------------------------------
        foreach( $projects as $project ){
            // --------------------------------------------------------------
            if( !$districts->isEmpty()){
                $district = $districts->random();
                $project->usage_area = $district->id;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Determine the project group based on the created year and month
            // --------------------------------------------------------------
            $createdDate = Carbon::parse( $project->created_at );
            if( !$previousDate ) $previousDate = $createdDate;
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                $differentYear = $previousDate->year !== $createdDate->year;
                $differentMonth = $previousDate->month !== $createdDate->month;
                // ----------------------------------------------------------
                // If different group, reset the counter
                // ----------------------------------------------------------
                if( $differentYear || $differentMonth ) $counter = 1; 
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Compose the serial number
            // --------------------------------------------------------------
            $prefix = 'J';
            $serial = sprintf( "%02d", $counter );
            $group = "{$createdDate->format('y')}{$createdDate->format('m')}";
            $number = "{$prefix}{$group}-{$serial}";
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $project->port_pj_info_number = $number; // Update the project
            // --------------------------------------------------------------
                
            // --------------------------------------------------------------
            $counter++; // Increment the counter
            $project->save(); // Save the updates
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------