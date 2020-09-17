<?php

use Illuminate\Database\Seeder;
// -----------------------------------------------------------------------------
use App\Models\Project as Project;
use App\Models\PjProperty as Property;
use App\Models\MasBasic as Basic;
// -----------------------------------------------------------------------------
class MasterBasicSeeder extends Seeder
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
        $purchase_sale  = $project->purchaseSale;
        // ---------------------------------------------------------------------

        // assign data from property and purchase sale
        // ---------------------------------------------------------------------
        $mas_basic = factory(Basic::class)->states('init')->make();
        $mas_basic = $mas_basic->fill(collect($property)->toArray());
        $mas_basic = $mas_basic->fill($purchase_sale->toArray());
        // ---------------------------------------------------------------------

        // remove unnecesary data
        // ---------------------------------------------------------------------
        $mas_basic_input = collect($mas_basic)->except([
            'registry_size', 'registry_size_status', 'survey_size',
            'survey_size_status', 'fixed_asset_tax_route_value', 'restriction_extra',
            'company_id_organizer', 'organizer_realestate_explainer', 'project_address',
            'project_address_extra', 'offer_route', 'offer_date', 'project_type',
            'project_size', 'project_size_status', 'purchase_price', 'project_status',
        ]);
        // ---------------------------------------------------------------------

        // save or update mas basic data
        // ---------------------------------------------------------------------
        $mas_basic = Basic::create($mas_basic_input->toArray());
        // ---------------------------------------------------------------------
    }
}
