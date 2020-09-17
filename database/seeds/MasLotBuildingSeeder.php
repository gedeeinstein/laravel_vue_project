<?php

use Illuminate\Database\Seeder;
// -----------------------------------------------------------------------------
use App\Models\Project as Project;
use App\Models\PjProperty as Property;
use App\Models\PjLotBuildingA as BuildingA;
// -----------------------------------------------------------------------------
use App\Models\MasBasic as Basic;
use App\Models\MasLotBuilding as Building;
use App\Models\MasLotBuildingFloorSize as BuildingFloorSize;
// -----------------------------------------------------------------------------
class MasLotBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get necesary data
        // ---------------------------------------------------------------------
        $project_id     = 1;
        $project        = Project::findOrFail($project_id);
        $property       = Property::where('project_id', $project_id)->firstOrFail();
        // ---------------------------------------------------------------------

        // get pj lot building a data
        // ---------------------------------------------------------------------
        $buildings = BuildingA::where('pj_property_id', $property->id ?? null)->with(
            'building_floors', 'building_owners',
            'mas_building')->get();
        // ---------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // assign data from assist_a
        // ----------------------------------------------------------------------
        foreach ($buildings as $key => $building) {
            if (!$building->mas_building) { // relation between assist a and mas lot

                // get building data only (without relation)
                $building_only = collect($building)->except([
                    'building_floors',
                    'building_owners',
                    'mas_building',
                ]);

                // assign assist a to mas lot (new variable created before)
                $building->mas_lot_building = factory(Building::class)->states('init')->make();
                $building->mas_lot_building = $building->mas_lot_building->fill($building_only->toArray());
                $building->mas_lot_building->pj_lot_building_a_id = $building->id;

                // assign building floor relation
                foreach ($building->building_floors as $key => $building_floor) {
                    $building->mas_lot_building->building_floors[$key] = factory(BuildingFloorSize::class)->state('init')->make();
                    $building->mas_lot_building->building_floors[$key] = $building->mas_lot_building->building_floors[$key]->fill($building_floor->toArray());
                }
            }
        }
        // ---------------------------------------------------------------------

        // get mas lot building data from pj lot building a relation
        // ---------------------------------------------------------------------
        $buildings = $buildings->map(function ($building, $key) {
            return $building->mas_lot_building;
        });
        // ---------------------------------------------------------------------

        // save mas lot building data
        // ---------------------------------------------------------------------
        foreach ($buildings as $key => $building) {

            // remove unnecesary data
            // -----------------------------------------------------------------
            $building_input = collect($building)->except([
                'building_floors', 'pj_property_id', 'exists_building_residential',
                'city', 'building_date_western'
            ]);
            // -----------------------------------------------------------------

            // save mas lot building
            // -----------------------------------------------------------------
            $building_input['building_date_year'] = $building['building_date_western'] ?? null;
            $mas_building = Building::create($building_input->toArray());
            // -----------------------------------------------------------------

            // save mas lot building floor size
            foreach ($building->building_floors as $key => $building_floor) {

                // remove unnecesary data
                $building_floor_input = collect($building_floor)->except(['pj_lot_building_a_id']);

                // save or update mas lot building floor size
                // -------------------------------------------------------------
                $building_floor_input['mas_lot_building_id'] = $mas_building->id;
                $building_floor = BuildingFloorSize::updateOrCreate(
                    ['id' => $building_floor_input['id']],
                    $building_floor_input->toArray()
                );
                // -------------------------------------------------------------
            }
        }
        // ---------------------------------------------------------------------
    }
}
