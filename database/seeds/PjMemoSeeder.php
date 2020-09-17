<?php
// --------------------------------------------------------------------------
use App\Models\User;
use App\Models\Project;
use App\Models\PjMemo;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjMemoSeeder extends Seeder {
	// ----------------------------------------------------------------------
	public function run(){
		DB::transaction( function(){
			// --------------------------------------------------------------
			$projects = Project::all();
			$projects->each( function( $project ){
				// ----------------------------------------------------------
				$faker = Faker::create('ja_JP');
				$append = array( 'project_id' => $project->id );
				// ----------------------------------------------------------
	
				// ----------------------------------------------------------
				// Get qualified users
				// ----------------------------------------------------------
				$users = User::with([ 'user_role' => function( $query ){
					$query->where( 'name', '<>', 'no_access' )->where( 'name', '<>', 'agent' );
				}])->get();
				// ----------------------------------------------------------
	
				// ----------------------------------------------------------
				$amount = $faker->numberBetween( 1, 3 );
				$memos = factory( PjMemo::class, $amount )->create( $append );
				// ----------------------------------------------------------
				if( !$users->isEmpty()) $memos->each( function( $memo ) use( $users ){
					// ------------------------------------------------------
					// Assign memo author - user ID
					// ------------------------------------------------------
					$user = $users->random();
					$memo->user_id = $user->id;
					// ------------------------------------------------------
					$memo->save(); // Save the updates
					// ------------------------------------------------------
				});
			});
			// --------------------------------------------------------------
		});
	}
	// ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------