<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasterValue;
// --------------------------------------------------------------------------
use App\Models\PjSheet;
use App\Models\PjBasicQA;
use App\Models\PjChecklist;
use App\Models\PjAdditionalQa;
use App\Models\OtherAdditionalQaCheck;
use App\Models\OtherAdditionalQaCategory;
// --------------------------------------------------------------------------
use App\Models\PjStock;
use App\Models\PjStockCost;
use App\Models\PjStockRegister;
use App\Models\PjStockProcurement;
use App\Models\PjStockFinance;
use App\Models\PjStockTax;
use App\Models\PjStockConstruction;
use App\Models\PjStockSurvey;
use App\Models\PjStockOther;
// --------------------------------------------------------------------------
use App\Models\PjSale;
use App\Models\PjSalePlan;
use App\Models\PjSalePlanSection;
use App\Models\PjSaleCalculator;
// --------------------------------------------------------------------------
use App\Models\PjPurchase;
// --------------------------------------------------------------------------
use App\Models\MasFinance;
use App\Models\MasFinanceUnit;
use App\Models\MasSection;
// --------------------------------------------------------------------------
use App\Models\RequestInspection as Inspection;
use App\Models\PjSheetCalculate as SheetValues;
// --------------------------------------------------------------------------
use App\Models\PjExpense as Expense;
use App\Models\PjExpenseRow as ExpenseRow;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\PjStockCostRow;
// --------------------------------------------------------------------------
use App\Reports\CheckListReport;
use App\Reports\SheetReport;
use Response;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ProjectSheetController extends Controller {
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get fields to update of the project
    // ----------------------------------------------------------------------
    private function get_project_updates( $project ){
        $updates = new \stdClass; $project = (object) $project;
        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'title', 'overall_area', 'fixed_asset_tax_route_value', 'building_area',
            'usage_area', 'building_coverage_ratio', 'floor_area_ratio', 'estimated_closing_date',
            'port_pj_info_number', 'port_contract_number'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the updates
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $project, $field )){
            $updates->{ $field } = $project->{ $field };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Format closing date into timestamp
            // --------------------------------------------------------------
            if( 'estimated_closing_date' == $field ){
                $updates->{ $field } = Carbon::parse( $project->{ $field });
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get fields to update of the basic QA question
    // ----------------------------------------------------------------------
    private function get_question_basic_updates( $question ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $question = (object) $question;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'soil_contamination', 'cultural_property', 'district_planning',
            'minimum_area', 'building_use_restrictions', 'difference_in_height',
            'retaining_wall', 'retaining_wall_location', 'retaining_wall_breakage',
            'water', 'water_supply_pipe', 'water_meter', 'sewage',
            'private_pipe', 'cross_other_land', 'insufficient_capacity',
            'telegraph_pole_hindrance', 'telegraph_pole_move', 'telegraph_pole_high_cost',
            'width_of_front_road', 'plus_popular', 'plus_high_price_sale', 'plus_others',
            'plus_low_price_sale', 'minus_landslide_etc', 'minus_psychological_defect', 'minus_others',
            'fixed_survey', 'fixed_survey_season', 'fixed_survey_reason', 'contact_requirement'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $question, $field )){
            $updates->{ $field } = $question->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // District planning
        // ------------------------------------------------------------------
        if( !empty( $question->district_planning ) && 1 !== (int) $question->district_planning ){
            $updates->minimum_area = null;
            $updates->building_use_restrictions = null;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Retaining wall
        // ------------------------------------------------------------------
        if( !empty( $question->retaining_wall ) && 1 !== (int) $question->retaining_wall ){
            $updates->retaining_wall_location = null;
            $updates->retaining_wall_breakage = null;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Water
        // ------------------------------------------------------------------
        if( !empty( $question->water ) && 1 !== (int) $question->water ){
            $updates->water_supply_pipe = 0;
            $updates->water_meter = 0;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Obstructive pole
        // ------------------------------------------------------------------
        if( !empty( $question->telegraph_pole_hindrance ) && 1 !== (int) $question->telegraph_pole_hindrance ){
            $updates->telegraph_pole_move = 0;
            $updates->telegraph_pole_high_cost = 0;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Fixed survey
        // ------------------------------------------------------------------
        if( !empty( $question->fixed_survey )){
            $survey = (int) $question->fixed_survey;
            if( 3 !== $survey ) $updates->fixed_survey_season = null;
            elseif( 4 !== $survey ) $updates->fixed_survey_reason = 0;
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get base sheet updates
    // ----------------------------------------------------------------------
    private function get_sheet_updates( $sheet ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $sheet = (object) $sheet;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'tab_index', 'is_reflecting_in_budget', 'name', 'creator_name', 'memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $sheet, $field )){
            $updates->{ $field } = $sheet->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sheet checklist updates
    // ----------------------------------------------------------------------
    private function get_sheet_checklist_updates( $checklist, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $checklist = (object) $checklist;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'breakthrough_rate', 'effective_area', 'loan_borrowing_amount',
            'procurement_brokerage_fee', 'resale_brokerage_fee', 'sales_area'
        ]);
        // ------------------------------------------------------------------
        if( !empty( $checklist->sales_area ) && $checklist->sales_area ){
            $fields = $fields->merge([
                'building_demolition_work', 'demolition_work_of_retaining_wall',
                'construction_work', 'driveway', 'realistic_division', 'type_of_building',
                'asbestos', 'many_trees_and_stones', 'big_storeroom', 'hard_to_enter',
                'water_draw_count', 'new_road_type', 'new_road_width', 'new_road_length',
                'side_groove', 'side_groove_length', 'fill', 'no_fill', 'retaining_wall',
                'retaining_wall_height', 'retaining_wall_length', 'development_cost',
                'main_pipe_is_distant', 'main_pipe_is_distant', 'road_sharing',
                'traffic_excavation_consent'
            ]);
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $checklist, $field )){
            $updates->{ $field } = $checklist->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        if( !empty( $checklist->sales_area )){
            // --------------------------------------------------------------
            // Building demolition work
            // --------------------------------------------------------------
            if( isset( $checklist->building_demolition_work )){
                if( ! (int) $checklist->building_demolition_work ){
                    // ------------------------------------------------------
                    $updates->type_of_building = null;
                    $updates->asbestos = null;
                    $updates->many_trees_and_stones = null;
                    $updates->big_storeroom = null;
                    $updates->hard_to_enter = null;
                    // ------------------------------------------------------
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Construction work
            // --------------------------------------------------------------
            if( !empty( $checklist->construction_work )){
                // ----------------------------------------------------------
                $nonDevelopment = ( 2 === (int) $checklist->construction_work );
                $development = ( 3 === (int) $checklist->construction_work && $project->overall_area < 1000 );
                // ----------------------------------------------------------
                // Set null all related fields of conditions do not match
                // ----------------------------------------------------------
                if( !$nonDevelopment && !$development ){
                    $updates->water_draw_count = null;
                    $updates->new_road_type = null;
                    $updates->new_road_width = null;
                    $updates->new_road_length = null;
                    $updates->side_groove = null;
                    $updates->side_groove_length = null;
                    $updates->fill = null;
                    $updates->no_fill = null;
                    $updates->retaining_wall = null;
                    $updates->retaining_wall_height = null;
                    $updates->retaining_wall_length = null;
                }
                // ----------------------------------------------------------
                else {
                    // ------------------------------------------------------
                    // Road type
                    // ------------------------------------------------------
                    if( !empty( $updates->new_road_type )){
                        if( 3 === (int) $updates->new_road_type ){
                            $updates->new_road_width = null;
                            $updates->new_road_length = null;
                        }
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Road gutter
                    // ------------------------------------------------------
                    if( !empty( $updates->side_groove )){
                        if( 2 !== (int) $updates->side_groove && 1 !== (int) $updates->side_groove ){
                            $updates->side_groove_length = null;
                        }
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Retaining wall
                    // ------------------------------------------------------
                    if( !empty( $updates->retaining_wall )){
                        if( 1 !== (int) $updates->retaining_wall ){
                            $updates->retaining_wall_height = null;
                            $updates->retaining_wall_length = null;
                        }
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Driveway / Private road
            // --------------------------------------------------------------
            if( !empty( $checklist->driveway )){
                if( ! (int) $checklist->driveway ){
                    $updates->road_sharing = null;
                    $updates->traffic_excavation_consent = null;
                }
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sheet stock updates
    // ----------------------------------------------------------------------
    private function get_sheet_stock_updates( $stock ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $stock = (object) $stock;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'total_budget_cost', 'total_decision_cost'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $stock, $field )){
            $updates->{ $field } = $stock->{ $field };
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock purchase / procurement updates
    // ----------------------------------------------------------------------
    private function get_stock_purchase_updates( $purchase, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass;
        $purchase = (object) $purchase;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'price', 'price_tsubo_unit',
            'brokerage_fee', 'brokerage_fee_type', 'brokerage_fee_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $purchase, $field )){
            $updates->{ $field } = $purchase->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Update purchase fee as positive or negative signed value
        // This has been done on the frontend side, this is the second layer measurement
        // https://bit.ly/2RhxdIK
        // ------------------------------------------------------------------
        if( !empty( $updates->brokerage_fee ) && !empty( $updates->brokerage_fee_type )){
            // --------------------------------------------------------------
            $purchase->brokerage_fee = abs( $purchase->brokerage_fee ); // Assign absolute number
            // --------------------------------------------------------------
            if( 1 === (int) $purchase->brokerage_fee_type ){
                $purchase->brokerage_fee = $purchase->brokerage_fee * -1; // Turn to negative
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock registration / register updates
    // ----------------------------------------------------------------------
    private function get_stock_registration_updates( $registration, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $registration = (object) $registration;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'transfer_of_ownership', 'transfer_of_ownership_memo',
            'mortgage_setting', 'mortgage_setting_plan', 'fixed_assets_tax',
            'fixed_assets_tax_date', 'loss', 'loss_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $registration, $field )){
            $updates->{ $field } = $registration->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock finance updates
    // ----------------------------------------------------------------------
    private function get_stock_finance_updates( $finance, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $finance = (object) $finance;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'total_interest_rate', 'expected_interest_rate',
            'banking_fee', 'banking_fee_memo', 'stamp', 'stamp_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $finance, $field )){
            $updates->{ $field } = $finance->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock tax updates
    // ----------------------------------------------------------------------
    private function get_stock_tax_updates( $tax, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $tax = (object) $tax;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'property_acquisition_tax', 'property_acquisition_tax_memo',
            'the_following_year_the_city_tax', 'the_following_year_the_city_tax_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $tax, $field )){
            $updates->{ $field } = $tax->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock construction updates
    // ----------------------------------------------------------------------
    private function get_stock_construction_updates( $construction, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass;
        $construction = (object) $construction;
        // ------------------------------------------------------------------
        $checklist = $sheet->checklist ?? null;
        if( $checklist ) $checklist = (object) $checklist;
        // ------------------------------------------------------------------
        $question = $project->question ?? null;
        if( $question ) $question = (object) $question;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'building_demolition', 'building_demolition_memo',
            'waterwork_construction', 'waterwork_construction_memo',
            'retaining_wall_construction', 'retaining_wall_construction_memo',
            'road_construction', 'road_construction_memo',
            'side_groove_construction', 'side_groove_construction_memo',
            'construction_work_set', 'construction_work_set_memo',
            'location_designation_application_fee', 'location_designation_application_fee_memo',
            'development_commissions_fee', 'development_commissions_fee_memo',

            'retaining_wall_demolition', 'retaining_wall_demolition_memo',
            'transfer_electric_pole', 'transfer_electric_pole_memo',
            'cultural_property_research_fee', 'cultural_property_research_fee_memo',
            'fill_work', 'fill_work_memo',
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $construction, $field )){
            $updates->{ $field } = $construction->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock survey updates
    // ----------------------------------------------------------------------
    private function get_stock_survey_updates( $survey, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $survey = (object) $survey;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'fixed_survey', 'fixed_survey_memo',
            'divisional_registration', 'divisional_registration_memo',
            'boundary_pile_restoration', 'boundary_pile_restoration_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $survey, $field )){
            $updates->{ $field } = $survey->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get stock other updates
    // ----------------------------------------------------------------------
    private function get_stock_other_updates( $other, $sheet, $project ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $other = (object) $other;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'referral_fee', 'referral_fee_memo',
            'eviction_fee', 'eviction_fee_memo',
            'water_supply_subscription', 'water_supply_subscription_memo'
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $other, $field )){
            $updates->{ $field } = $other->{ $field };
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Update / Create stock section
    // ----------------------------------------------------------------------
    private function update_stock_section( $type, $dataset, $stock, $sheet, $project ){
        // ------------------------------------------------------------------
        if( empty( $type ) || empty( $dataset )) return;
        if( empty( $stock ) || empty( $sheet ) || empty( $project )) return;
        $dataset = (object) $dataset;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Stock model list
        // ------------------------------------------------------------------
        $model = null; $method = null;
        // ------------------------------------------------------------------
        if( 'purchase' == $type || 'procurement' == $type ){
            $model = 'PjStockProcurement';
            $method = 'get_stock_purchase_updates';
        }
        // ------------------------------------------------------------------
        elseif( 'register' == $type || 'registration' == $type ){
            $model = 'PjStockRegister';
            $method = 'get_stock_registration_updates';
        }
        // ------------------------------------------------------------------
        elseif( 'finance' == $type ) $model = 'PjStockFinance';
        elseif( 'tax' == $type ) $model = 'PjStockTax';
        elseif( 'construction' == $type ) $model = 'PjStockConstruction';
        elseif( 'survey' == $type ) $model = 'PjStockSurvey';
        elseif( 'other' == $type ) $model = 'PjStockOther';
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Get the updates
        // ------------------------------------------------------------------
        $method = $method ?? "get_stock_{$type}_updates";
        $updates = (object) $this->{ $method }( $dataset, $sheet, $project );
        if( !empty( $stock->id )) $updates->pj_stocking_id = $stock->id;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        if( !empty( $model )){
            // --------------------------------------------------------------
            // Prepare stock section model, create new if ID doesn't exist
            // --------------------------------------------------------------
            $model = "App\\Models\\{$model}";
            $section = new $model();
            if( !empty( $dataset->id )){
                $section = $model::find( $dataset->id );
                if( !$section ) $section = new $model();
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Apply the updates
            // --------------------------------------------------------------
            foreach( (array) $updates as $field => $update ){
                $section->{ $field } = $update;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $section->save(); // Save the updates
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Process additional costs if available
            // --------------------------------------------------------------
            if( !empty( $dataset->additional )){
                // ----------------------------------------------------------
                $additional = (object) $dataset->additional;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Find or create stock-cost-parent
                // ----------------------------------------------------------
                $parent = new PjStockCost();
                if( !empty( $additional->id )){
                    $parent = PjStockCost::find( $additional->id ); // Find the cost parent
                    if( !$parent ) $parent = new PjStockCost(); // Otherwise create new
                }
                // ----------------------------------------------------------
                if( $type ) $parent->type = $type; // Assign type if provided
                $parent->save(); // Save the updates
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Update section stock-parent ID
                // ----------------------------------------------------------
                if( !empty( $parent->id )){
                    $section->pj_stock_cost_parent_id = $parent->id;
                    $section->save();
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Process the additional cost entries
                // ----------------------------------------------------------
                $entries = collect(); // List containing all new entries
                if( !empty( $additional->entries )){
                    foreach( $additional->entries as $entry ){
                        // --------------------------------------------------
                        $entry = (object) $entry;
                        // --------------------------------------------------
                        // $emptyName = empty( $entry->name );
                        // $emptyMemo = empty( $entry->memo );
                        // $emptyValue = empty( $entry->value );
                        // if( $emptyName && $emptyMemo && $emptyValue ) continue;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $row = new PjStockCostRow();
                        if( !empty( $entry->id )){
                            $row = PjStockCostRow::find( $entry->id );
                            if( !$row ) $row = new PjStockCostRow();
                        }
                        // --------------------------------------------------
                        $row->pj_stock_cost_parent_id = $parent->id;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Fields
                        // --------------------------------------------------
                        $fields = collect([ 'name', 'value', 'memo' ]);
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Apply the updates
                        // --------------------------------------------------
                        foreach( $fields as $field ){
                            if( isset( $entry->{ $field })){
                                $row->{ $field } = $entry->{ $field };
                            }
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $row->save(); // Save the updates
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Push entry ID to the list
                        // --------------------------------------------------
                        if( !empty( $row->id )) $entries->push( $row->id );
                        // --------------------------------------------------
                    }
                }
                // ----------------------------------------------------------

                // if( 'register' == $type ) dd( $entries->contains( 285 ));

                // ----------------------------------------------------------
                // Validate all additional stock entries
                // ----------------------------------------------------------
                $rows = PjStockCostRow::where( 'pj_stock_cost_parent_id', $parent->id )->get();
                // dd( $rows );
                $rows->each( function( $row ) use( $entries ){
                    // ------------------------------------------------------
                    // If this entry ID is not in the list, delete it
                    // ------------------------------------------------------
                    if( !empty( $row->id ) && !$entries->contains( $row->id )){
                        $row->delete();
                    }
                    // ------------------------------------------------------
                    // If this entry has empty values, delete it
                    // ------------------------------------------------------
                    if( empty( $row->id ) && empty( $row->name ) && empty( $row->value )){
                        $row->delete();
                    }
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sheet sale updates
    // ----------------------------------------------------------------------
    private function get_sheet_sale_updates( $sale ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $sale = (object) $sale;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'sales_divisions', 'unit_average_area', 'rate_of_return',
            'sales_unit_price', 'unit_average_price'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $sale, $field )){
            $updates->{ $field } = $sale->{ $field };
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sale calculator updates
    // ----------------------------------------------------------------------
    private function get_sale_calculator_updates( $calculator ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $calculator = (object) $calculator;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'sales_divisions', 'unit_average_area', 'rate_of_return',
            'sales_unit_price', 'unit_average_price'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $calculator, $field )){
            $updates->{ $field } = $calculator->{ $field };
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sale plan updates
    // ----------------------------------------------------------------------
    private function get_sale_plan_updates( $plan ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $plan = (object) $plan;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'tab_index', 'plan_name', 'plan_creator', 'plan_memo', 'export',
            'reference_area_total', 'planned_area_total', 'unit_selling_price_total',
            'gross_profit_plan', 'gross_profit_total_plan', 'created_at'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $plan, $field )){
            $updates->{ $field } = $plan->{ $field };
            if( 'created_at' == $field ){
                $updates->created_at = Carbon::parse( $plan->created_at );
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get sale stock updates
    // ----------------------------------------------------------------------
    private function get_plan_section_updates( $section ){
        // ------------------------------------------------------------------
        $updates = new \stdClass; $section = (object) $section;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Field list
        // ------------------------------------------------------------------
        $fields = collect([
            'divisions_number', 'reference_area', 'planned_area', 'unit_selling_price',
            'unit_price', 'brokerage_fee', 'brokerage_fee_type'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Compose the update dataset
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        foreach( $fields as $field ) if( property_exists( $section, $field )){
            $updates->{ $field } = $section->{ $field };
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return (array) $updates;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Create sheet, sheet-checklist, sheet-stock, sheet-sales template
    // Template is used in Vue binding when creating new sheet
    // ----------------------------------------------------------------------
    private function create_sheet_template(){
        // ------------------------------------------------------------------
        // Project sheet
        // ------------------------------------------------------------------
        $sheet = factory( PjSheet::class )->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sheet checklist
        // ------------------------------------------------------------------
        $sheet->checklist = factory( PjChecklist::class )->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sheet stock
        // ------------------------------------------------------------------
        $stock = factory( PjStock::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock procurements
        // ------------------------------------------------------------------
        $stock->procurements = factory( PjStockProcurement::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock registers
        // ------------------------------------------------------------------
        $stock->registers = factory( PjStockRegister::class )->state('init')->make();
        $stock->registers->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock finances
        // ------------------------------------------------------------------
        $stock->finances = factory( PjStockFinance::class )->state('init')->make();
        $stock->finances->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock taxes
        // ------------------------------------------------------------------
        $stock->taxes = factory( PjStockTax::class )->state('init')->make();
        $stock->taxes->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock constructions
        // ------------------------------------------------------------------
        $stock->constructions = factory( PjStockConstruction::class )->state('init')->make();
        $stock->constructions->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock surveys
        // ------------------------------------------------------------------
        $stock->surveys = factory( PjStockSurvey::class )->state('init')->make();
        $stock->surveys->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Stock others
        // ------------------------------------------------------------------
        $stock->others = factory( PjStockOther::class )->state('init')->make();
        $stock->others->additional = factory( PjStockCost::class )->state('init')->make();
        // ------------------------------------------------------------------
        // Assign the stock
        // ------------------------------------------------------------------
        $sheet->stock = $stock;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sheet sale
        // ------------------------------------------------------------------
        $sale = factory( PjSale::class )->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $sale->calculators = factory( PjSaleCalculator::class, 2 )->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sale plans
        // ------------------------------------------------------------------
        $sale->plans = factory( PjSalePlan::class, 1 )->state('init')->make();
        $sale->plans->each( function( $plan, $planIndex ){
            // --------------------------------------------------------------
            $index = $planIndex + 1;
            $plan->active = !$planIndex;
            $plan->plan_name = "Plan {$index}";
            $plan->tab_index = $index;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Plan sections
            // --------------------------------------------------------------
            $plan->sections = factory( PjSalePlanSection::class, 1 )->state('init')->make();
            $plan->sections->each( function( $section, $sectionIndex ){
                $section->divisions_number = $sectionIndex +1;
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
        $sheet->sale = $sale;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        return $sheet;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Create sale-plan, plan-section template
    // Template is used in Vue binding when creating new sale-plan
    // ----------------------------------------------------------------------
    private function create_plan_template(){
        // ------------------------------------------------------------------
        $plan = factory( PjSalePlan::class )->state('init')->make();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Plan sections
        // ------------------------------------------------------------------
        $plan->sections = factory( PjSalePlanSection::class, 1 )->state('init')->make();
        $plan->sections->each( function( $section, $sectionIndex ){
            $section->divisions_number = $sectionIndex +1;
        });
        // ------------------------------------------------------------------s

        // ------------------------------------------------------------------
        return $plan;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get project data
    // ----------------------------------------------------------------------
    private function get_project( $projectID ){
        // ------------------------------------------------------------------
        $query = Project::with('question');
        $project = $query->where( 'id', $projectID )->first();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Format project estimated closing date
        // ------------------------------------------------------------------
        if( !empty( $project->estimated_closing_date )){
            $closing = Carbon::parse( $project->estimated_closing_date );
            $project->estimated_closing_date = $closing->format('Y/m/d');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Format project approval request time
        // ------------------------------------------------------------------
        if( !empty( $project->request_time )){
            $carbon = Carbon::parse( $project->request_time );
            $project->request_time = $carbon->format('Y/m/d H:m');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $project;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get project additional Q&A questions
    // ----------------------------------------------------------------------
    private function get_additional_questions( $projectID ){
        // ------------------------------------------------------------------
        $query = OtherAdditionalQaCategory::select( 'id', 'name' );
        $query = $query->with([ 'questions' => function( $question ) use( $projectID ){
            // --------------------------------------------------------------
            $question->where( 'status', true );
            $question->select( 'id', 'category_id', 'choices', 'question', 'input_type' );
            // --------------------------------------------------------------
            // Add relation to Q&A answer
            // --------------------------------------------------------------
            $question->with([ 'answer' => function( $answer ) use( $projectID ){
                $answer->where( 'project_id', $projectID );
                $answer->select( 'id', 'project_id', 'question_id', 'answer' );
            }]);
            // --------------------------------------------------------------
        }]);
        // ------------------------------------------------------------------
        $result = $query->orderBy( 'order', 'asc' )->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $counter = 1;
        foreach( $result as $category ){
            if( isset( $category->questions ) && !$category->questions->isEmpty()){
                // ----------------------------------------------------------
                foreach( $category->questions as $question ){
                    $question->index = $counter;
                    $counter++;
                }
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $result;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Create a flat map of question and answer pair
    // ----------------------------------------------------------------------
    private function map_additional_answers( $additionals ){
        $map = collect([]);
        // ------------------------------------------------------------------
        if( $additionals && !$additionals->isEmpty()){
            $additionals->each( function( $category ) use( $map ){
                // ----------------------------------------------------------
                if( isset( $category->questions ) && !$category->questions->isEmpty()){
                    $category->questions->each( function( $question ) use( $map ){
                        // --------------------------------------------------
                        $query = new \stdClass;
                        // --------------------------------------------------
                        if( !empty( $question->id ) && !empty( $question->input_type )){
                            $query->question = (object)[
                                'id' => $question->id,
                                'type' => $question->input_type
                            ];
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // If this question already has an answer
                        // --------------------------------------------------
                        if( !empty( $question->answer )){
                            $answer = $question->answer;
                            // ----------------------------------------------
                            $query->id = $answer->id;
                            $query->answer = $answer->answer;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // If question is checkbox type (type 2)
                            // Building the vue model
                            // ----------------------------------------------
                            if( 2 == $question->input_type && !empty( $question->options )){
                                // ------------------------------------------
                                $query->answer = collect([]);
                                $answers = collect( $answer->answer );
                                $options = collect( $question->options );
                                // ------------------------------------------
                                $options->each( function( $option ) use( $query, $answers ){
                                    $checked = false;
                                    if( $answers->contains( $option )) $checked = true;
                                    $query->answer->push( (object)[
                                        'label' => $option, 'checked' => $checked
                                    ]);
                                });
                                // ------------------------------------------
                            }
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Otherwise if it does not
                        // --------------------------------------------------
                        else {
                            // ----------------------------------------------
                            $query->id = null;
                            $query->answer = '';
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // If question is checkbox type (type 2)
                            // Building the vue model
                            // ----------------------------------------------
                            if( 2 == $question->input_type && !empty( $question->options )){
                                // ------------------------------------------
                                $query->answer = collect([]);
                                $options = collect( $question->options );
                                // ------------------------------------------
                                $options->each( function( $option ) use( $query ){
                                    $query->answer->push( (object)[
                                        'label' => $option, 'checked' => false
                                    ]);
                                });
                            }
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // $map[ question.id ] = query
                        $map->put( $question->id, $query );
                        // --------------------------------------------------
                    });
                }
                // ----------------------------------------------------------
            });
        };
        // ------------------------------------------------------------------
        return $map;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get project's sheets with all corresponding relationships
    // ----------------------------------------------------------------------
    private function get_project_sheets( $projectID, $sheetID = null ){
        // ------------------------------------------------------------------
        $relations = collect([ 'checklist', 'stock', 'sale' ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sheet expense/stock relations
        // ------------------------------------------------------------------
        $relations = $relations->concat([
            'stock.procurements',
            'stock.registers.additional.entries',
            'stock.finances.additional.entries',
            'stock.taxes.additional.entries',
            'stock.constructions.additional.entries',
            'stock.surveys.additional.entries',
            'stock.others.additional.entries'
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sheet sale relations
        // ------------------------------------------------------------------
        $relations = $relations->concat([ 
            'sale.calculators', 'sale.plans', 'sale.plans.sections' 
        ]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $query = PjSheet::where( 'project_id', $projectID );
        if( $sheetID ) $query = $query->where( 'id', $sheetID );
        // ------------------------------------------------------------------
        $query = $query->with( $relations->all())->orderBy( 'tab_index', 'asc' );
        // ------------------------------------------------------------------
        if( $sheetID ) return $query->first();
        return $query->get();
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Cascade delete stock, stock-additional parent and entries
    // ----------------------------------------------------------------------
    private function delete_sheet_stock_section( $section ){
        if( $section ){
            // --------------------------------------------------------------
            if( !empty( $section->additional )){
                $additional = $section->additional;
                // ----------------------------------------------------------
                if( !empty( $additional->entries )){
                    $additional->entries->each( function( $entry ){
                        $entry->delete();
                    });
                }
                // ----------------------------------------------------------
                $section->delete(); // Delete the section first
                $additional->delete(); // And then the additional
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            $section->delete();
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete sheet by ID.
    // Cascade deletion to the nested relations
    // ----------------------------------------------------------------------
    private function delete_project_sheet( $sheetID, $projectID ){
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $project = Project::find( $projectID );
        if( !$project ){
            $response->status = 'error';
            $response->error = 'project-not-found';
            return $response;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( $project->approval_request && 1 !== $project->approval_request ){
            $response->status = 'error';
            $response->error = 'approval-request';
            return $response;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $sheet = $this->get_project_sheets( $projectID, $sheetID );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( $sheet ){
            // --------------------------------------------------------------
            // Check whether sheet has 'reflecting-to-budget' on or not
            // --------------------------------------------------------------
            if( !empty( $sheet->is_reflecting_in_budget ) && $sheet->is_reflecting_in_budget ){
                $response->status = 'error';
                $response->error = 'reflecting-to-budget';
                return $response;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete sheet checklist
            // --------------------------------------------------------------
            if( !empty( $sheet->checklist )) $sheet->checklist->delete();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete sheet stock/purchasing
            // --------------------------------------------------------------
            if( !empty( $sheet->stock )){
                $stock = $sheet->stock;
                // ----------------------------------------------------------
                if( !empty( $stock->procurements )) $this->delete_sheet_stock_section( $stock->procurements );
                if( !empty( $stock->registers )) $this->delete_sheet_stock_section( $stock->registers );
                if( !empty( $stock->finances )) $this->delete_sheet_stock_section( $stock->finances );
                if( !empty( $stock->taxes )) $this->delete_sheet_stock_section( $stock->taxes );
                if( !empty( $stock->constructions )) $this->delete_sheet_stock_section( $stock->constructions );
                if( !empty( $stock->surveys )) $this->delete_sheet_stock_section( $stock->surveys );
                if( !empty( $stock->others )) $this->delete_sheet_stock_section( $stock->others );
                // ----------------------------------------------------------
                $stock->delete();
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Delete sheet sale
            // --------------------------------------------------------------
            if( !empty( $sheet->sale )){
                $sale = $sheet->sale;
                // ----------------------------------------------------------
                // Delete sale plans and cascade to each sale-plan's section
                // ----------------------------------------------------------
                if( !$sale->plans->isEmpty()) $sale->plans->each( function( $plan ){
                    if( isset( $plan->sections ) && !$plan->sections->isEmpty()){
                        // --------------------------------------------------
                        $plan->sections->each( function( $section ){
                            $id = $section->id;
                            $section->delete();
                        });
                        // --------------------------------------------------
                        $plan->delete();
                        // --------------------------------------------------
                    }
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $sale->delete();
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $sheet->delete(); // Delete sheet
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $response;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete sale plan by ID.
    // Cascade deletion to the nested relations
    // ----------------------------------------------------------------------
    private function delete_sale_plan( $planID ){
        // ------------------------------------------------------------------
        $relations = collect([ 'sections' ]);
        $plan = PjSalePlan::where( 'id', $planID )->with( $relations->all() )->first();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( $plan ){
            // --------------------------------------------------------------
            // Delete plan sections
            // --------------------------------------------------------------
            if( !$plan->sections->isEmpty()){
                $plan->sections->each( function( $section ){
                    $section->delete();
                });
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $plan->delete(); // Delete plan
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete sale plan section by ID.
    // ----------------------------------------------------------------------
    private function delete_sale_plan_section( $sectionID ){
        // ------------------------------------------------------------------
        $section = PjSalePlanSection::find( $sectionID );
        if( $section ) $section->delete();
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get alert message
    // ----------------------------------------------------------------------
    private function get_alert( $status = 'success', $type = 'create' ){
        // ------------------------------------------------------------------
        $alert = new \stdClass;
        $statuses = collect([ 'success', 'error' ]);
        $types = collect([ 'create', 'update', 'delete' ]);
        // ------------------------------------------------------------------
        if( $statuses->contains( $status )){
            // --------------------------------------------------------------
            $alert->icon = $status;
            $alert->heading = __("label.{$status}");
            // --------------------------------------------------------------
            if( $types->contains( $type )){
                $prefix = 'success' == $status ? 'success' : 'failed';
                $alert->text = __("label.{$prefix}_{$type}_message");
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        return $alert;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get PjExpense section data
    // ----------------------------------------------------------------------
    private function get_expense_sections( $projectID ){
        // ------------------------------------------------------------------
        $result = collect();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $project = Project::find( $projectID );
        $expense = Expense::where( 'project_id', $projectID )->first();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // $expenseRows = collect();
        // if( !empty( $expense->id )){ // Get expense rows
        //     $expenseRows = ExpenseRow::where( 'pj_expense_id', $expense->id )->get();
        // }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Get sheet data where is_reflecting_in_budget is 1
        // ------------------------------------------------------------------
        $query = Project::find( $project->id )->withBudgetSheet();
        $sheet = $query->with([
            'stock.procurements',
            'stock.registers.stockCost.rows',
            'stock.taxes.stockCost.rows',
            'stock.constructions.stockCost.rows',
            'stock.surveys.stockCost.rows',
            'stock.others.stockCost.rows',
            'stock.finances'
        ])->first();
        $procurement = $sheet->stock->procurements ?? null;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Get data from purchase
        // ------------------------------------------------------------------
        $purchase = $project->purchase()->with(
            'targets.contract.deposits',
            'targets.contract.mediations',
            'targets.purchase_target_contractors.contractor'
        )->first();
        $purchaseTargets = $purchase->targets ?? [];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        //  Get Master Finance
        // ------------------------------------------------------------------
        $query = MasFinance::where( 'project_id', $project->id );
        $finance = $query->with( 'expenses' )->first();
        $financeExpenses = $finance->expenses ?? collect();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Get section data dynamically by the screen index
        // ------------------------------------------------------------------
        $getSection = function( $screenIndex ) use( $expense ){
            // --------------------------------------------------------------
            $section = new \stdClass;
            $section->rows = collect();
            // --------------------------------------------------------------
            // Get decided data from project expense
            // --------------------------------------------------------------
            $fields = array( 
                'id', 'pj_expense_id', 'additional_id', 'name', 'decided', 
                'screen_name', 'screen_index', 'data_kind'
            );
            // --------------------------------------------------------------
            if( !empty( $expense->id )){
                $screen = strtolower( "screen_{$screenIndex}" );
                $query = ExpenseRow::select( $fields )->where( 'pj_expense_id', $expense->id );
                $section->rows = $query->where( 'screen_name', $screen )->get();
            }
            // --------------------------------------------------------------
            return $section;
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Section A
        // ------------------------------------------------------------------
        $section = new \stdClass;
        $section->decided = null;
        $result->put( 'a', $section );
        // ------------------------------------------------------------------
        foreach( $purchaseTargets as $target ){ // Get decided amount from one target
            $section->decided = (int) $target->contract->contract_price_total ?? 0; break;
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Section B
        // ------------------------------------------------------------------
        $section = new \stdClass;
        $section->decided = collect();
        $result->put( 'b', $section );
        // ------------------------------------------------------------------
        // Get decided data from purchase target mediation
        // ------------------------------------------------------------------
        foreach( $purchaseTargets as $target ){
            $mediations = $target->contract->mediations ?? [];
            // --------------------------------------------------------------
            if( empty( $mediations )) $section->decided->push( 0 );
            else foreach( $mediations as $mediation ){
                if( !empty( $mediation->reward )) $section->decided->push( $mediation->reward );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Section C
        // ------------------------------------------------------------------
        $result->put( 'c', $getSection( 'c' ));
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Section D
        // ------------------------------------------------------------------
        $section = new \stdClass;
        $section->rows = collect();
        $result->put( 'd', $section );
        // ------------------------------------------------------------------
        // Get finance data
        // ------------------------------------------------------------------
        $getFinance = function( $category, $name, $group ) use( $expense, $financeExpenses ){
            // --------------------------------------------------------------
            $filtered = $financeExpenses->where( 'category_index', $category )->where( 'display_name', $name );
            if( $filtered->isNotEmpty()){
                $filtered->map( function( $entry ) use( $group ){
                    if( $group ) $entry['group'] = $group;
                });
                return $filtered->values();
            }
            // --------------------------------------------------------------
            return array( array( 'id' => '', 'pj_expense_id' => $expense->id ?? '', 'decided' => '' ));
            // --------------------------------------------------------------
        };
        // ------------------------------------------------------------------
        // Finance expenses
        // ------------------------------------------------------------------
        $section->rows = $section->rows->concat( $getFinance( 'ア', '金利負担', 'interest' ));       // Interest rate
        $section->rows = $section->rows->concat( $getFinance( 'イ', '銀行手数料', 'banking' ));     // Banking fee
        $section->rows = $section->rows->concat( $getFinance( 'ウ', '印紙(手形)', 'stamp' ));     // Stamp
        // ------------------------------------------------------------------
        // Additional expenses
        // ------------------------------------------------------------------
        $additionals = $financeExpenses->whereNotIn( 'category_index', [ 'ア', 'イ', 'ウ' ])->groupBy('category_index');
        foreach( $additionals as $entry ){
            $entry->map( function( $row ){ $row['additional'] = true; });
            $section->rows = $section->rows->concat( $entry );
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Section E
        // ------------------------------------------------------------------
        $result->put( 'e', $getSection( 'e' ));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Section F
        // ------------------------------------------------------------------
        $result->put( 'f', $getSection( 'f' ));
        // ------------------------------------------------------------------
        
        // ------------------------------------------------------------------
        // Section G
        // ------------------------------------------------------------------
        $result->put( 'g', $getSection( 'g' ));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Section H
        // ------------------------------------------------------------------
        $result->put( 'h', $getSection( 'h' ));
        // ------------------------------------------------------------------

        
        // ------------------------------------------------------------------
        // dd( $result );
        return $result;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    public function index( Request $request ){
        // ------------------------------------------------------------------
        $param = (object) $request->route()->parameters;
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->new = new \stdClass;
        $data->user = Auth::user();
        $data->locale = App::getLocale() ?? config('app.locale');
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project data
        // ------------------------------------------------------------------
        $project = $this->get_project( $param->project );
        if( !$project ) return abort(404);
        $data->project = $project;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Get sections from expense module
        // ------------------------------------------------------------------
        $data->expense = $this->get_expense_sections( $project->id );
        // dd( $data->expense );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Area types
        // ------------------------------------------------------------------
        $query = MasterValue::where( 'masterdeleted', 0 );
        $data->areaTypes = $query->where( 'type', 'usedistrict' )->get();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Other additional questions
        // ------------------------------------------------------------------
        $data->additional = $this->get_additional_questions( $param->project );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project sheets
        // ------------------------------------------------------------------
        $data->sheets = $this->get_project_sheets( $project->id );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Global tax
        // ------------------------------------------------------------------
        $data->tax = config('const.JAPANESE_TAX');
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Format purchasing tax date
        // ------------------------------------------------------------------
        $data->sheets->each( function( $sheet ){
            if( !empty( $sheet->stock->registers )){
                $registration = $sheet->stock->registers;
                // ----------------------------------------------------------
                if( !empty( $registration->fixed_assets_tax_date )){
                    $date = Carbon::parse( $registration->fixed_assets_tax_date );
                    $registration->fixed_assets_tax_date = $date->format('Y/m/d');
                }
                // ----------------------------------------------------------
            }
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Create new sheet template
        // ------------------------------------------------------------------
        $data->new->plan = $this->create_plan_template();
        $data->new->sheet = $this->create_sheet_template();
        $data->new->section = factory( PjSalePlanSection::class )->state('init')->make();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Get oldest contract date from purchase-contract
        // https://bit.ly/2xrv06D
        // ------------------------------------------------------------------
        $contractDate = null;
        $query = PjPurchase::with([ 'targets', 'targets.contract' ]);
        $purchases = $query->where( 'project_id', $param->project )->get();
        $purchases->each( function( $purchase ) use( $contractDate ){
            $purchase->targets->each( function( $target ) use( $contractDate ){
                if( isset( $target->contract, $target->contract->contract_date )){
                    // ------------------------------------------------------
                    $date = Carbon::parse( $target->contract->contract_date );
                    if( $date && ( !$contractDate || $date->lessThan( $contractDate ))){
                        $contractDate = $date;
                    }
                    // ------------------------------------------------------
                }
            });
        });
        // ------------------------------------------------------------------
        if( $contractDate ) $contractDate = $contractDate->format('Y/m/d');
        $data->contract = $contractDate;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Get mas-finance-unit and mas-finance-unit-money of the current project
        // ------------------------------------------------------------------
        $data->loanMoney = 0; // https://bit.ly/33Tkot2
        $data->loanRatio = 0; // https://bit.ly/2QXZMKZ
        // ------------------------------------------------------------------
        $finance = MasFinance::where( 'project_id', $param->project )->first();
        if( $finance ){
            // --------------------------------------------------------------
            $query = MasFinanceUnit::with( 'moneys' );
            $unit = $query->where( 'mas_finance_id', $finance->id )->first();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get sum of loan-money from mas_finance_unit_money
            // of the first mas_finance_unit of the project
            // https://bit.ly/33Tkot2
            // --------------------------------------------------------------
            if( $unit && !$unit->moneys->isEmpty()){
                $unit->moneys->each( function( $money ) use( $data ){
                    if( !empty( $money->loan_money )){
                        $data->loanMoney += $money->loan_money;
                    }
                });
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get loan-ratio of the first mas_finance_unit of the project
            // https://bit.ly/2QXZMKZ
            // --------------------------------------------------------------
            if( $unit && !empty( $unit->loan_ratio )){
                $data->loanRatio = $unit->loan_ratio;
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project inspection request
        // ------------------------------------------------------------------
        $query = Inspection::with([ 'user' ])
            ->where( 'kind', 1 )
            ->where( 'active', true )
            ->where( 'project_id', $project->id )
            ->orderBy( 'created_at', 'desc' );
        $data->inspection = $query->first();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Master sheet manager/calculate data
        // ------------------------------------------------------------------
        $data->sheetValues = SheetValues::with( 'master' )->get();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        $title = __('project.sheet.title');
        $data->page_title = "{$title}::{$project->title}";
        $data->form_action = route('project.sheet.update', [ 'project' => $project->id ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // dd( $data->project );
        return view( 'backend.project.sheet.form', (array) $data );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Update PJ Sheet form
    // ----------------------------------------------------------------------
    public function update( Request $request ){
        // ------------------------------------------------------------------
        $data = (object) $request->all();
        if( !isset( $data->project )) return response()->json( null );
        // ------------------------------------------------------------------
        $dataProject = (object) $data->project;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Find the project
        // ------------------------------------------------------------------
        $project = Project::find( $dataProject->id );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Return response error if project does not exist
        // ------------------------------------------------------------------
        if( !$project ){
            $response->status = 'error';
            $response->error = 'invalid-project';
            return response()->json( $response );
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Save the project updates
        // ------------------------------------------------------------------
        if( !empty( $dataProject )){
            // --------------------------------------------------------------
            $updates = $this->get_project_updates( $dataProject );
            foreach( $updates as $field => $update ) if( isset( $project->{ $field })){
                $project->{ $field } = $update;
            }; $project->save();
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Save the basic QA updates
        // ------------------------------------------------------------------
        if( !empty( $dataProject->question )){
            // --------------------------------------------------------------
            $dataQuestion = (object) $dataProject->question;
            $updates = $this->get_question_basic_updates( $dataQuestion );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Find the basic QA entry
            // --------------------------------------------------------------
            $query = PjBasicQA::where( 'project_id', $project->id );
            $question = $query->where( 'id', $dataQuestion->id )->first();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // If it doesnt exists, create new one
            // --------------------------------------------------------------
            if( !$question ){
                $question = new PjBasicQa();
                $question->project_id = $project->id;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Apply the updates
            // --------------------------------------------------------------
            foreach( $updates as $field => $update ){
                $question->{ $field } = $update;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $question->save(); // Save the updates
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Save the additional QA updates, wrap in transaction
        // ------------------------------------------------------------------
        if( !empty( $data->additional )) DB::transaction( function() use( $data, $project ){
            // --------------------------------------------------------------
            $categories = $data->additional;
            if( !empty( $categories )) foreach( $categories as $category ){
                $category = (object) $category;
                if( !empty( $category->questions )) foreach( $category->questions as $question ){
                    // ------------------------------------------------------
                    $question = (object) $question;
                    if( !empty( $question->answer )){
                        // --------------------------------------------------
                        $model = new PjAdditionalQa();
                        $answer = (object) $question->answer;
                        $update = $answer->answer;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // If question is checkbox/radio without choices, remove the answer
                        // --------------------------------------------------
                        $type = $question->input_type;
                        $empty = empty( trim( $question->choices ));
                        if( $empty && ( 1 === $type || 2 === $type )) $update = null;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Find the answer if it has ID, otherwise create new
                        // --------------------------------------------------
                        if( !empty( $answer->id )){
                            $model = PjAdditionalQa::find( $answer->id );
                            if( !$model ) $model = new PjAdditionalQa();
                        } else {
                            $model->project_id = $project->id;
                            $model->question_id = $question->id;
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Save as JSON the answer is an array (Checkbox type)
                        // --------------------------------------------------
                        if( is_array( $update )) $update = json_encode( $update, JSON_UNESCAPED_UNICODE );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $model->answer = $update; // Update the answer
                        $model->save(); // Save the update
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                }
            }
            // --------------------------------------------------------------
        }, 5);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Save the sheets
        // ------------------------------------------------------------------
        $sheets = collect([]);
        if( !empty( $data->sheets )) DB::transaction( function() use( $data, $project, $dataProject, $sheets ){
            foreach( $data->sheets as $index => $sheet ){
                // ----------------------------------------------------------
                $sheet = (object) $sheet;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get the updates
                // ----------------------------------------------------------
                $updates = (object) $this->get_sheet_updates( $sheet );
                $updates->project_id = $project->id;
                $updates->tab_index = $index +1;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Prepare the model, create new if sheet doesn't have ID
                // ----------------------------------------------------------
                $projectSheet = new PjSheet();
                if( !empty( $sheet->id )){
                    $projectSheet = PjSheet::find( $sheet->id );
                    if( !$projectSheet ) $projectSheet = new PjSheet();
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Apply the updates
                // ----------------------------------------------------------
                foreach( (array) $updates as $field => $update ){
                    $projectSheet->{ $field } = $update;
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $projectSheet->save(); // Save the updates
                if( !empty( $projectSheet->id )) $sheets->push( $projectSheet->id );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Update / create sheet checklist
                // ----------------------------------------------------------
                if( !empty( $sheet->checklist )){
                    // ------------------------------------------------------
                    $checklist = (object) $sheet->checklist;
                    $updates = (object) $this->get_sheet_checklist_updates( $checklist, $project );
                    $updates->pj_sheet_id = $projectSheet->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Prepare checklist model, create new if checklist doesn't have ID
                    // ------------------------------------------------------
                    $sheetChecklist = new PjChecklist();
                    if( !empty( $checklist->id )){
                        $sheetChecklist = PjChecklist::find( $checklist->id );
                        if( !$sheetChecklist ) $sheetChecklist = new PjChecklist();
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Apply the updates
                    // ------------------------------------------------------
                    foreach( (array) $updates as $field => $update ){
                        $sheetChecklist->{ $field } = $update;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $sheetChecklist->save(); // Save the updates
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Update / create sheet expense/stock
                // ----------------------------------------------------------
                if( !empty( $sheet->stock )){
                    // ------------------------------------------------------
                    $stock = (object) $sheet->stock;
                    $updates = (object) $this->get_sheet_stock_updates( $stock, $project );
                    $updates->pj_sheet_id = $projectSheet->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Prepare stock model, create new if stock does not have ID
                    // ------------------------------------------------------
                    $sheetStock = new PjStock();
                    if( !empty( $stock->id )){
                        $sheetStock = PjStock::find( $stock->id );
                        if( !$sheetStock ) $sheetStock = new PjStock();
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Apply the updates
                    // ------------------------------------------------------
                    foreach( (array) $updates as $field => $update ){
                        $sheetStock->{ $field } = $update;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $sheetStock->save(); // Save the updates
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock purchase / procurement
                    // ------------------------------------------------------
                    if( !empty( $stock->procurements )){
                        $this->update_stock_section( 'procurement', $stock->procurements, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock registration / register
                    // ------------------------------------------------------
                    if( !empty( $stock->registers )){
                        $this->update_stock_section( 'register', $stock->registers, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock finance
                    // ------------------------------------------------------
                    if( !empty( $stock->finances )){
                        $this->update_stock_section( 'finance', $stock->finances, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock tax
                    // ------------------------------------------------------
                    if( !empty( $stock->taxes )){
                        $this->update_stock_section( 'tax', $stock->taxes, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock construction
                    // ------------------------------------------------------
                    if( !empty( $stock->constructions )){
                        $this->update_stock_section( 'construction', $stock->constructions, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock survey
                    // ------------------------------------------------------
                    if( !empty( $stock->surveys )){
                        $this->update_stock_section( 'survey', $stock->surveys, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create stock other
                    // ------------------------------------------------------
                    if( !empty( $stock->others )){
                        $this->update_stock_section( 'other', $stock->others, $sheetStock, $sheet, $dataProject );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Update / create sheet sale
                // ----------------------------------------------------------
                if( !empty( $sheet->sale )){
                    // ------------------------------------------------------
                    $sale = (object) $sheet->sale;
                    $updates = new \stdClass;
                    $updates->pj_sheet_id = $projectSheet->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Prepare sale model, create new if sale does not have ID
                    // ------------------------------------------------------
                    $sheetSale = new PjSale();
                    if( !empty( $sale->id )){
                        $sheetSale = PjSale::find( $sale->id );
                        if( !$sheetSale ) $sheetSale = new PjSale();
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Apply the updates
                    // ------------------------------------------------------
                    foreach( (array) $updates as $field => $update ){
                        $sheetSale->{ $field } = $update;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $sheetSale->save(); // Save the updates
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create sale calculators
                    // ------------------------------------------------------
                    if( !empty( $sale->calculators )) foreach( $sale->calculators as $calculator ){
                        $calculator = (object) $calculator;
                        $updates = (object) $this->get_sale_calculator_updates( $calculator );
                        $updates->pj_sale_id = $sheetSale->id;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Prepare calculator model, create new if plan does not have ID
                        // --------------------------------------------------
                        $saleCalc = new PjSaleCalculator();
                        if( !empty( $calculator->id )){
                            $saleCalc = PjSaleCalculator::find( $calculator->id );
                            if( !$saleCalc ) $saleCalc = new PjSaleCalculator();
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Apply the updates
                        // --------------------------------------------------
                        foreach( (array) $updates as $field => $update ){
                            $saleCalc->{ $field } = $update;
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $saleCalc->save(); // Save the updates
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Update / create sale plans
                    // ------------------------------------------------------
                    if( !empty( $sale->plans )) foreach( $sale->plans as $planIndex => $plan ){
                        // --------------------------------------------------
                        $plan = (object) $plan;
                        $updates = (object) $this->get_sale_plan_updates( $plan );
                        $updates->pj_sale_id = $sheetSale->id;
                        $updates->tab_index = $planIndex +1;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Prepare plan model, create new if plan does not have ID
                        // --------------------------------------------------
                        $salePlan = new PjSalePlan();
                        if( !empty( $plan->id )){
                            $salePlan = PjSalePlan::find( $plan->id );
                            if( !$salePlan ) $salePlan = new PjSalePlan();
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Apply the updates
                        // --------------------------------------------------
                        foreach( (array) $updates as $field => $update ){
                            $salePlan->{ $field } = $update;
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $salePlan->save(); // Save the updates
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Update / create plan sections
                        // --------------------------------------------------
                        if( !empty( $plan->sections )) foreach( $plan->sections as $sectionIndex => $section ){
                            // ----------------------------------------------
                            $section = (object) $section;
                            $updates = (object) $this->get_plan_section_updates( $section );
                            $updates->pj_sale_plan_id = $salePlan->id;
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Prepare plan model, create new if plan does not have ID
                            // ----------------------------------------------
                            $planSection = new PjSalePlanSection();
                            if( !empty( $section->id )){
                                $planSection = PjSalePlanSection::find( $section->id );
                                if( !$planSection ) $planSection = new PjSalePlanSection();
                            }
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Apply the updates
                            // ----------------------------------------------
                            foreach( (array) $updates as $field => $update ){
                                $planSection->{ $field } = $update;
                            }
                            // ----------------------------------------------

                            // ----------------------------------------------
                            $planSection->save(); // Save the updates
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
        }, 5);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        $response->status = 'success';
        $response->updates = new \stdClass;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project sheets
        // ------------------------------------------------------------------
        $response->updates->sheets = $this->get_project_sheets( $project->id );
        $response->updates->additional = $this->get_additional_questions( $project->id );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response )->setEncodingOptions( JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete a sheet
    // Cascade deletion to all nested relations.
    // ----------------------------------------------------------------------
    public function delete( Request $request ){
        $response = (object) array( 'status' => 'success');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( !empty( $request->sheet )){
            // --------------------------------------------------------------
            $sheet = (int) $request->sheet;
            $project = (int) $request->project;
            // --------------------------------------------------------------
            $result = $this->delete_project_sheet( $sheet, $project );
            $response = (object) array_merge( (array) $response, (array) $result );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        elseif( !empty( $request->plan )){
            $plan = (int) $request->plan;
            $this->delete_sale_plan( $plan );
        }
        // ------------------------------------------------------------------
        elseif( !empty( $request->section )){
            $section = (int) $request->section;
            $this->delete_sale_plan_section( $section );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete additional expense entry
    // ----------------------------------------------------------------------
    public function delete_expense( Request $request ){
        // ------------------------------------------------------------------
        $response = (object) array( 'status' => 'error' );
        $alert = $this->get_alert( 'error', 'delete' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( !empty( $request->id )){
            // --------------------------------------------------------------
            $additional = PjStockCostRow::find( $request->id );
            if( !$additional ) {
                $response->status = 'error';
                $alert = $this->get_alert( 'error', 'delete' );
            }
            // --------------------------------------------------------------
            else {
                // ----------------------------------------------------------
                $additional->delete();
                $response->status = 'success';
                $alert = $this->get_alert( 'success', 'delete' );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->alert = $alert;
        return response()->json( $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Handle report requests
    // ----------------------------------------------------------------------
    public function report( Request $request ){
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------
        $projectID = (int) $request->project;
        $sheetID = (int) $request->sheet;
        $sale = $request->data['sale'];
        $map = collect([]);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $sheet = $this->get_project_sheets( $projectID, $sheetID ); // Get current sheet
        $data->sheet = $sheet;
        // ------------------------------------------------------------------
        $project = $this->get_project( $projectID );
        $data->project = $project;
        // ------------------------------------------------------------------
        $additional = $this->get_additional_questions( $projectID );
        $data->additional = $additional;
        // ------------------------------------------------------------------
        if( $additional && !$additional->isEmpty()){
            $map = $this->map_additional_answers( $additional );
            $data->map = $map;
        }
        // ------------------------------------------------------------------
        $data->sale = $sale;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        if( !empty( $request->report )){
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Generate overall sheet report
            // --------------------------------------------------------------
            if( 'sheet' == $request->report ){
                // ----------------------------------------------------------
                // Generate the report
                // ----------------------------------------------------------
                $filepath = SheetReport::reportSheet( $data );
                $response->report = $filepath;
                $response->filename = basename( $filepath );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Generate sheet checklist report
            // --------------------------------------------------------------
            elseif( 'checklist' == $request->report ){
                // ----------------------------------------------------------
                // Generate the report
                // ----------------------------------------------------------
                $filepath = CheckListReport::reportCheckList( $data );
                $response->report = $filepath;
                $response->filename = "チェックリスト".basename( $filepath );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        return response()->json( (array) $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
