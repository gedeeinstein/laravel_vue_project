<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
// --------------------------------------------------------------------------
use App\Models\MasSection as Section;
use App\Models\SaleContract as Contract;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class SaleContractSeeder extends Seeder {
    public function run(){
        // ------------------------------------------------------------------
        $sections = Section::with( 'project' );
        $sections->whereHas( 'project', function( Builder $project ){
            // $project->where( 'id', '<=', 15 ); // Enable to limit seed on certain project
        });
        // ------------------------------------------------------------------
        $sections->each( function( $section ){
            // --------------------------------------------------------------
            // Generate random date
            // --------------------------------------------------------------
            $randomDate = function(){
                return Carbon::now()
                    ->subYears( rand( 1, 6 ))
                    ->subMonths( rand( 1, 12 ))
                    ->subDays( rand( 1, 30 ));
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create sale contract
            // --------------------------------------------------------------
            factory( Contract::class )->create([
                'mas_section_id' => $section->id,
                'purchase_date'  => $randomDate(),
                'contract_date'  => $randomDate(),
                'payment_date'   => $randomDate(),
            ]);
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
// --------------------------------------------------------------------------