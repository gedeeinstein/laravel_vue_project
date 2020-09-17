<?php

use Illuminate\Database\Seeder;
// -----------------------------------------------------------------------------
use App\Models\PjProperty;
use App\Models\PjPurchaseSale;
// -----------------------------------------------------------------------------
use App\Models\PjLotResidentialA;
use App\Models\PjLotRoadA;
// -----------------------------------------------------------------------------
use App\Models\PjLotResidentialPurchase;
use App\Models\PjLotRoadPurchase;
// -----------------------------------------------------------------------------

class PjLotPurchaseSeeder extends Seeder
{
    public function run()
    {
        $properties = PjProperty::all();
        foreach ($properties as $key => $property) {
            // find purchase sale
            $purchase_sale = PjPurchaseSale::where('project_id', $property->project_id)->first();

            // create residential purchase
            // -----------------------------------------------------------------
            $residentials = PjLotResidentialA::where('pj_property_id', $property->id)->get();
            foreach ($residentials as $key => $residential) {
                PjLotResidentialPurchase::create([
                    'pj_lot_residential_a_id' => $residential->id,
                    'urbanization_area' => $purchase_sale->project_urbanization_area,
                    'urbanization_area_sub' => $purchase_sale->project_urbanization_area_sub,
                    'urbanization_area_number' => $purchase_sale->project_urbanization_area_status == 1 ? '計画有' : '施行中',
                    'urbanization_area_date' => $purchase_sale->project_urbanization_area_date,
                    'urbanization_area_same' => 1,
                ]);
            }
            // -----------------------------------------------------------------

            // create road purchase
            // -----------------------------------------------------------------
            $roads = PjLotRoadA::where('pj_property_id', $property->id)->get();
            foreach ($roads as $key => $road) {
                PjLotRoadPurchase::create([
                    'pj_lot_road_a_id' => $road->id,
                    'urbanization_area' => $purchase_sale->project_urbanization_area,
                    'urbanization_area_sub' => $purchase_sale->project_urbanization_area_sub,
                    'urbanization_area_number' => $purchase_sale->project_urbanization_area_status == 1 ? '計画有' : '施行中',
                    'urbanization_area_date' => $purchase_sale->project_urbanization_area_date,
                    'urbanization_area_same' => 1,
                ]);
            }
            // -----------------------------------------------------------------
        }
    }
}
