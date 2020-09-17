<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialParcelUseDistrict;
use App\Models\PjLotResidentialParcelBuildRatio;
use App\Models\PjLotResidentialParcelFloorRatio;
use App\Models\PjLotResidentialOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotRoadA;
use App\Models\PjLotRoadParcelUseDistrict;
use App\Models\PjLotRoadParcelBuildRatio;
use App\Models\PjLotRoadParcelFloorRatio;
use App\Models\PjLotRoadOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotBuildingA;
use App\Models\PjLotBuildingOwner;
use App\Models\PjBuildingFloorSize;
// --------------------------------------------------------------------------
use App\Models\PjLotResidentialB;
use App\Models\PjLotRoadB;
// --------------------------------------------------------------------------
use App\Models\StatCheck;

class PjAssistASeeder extends Seeder
{
    public $project_id = [ 1, 2 ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::whereIn('id', $this->project_id)->get()->each( function( $project ) {
        // ------------------------------------------------------------------
            $faker = Faker::create();
            // --------------------------------------------------------------
            // PROPERTY
            // --------------------------------------------------------------
            $property = factory( PjProperty::class )->create([
                'project_id' => $project->id,
                'fixed_asset_tax_route_value' => $project->fixed_asset_tax_route_value
            ]);
            // --------------------------------------------------------------
            $property_id = [ 'pj_property_id' => $property->id ];
            $created = [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            // --------------------------------------------------------------
            // PROPERTY OWNERS
            // --------------------------------------------------------------
            $owners = factory( PjPropertyOwner::class, 2 )->create( $property_id );
            // --------------------------------------------------------------
            // RESIDENTIAL
            // --------------------------------------------------------------
            $residential_a = factory( PjLotResidentialA::class, 2 )->create([ 'pj_property_id' => $property->id ]);
            $residential_a->each( function( $residential, $index ) use( $faker, $owners ){
                $basic_data = [
                    'pj_lot_residential_a_id' => $residential->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $i = 1;
                $loop = $index == 0 ? 2 : 1;
                while ($i <= $loop) {
                    PjLotResidentialParcelUseDistrict::create([
                        'value' => 40
                    ] + $basic_data);
                    PjLotResidentialParcelBuildRatio::create([
                        'value' => $faker->numberBetween( 80, 90 )
                    ] + $basic_data);
                    PjLotResidentialParcelFloorRatio::create([
                        'value' => $faker->numberBetween( 150, 180 )
                    ] + $basic_data);
                    PjLotResidentialOwner::create([
                        'share_denom' => 10,
                        'share_number' => 3,
                        'other' => 'その他',
                        'other_denom' => $index == 0 ? 5 : 10,
                        'other_number' => $index == 0 ? 2 : 7,
                        'pj_property_owners_id' => $i == 1 ? $owners->first()->id : $owners->first()->id+1,
                    ] + $basic_data);
                    $i++;
                }
            });
            // --------------------------------------------------------------
            // ROAD
            // --------------------------------------------------------------
            $road_a = factory( PjLotRoadA::class, 1 )->create([ 'pj_property_id' => $property->id ]);
            $road_a->each( function( $road, $index ) use( $faker, $owners ){
                $basic_data = [
                    'pj_lot_road_a_id' => $road->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                PjLotRoadParcelUseDistrict::create([
                    'value' => 40
                ] + $basic_data);
                PjLotRoadParcelBuildRatio::create([
                    'value' => $faker->numberBetween( 80, 90 )
                ] + $basic_data);
                PjLotRoadParcelFloorRatio::create([
                    'value' => $faker->numberBetween( 150, 180 )
                ] + $basic_data);
                PjLotRoadOwner::create([
                    'share_denom' => 10,
                    'share_number' => 3,
                    'other' => 'その他',
                    'other_denom' => 10,
                    'other_number' => 7,
                    'pj_property_owners_id' => $owners->random()->id,
                ] + $basic_data);
            });
            // --------------------------------------------------------------
            // BUILDING
            // --------------------------------------------------------------
            $building_a = factory( PjLotBuildingA::class, 2 )->create([ 'pj_property_id' => $property->id ]);
            $building_a->each( function( $building, $index ) use( $faker, $owners ){
                $basic_data = [
                    'pj_lot_building_a_id' => $building->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                PjLotBuildingOwner::create([
                    'share_denom' => 10,
                    'share_number' => 3,
                    'other' => 'その他',
                    'other_denom' => 10,
                    'other_number' => 7,
                    'pj_property_owners_id' => $owners->random()->id,
                ] + $basic_data);

                $i = 1;
                while ($i <= 4) {
                    PjBuildingFloorSize::create([
                        'floor_size' => $faker->numberBetween( 150, 200 )
                    ] + $basic_data);
                    $i++;
                }
            });
            // --------------------------------------------------------------
            // STATUS CHECK
            // --------------------------------------------------------------
            StatCheck::created([
                'project_id' => $project->id,
                'screen' => 'pj_assist_a',
                'status' => 2,
                'memo' => '未完メモ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        // ------------------------------------------------------------------
        });
    }
}
