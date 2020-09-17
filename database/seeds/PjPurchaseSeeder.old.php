<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
// Required Models //
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\Project;
use App\Models\PjPurchase;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseTargetBuilding;
use App\Models\PjPurchaseTargetContractor;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialOwner;
use App\Models\PjLotRoadA;
use App\Models\PjLotRoadOwner;
use App\Models\PjLotBuildingA;
use App\Models\PjLotBuildingOwner;
use App\Models\PjBuildingFloorSize;
use App\Models\PjLotContractor;
use App\Models\PjLotCommon;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseDoc;
use App\Models\PjPurchaseSale;
use App\Models\TradingLedger;

class PjPurchaseSeederOld extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(PjPurchase::where('project_id', 1)->first() == null) {
            $purchase = PjPurchase::create([
                'count' => 1,
                'project_id' => 1,
            ]);
        } else {
            $purchase = PjPurchase::where('project_id', 1)->update([
                'count' => 1,
            ]);
        }

        $property_owners = PjPropertyOwner::where('pj_property_id', 1)->get();
        foreach($property_owners as $property_owner) {
            $res_owners = PjLotResidentialOwner::where('pj_property_owners_id', $property_owner->id)->get();
            foreach($res_owners as $res_owner) {
                $new_common = PjLotCommon::create([
                    'pj_property_id' => 1,
                    'pj_lot_residential_a_id' => $res_owner->pj_lot_residential_a_id,
                    'pj_lot_road_a_id' => null,
                    'pj_lot_building_a_id' => null,
                ]);
                PjLotContractor::create([
                    'name'  => $property_owner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $new_common->id,
                    'pj_property_owner_id' => $property_owner->id,
                ]);
            }
            $road_owners = PjLotRoadOwner::where('pj_property_owners_id', $property_owner->id)->get();
            foreach($road_owners as $road_owner) {
                $new_common = PjLotCommon::create([
                    'pj_property_id' => 1,
                    'pj_lot_residential_a_id' => null,
                    'pj_lot_road_a_id' => $road_owner->pj_lot_road_a_id,
                    'pj_lot_building_a_id' => null,
                ]);
                PjLotContractor::create([
                    'name'  => $property_owner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $new_common->id,
                    'pj_property_owner_id' => $property_owner->id,
                ]);
            }
            $buil_owners = PjLotBuildingOwner::where('pj_property_owners_id', $property_owner->id)->get();
            foreach($buil_owners as $buil_owner) {
                $new_common = PjLotCommon::create([
                    'pj_property_id' => 1,
                    'pj_lot_residential_a_id' => null,
                    'pj_lot_road_a_id' => null,
                    'pj_lot_building_a_id' => $buil_owner->pj_lot_building_a_id,
                ]);
                PjLotContractor::create([
                    'name'  => $property_owner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $new_common->id,
                    'pj_property_owner_id' => $property_owner->id,
                ]);
            }
        }

        $purchase_target = factory(PjPurchaseTarget::class, $purchase->count)->create([ 'pj_purchase_id' => $purchase->id ]);

        $pu_target = PjPurchaseTarget::where('pj_purchase_id', $purchase->id)->first();
        foreach($property_owners as $property_owner) {
            $contractor = PjLotContractor::where('name', $property_owner->name)->first();
            PjPurchaseTargetContractor::create([
                'pj_lot_contractor_id'      => $contractor->id,
                'pj_purchase_target_id'     => $pu_target->id,
                'user_id'                   => 1,
            ]);
        }

        PjPurchaseTargetBuilding::create([
            'pj_purchase_target_id'     => $pu_target->id,
            'kind'                      => 1,
            'exist_unregistered'        => 1,
            'purchase_third_person_occupied' => 2,
        ]);

        $pj_purchase_contract = factory(PjPurchaseContract::class, $purchase->count)->create([ 'pj_purchase_target_id' => $pu_target->id ]);
        $pj_purchase_doc = factory(PjPurchaseDoc::class, $purchase->count)->create([ 'pj_purchase_target_id' => $pu_target->id ]);

        $pu_sale = PjPurchaseSale::where('project_id', 1)->first();

        TradingLedger::create([
            'pj_purchase_target_id'    => $pu_target->id,
            'pj_purchase_sale_id'      => $pu_sale->id,
            'sale_contract_id'      => null,
            'ledger_type'           => 1,
            'status'                => 1,
        ]);

    }
}
