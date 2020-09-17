<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\Company;
use App\Models\MasSection as Section;
use App\Models\SectionSale as Sale;
// ----------------------------------------------------------------------------

class SectionSaleSeeder extends Seeder
{
    public function run()
    {
        $sections = Section::all(); // get all mas_section data
        foreach ($sections as $key => $section) {

            // get random section staff data
            // -----------------------------------------------------------------
            $company       = Company::where('kind_in_house', 1)->get()->random();
            $section_staff = $company->users->random()->id;
            // -----------------------------------------------------------------

            // save section sale data
            // -----------------------------------------------------------------
            Sale::create([
                'mas_section_id'     => $section->id,
                'section_staff'      => $section_staff,
                'sell_period_status' => rand(1, 2),
                'rank'               => rand(1, 11),
            ]);
            // -----------------------------------------------------------------
        }
    }
}
