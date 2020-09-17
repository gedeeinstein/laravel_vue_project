<?php

namespace App\Reports;

// --------------------------------------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
// --------------------------------------------------------------------------
use File;
use Carbon\Carbon;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class SheetReportRefactored
{
    // ----------------------------------------------------------------------
    // Create Report Output Of Sheet
    // ----------------------------------------------------------------------
    public static function reportSheet($data){
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $template = 'reports/template/sheet-report.xlsx';
        $spreadsheet = IOFactory::load( $template );
        $worksheet = $spreadsheet->getActiveSheet();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Parameter for retrive sheet and project data
        // ------------------------------------------------------------------
        $sale = (object) $data->sale;
        $sheet = $data->sheet;
        $project = $data->project;
        $expense = $data->sheet->stock;
        $checklist = $data->sheet->checklist;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Data
        // ------------------------------------------------------------------
        $worksheet->getCell( 'A1' )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'A3' )->setValue( $project->title )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'A4' )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'B4' )->setValue( $sheet->name )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'A6' )->setValue( $project->overall_area );
        $worksheet->getCell( 'G6' )->setValue( $checklist->breakthrough_rate );
        $worksheet->getCell( 'J6' )->setValue( $checklist->effective_area );
        $worksheet->getCell( 'A8' )->setValue( $project->fixed_asset_tax_route_value );
        $worksheet->getCell( 'A10' )->getStyle()->getFont()->setBold( true );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for Sheet Selected
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Purchase/Procurement related expenses (仕入予定価格)
        // ------------------------------------------------------------------
        $purchase = $expense->procurements;
        $types = collect([ 1 => '収', 2 => '支', 3 => '無' ]);
        $brokerageFeeType = $types->get( $purchase->brokerage_fee_type );
        // ------------------------------------------------------------------
        $worksheet->getCell( 'A12' )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'C12' )->setValue( $purchase->price );
        $worksheet->getCell( 'A15' )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'C15' )->setValue( $purchase->brokerage_fee );
        $worksheet->getCell( 'E15' )->setValue( $brokerageFeeType );
        $worksheet->getCell( 'G15' )->setValue( $purchase->brokerage_fee_memo );
        // ------------------------------------------------------------------

        
        // ------------------------------------------------------------------
        // Survey related expenses (測量関連費用)
        // ------------------------------------------------------------------
        $survey = $expense->surveys;
        $worksheet->getCell( 'K12' )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'M13' )->setValue( $survey->fixed_survey );
        $worksheet->getCell( 'O13' )->setValue( $survey->fixed_survey_memo );
        $worksheet->getCell( 'M14' )->setValue( $survey->divisional_registration );
        $worksheet->getCell( 'O14' )->setValue( $survey->divisional_registration_memo );
        $worksheet->getCell( 'M15' )->setValue( $survey->boundary_pile_restoration );
        $worksheet->getCell( 'O15' )->setValue( $survey->boundary_pile_restoration_memo );
        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row1 = 16;
        if( !empty( $survey->additional->entries )){
            // --------------------------------------------------------------
            $entries = $survey->additional->entries;
            $worksheet->insertNewRowBefore( $row1, count( $entries ));
            // --------------------------------------------------------------
            foreach( $entries as $key => $entry ){
                $worksheet->mergeCells( "K{$row1}:L{$row1}" )->getCell( "K{$row1}" )->setValue( $entry->name );
                $worksheet->mergeCells( "M{$row1}:N{$row1}" )->getCell( "M{$row1}" )->setValue( $entry->value );
                $worksheet->mergeCells( "O{$row1}:R{$row1}" )->getCell( "O{$row1}" )->setValue( $entry->memo );
                $row1++;
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        // Styling for additional row
        // ------------------------------------------------------------------
        $styleArray = [
            'borders' => [
                'allBorders' => [ 'borderStyle' => Border::BORDER_NONE ],
            ],
        ];
        $worksheet->getStyle( "A16:J{$row1}" )->applyFromArray( $styleArray );
        $worksheet->getStyle( "A16:I{$row1}" )->getFill()->setFillType( Fill::FILL_SOLID )->getStartColor()->setARGB( 'FFFFFF' );
        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell( 'M12' )->setValue( "=SUM(M13:N{$row1})" );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Registration/register related expenses (登記関連費用)
        // ------------------------------------------------------------------
        $row1 = $row1 + 1;
        $registration = $expense->registers;
        $taxDate = Carbon::parse( $registration->fixed_assets_tax_date );
        // ------------------------------------------------------------------
        $worksheet->getCell( 'A'.$row1 )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'C'.( $row1+1 ))->setValue( $registration->transfer_of_ownership );
        $worksheet->getCell( 'E'.( $row1+1 ))->setValue( $registration->transfer_of_ownership_memo );
        $worksheet->getCell( 'C'.( $row1+2 ))->setValue( $registration->mortgage_setting );
        $worksheet->getCell( 'G'.( $row1+2 ))->setValue( $registration->mortgage_setting_plan );
        $worksheet->getCell( 'C'.( $row1+3 ))->setValue( $registration->fixed_assets_tax );
        $worksheet->getCell( 'G'.( $row1+3 ))->setValue( $taxDate->format( 'Y/m/d' ));
        $worksheet->getCell( 'C'.( $row1+4 ))->setValue( $registration->loss );
        $worksheet->getCell( 'E'.( $row1+4 ))->setValue( $registration->loss_memo );
        // ------------------------------------------------------------------
        // Other related expenses (その他)
        // ------------------------------------------------------------------
        $other = $expense->others;
        $worksheet->getCell( 'K'.$row1 )->getStyle()->getFont()->setBold( true );
        $worksheet->getCell( 'M'.( $row1+1 ))->setValue( $other->referral_fee );
        $worksheet->getCell( 'O'.( $row1+1 ))->setValue( $other->referral_fee_memo );
        $worksheet->getCell( 'M'.( $row1+2 ))->setValue( $other->eviction_fee );
        $worksheet->getCell( 'O'.( $row1+2 ))->setValue( $other->eviction_fee_memo );
        $worksheet->getCell( 'M'.( $row1+3 ))->setValue( $other->water_supply_subscription );
        $worksheet->getCell( 'O'.( $row1+3 ))->setValue( $other->water_supply_subscription_memo );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        // $row2 = $row1 + 5;
        // if (count($expense['registers']['additional']['entries']) > 0 || count($expense['others']['additional']['entries']) > 0) {
        //     if (count($expense['registers']['additional']['entries']) > (count($expense['others']['additional']['entries']) - 1)) {
        //         $worksheet->insertNewRowBefore($row2, count($expense['registers']['additional']['entries']));
        //         foreach ($expense['registers']['additional']['entries'] as $key => $value) {
        //             $worksheet->mergeCells('A'.$row2.':'.'B'.$row2)->getCell('A' . $row2)->setValue($value['name']);
        //             $worksheet->mergeCells('C'.$row2.':'.'D'.$row2)->getCell('C' . $row2)->setValue($value['value']);
        //             $worksheet->mergeCells('E'.$row2.':'.'I'.$row2)->getCell('E' . $row2)->setValue($value['memo']);
        //             if (!empty($expense['others']['additional']['entries'][$key])) {
        //                 $row2--;
        //                 $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K' . $row2)->setValue($expense['others']['additional']['entries'][$key]['name']);
        //                 $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M' . $row2)->setValue($expense['others']['additional']['entries'][$key]['value']);
        //                 $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O' . $row2)->setValue($expense['others']['additional']['entries'][$key]['memo']);
        //                 $row2++;

        //                 // ------------------------------------------------------------------
        //                 // Styling for additional row
        //                 // ------------------------------------------------------------------
        //                 $styleArray = [
        //                     'borders' => [
        //                         'left' => [
        //                             'borderStyle' => Border::BORDER_THIN,
        //                         ],
        //                         'right' => [
        //                             'borderStyle' => Border::BORDER_THIN,
        //                         ],
        //                         'bottom' => [
        //                             'borderStyle' => Border::BORDER_THIN,
        //                         ],
        //                     ],
        //                 ];
        //                 $worksheet->getStyle('K'.$row1.':'.'L'.$row2)->applyFromArray($styleArray);
        //                 $worksheet->getStyle('M'.$row1.':'.'N'.$row2)->applyFromArray($styleArray);
        //                 $worksheet->getStyle('O'.$row1.':'.'R'.$row2)->applyFromArray($styleArray);
        //             } else {
        //                 $row2--;
        //                 $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K'.$row2)->setValue('');
        //                 $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M'.$row2)->setValue('');
        //                 $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O'.$row2)->setValue('');
        //                 $row2++;
        //             }
        //             $row2++;
        //         }

        //     } else {
        //         if (count($expense['others']['additional']['entries']) > 1) {
        //             $worksheet->insertNewRowBefore($row2, count($expense['others']['additional']['entries']) - 1);
        //         }
        //         $row2--;
        //         foreach ($expense['others']['additional']['entries'] as $key => $value) {
        //             $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K' . $row2)->setValue($value['name']);
        //             $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M' . $row2)->setValue($value['value']);
        //             $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O' . $row2)->setValue($value['memo']);

        //             // ------------------------------------------------------------------
        //             // Styling for additional row
        //             // ------------------------------------------------------------------
        //             $styleArray = [
        //                 'borders' => [
        //                     'left' => [
        //                         'borderStyle' => Border::BORDER_THIN,
        //                     ],
        //                     'right' => [
        //                         'borderStyle' => Border::BORDER_THIN,
        //                     ],
        //                     'bottom' => [
        //                         'borderStyle' => Border::BORDER_THIN,
        //                     ],
        //                 ],
        //             ];
        //             $worksheet->getStyle('K'.$row1.':'.'L'.$row2)->applyFromArray($styleArray);
        //             $worksheet->getStyle('M'.$row1.':'.'N'.$row2)->applyFromArray($styleArray);
        //             $worksheet->getStyle('O'.$row1.':'.'R'.$row2)->applyFromArray($styleArray);
        //             $row2++;
        //             if (!empty($expense['registers']['additional']['entries'][$key])) {
        //                 $worksheet->mergeCells('A'.$row2.':'.'B'.$row2)->getCell('A' . $row2)->setValue($expense['registers']['additional']['entries'][$key]['name']);
        //                 $worksheet->mergeCells('C'.$row2.':'.'D'.$row2)->getCell('C' . $row2)->setValue($expense['registers']['additional']['entries'][$key]['value']);
        //                 $worksheet->mergeCells('E'.$row2.':'.'I'.$row2)->getCell('E' . $row2)->setValue($expense['registers']['additional']['entries'][$key]['memo']);
        //             }
        //         }
        //     }
        // }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row2 = $row1 + 5;
        if( !empty( $registration->additional->entries ) || !empty( $other->additional->entries )){
            // --------------------------------------------------------------
            $additionalOther = $other->additional->entries;
            $additionalRegistration = $registration->additional->entries;
            // --------------------------------------------------------------
            if( count( $additionalRegistration ) > ( count( $additionalOther ) - 1 )){
                $worksheet->insertNewRowBefore( $row2, count( $additionalRegistration ));
                foreach( $additionalRegistration as $key => $entry ){
                    // ------------------------------------------------------
                    $worksheet->mergeCells( "A{$row2}:B{$row2}" )->getCell( "A{$row2}" )->setValue( $entry->name );
                    $worksheet->mergeCells( "C{$row2}:D{$row2}" )->getCell( "C{$row2}" )->setValue( $entry->value );
                    $worksheet->mergeCells( "E{$row2}:I{$row2}" )->getCell( "E{$row2}" )->setValue( $entry->memo );
                    // ------------------------------------------------------
                    if( !empty( $additionalOther[ $key ])){
                        $row2--;
                        $entryOther = $additionalOther[ $key ];
                        $worksheet->mergeCells( "K{$row2}:L{$row2}" )->getCell( "K{$row2}" )->setValue( $entryOther->name );
                        $worksheet->mergeCells( "M{$row2}:N{$row2}" )->getCell( "M{$row2}" )->setValue( $entryOther->value );
                        $worksheet->mergeCells( "O{$row2}:R{$row2}" )->getCell( "O{$row2}" )->setValue( $entryOther->memo );
                        $row2++;
                        // --------------------------------------------------
                        // Styling for additional row
                        // --------------------------------------------------
                        $styleArray = [
                            'borders' => [
                                'left'   => [ 'borderStyle' => Border::BORDER_THIN ],
                                'right'  => [ 'borderStyle' => Border::BORDER_THIN ],
                                'bottom' => [ 'borderStyle' => Border::BORDER_THIN ],
                            ],
                        ];
                        // --------------------------------------------------
                        $worksheet->getStyle( "K{$row1}:L{$row2}" )->applyFromArray( $styleArray );
                        $worksheet->getStyle( "M{$row1}:N{$row2}" )->applyFromArray( $styleArray );
                        $worksheet->getStyle( "O{$row1}:R{$row2}" )->applyFromArray( $styleArray );
                        // --------------------------------------------------
                    } else {
                        // --------------------------------------------------
                        $row2--;
                        $worksheet->mergeCells( "K{$row2}:L{$row2}" )->getCell( "K{$row2}" )->setValue('');
                        $worksheet->mergeCells( "M{$row2}:N{$row2}" )->getCell( "M{$row2}" )->setValue('');
                        $worksheet->mergeCells( "O{$row2}:R{$row2}" )->getCell( "O{$row2}" )->setValue('');
                        $row2++;
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------
                    $row2++;
                    // ------------------------------------------------------
                }
            } else {
                // ----------------------------------------------------------
                if( count( $additionalOther ) > 1 ){
                    $worksheet->insertNewRowBefore( $row2, count( $additionalOther ) - 1);
                }
                // ----------------------------------------------------------
                $row2--;
                foreach( $additionalOther as $key => $entry ){
                    // ------------------------------------------------------
                    $worksheet->mergeCells( "K{$row2}:L{$row2}" )->getCell( "K{$row2}" )->setValue( $entry->name );
                    $worksheet->mergeCells( "M{$row2}:N{$row2}" )->getCell( "M{$row2}" )->setValue( $entry->value );
                    $worksheet->mergeCells( "O{$row2}:R{$row2}" )->getCell( "O{$row2}" )->setValue( $entry->memo );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Styling for additional row
                    // ------------------------------------------------------
                    $styleArray = [
                        'borders' => [
                            'left'   => [ 'borderStyle' => Border::BORDER_THIN ],
                            'right'  => [ 'borderStyle' => Border::BORDER_THIN ],
                            'bottom' => [ 'borderStyle' => Border::BORDER_THIN ],
                        ],
                    ];
                    // ------------------------------------------------------
                    $worksheet->getStyle( "K{$row1}:L{$row2}" )->applyFromArray( $styleArray );
                    $worksheet->getStyle( "M{$row1}:N{$row2}" )->applyFromArray( $styleArray );
                    $worksheet->getStyle( "O{$row1}:R{$row2}" )->applyFromArray( $styleArray );
                    // ------------------------------------------------------
                    $row2++;
                    if( !empty( $additionalRegistration[ $key ])){
                        $worksheet->mergeCells( "A{$row2}:B{$row2}" )->getCell( "A{$row2}" )->setValue( $additionalRegistration[ $key ]->name );
                        $worksheet->mergeCells( "C{$row2}:D{$row2}" )->getCell( "C{$row2}" )->setValue( $additionalRegistration[ $key ]->value );
                        $worksheet->mergeCells( "E{$row2}:I{$row2}" )->getCell( "E{$row2}" )->setValue( $additionalRegistration[ $key ]->memo );
                    }
                    // ------------------------------------------------------
                }
            }
        }
        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell( "C{$row1}" )->setValue( '=SUM(C'.($row1+1).':D'.$row2.')' );
        $worksheet->getCell( "M{$row1}" )->setValue( '=SUM(M'.($row1+1).':N'.$row2.')' );
        // // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Finance related expenses (融資関連費用)
        // ------------------------------------------------------------------
        $row2 = $row2 + 1;
        $finance = $expense->finances;
        $totalTsuboBudget = $expense->total_budget_cost / ( $checklist->effective_area * 0.3025 );
        $worksheet->getCell( 'A'.$row2)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'K'.$row2)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'K'.( $row2 +1 ))->setValue( $expense->total_budget_cost )->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'M'.( $row2 +1 ))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'N'.( $row2 +1 ))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'O'.( $row2 +1 ))->setValue( $totalTsuboBudget )->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'Q'.( $row2 +1 ))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell( 'C'.( $row2 +1 ))->setValue( $finance->total_interest_rate );
        $worksheet->getCell( 'G'.( $row2 +1 ))->setValue( $finance->expected_interest_rate );
        $worksheet->getCell( 'C'.( $row2 +2 ))->setValue( $finance->banking_fee );
        $worksheet->getCell( 'E'.( $row2 +2 ))->setValue( $finance->banking_fee_memo );
        $worksheet->getCell( 'C'.( $row2 +3 ))->setValue( $finance->stamp );
        $worksheet->getCell( 'E'.( $row2 +3 ))->setValue( $finance->stamp_memo );
        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row3 = $row2 + 4;
        if( !empty( $finance->additional->entries )){
            // --------------------------------------------------------------
            $additionalFinance = $finance->additional->entries;
            $worksheet->insertNewRowBefore( $row3, count( $additionalFinance ));
            // --------------------------------------------------------------
            foreach( $additionalFinance as $key => $entry ){
                $worksheet->mergeCells( "A{$row3}:B{$row3}" )->getCell( "A{$row3}" )->setValue( $entry->name );
                $worksheet->mergeCells( "C{$row3}:D{$row3}" )->getCell( "C{$row3}" )->setValue( $entry->value );
                $worksheet->mergeCells( "E{$row3}:I{$row3}" )->getCell( "E{$row3}" )->setValue( $entry->memo );
                $row3++;
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell( "C{$row2}" )->setValue('=SUM(C'.($row2+1).':D'.$row3.')');
        // // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Tax related expenses 税金等
        // ------------------------------------------------------------------
        $row3 = $row3 + 1;
        $tax = (object) $expense->taxes;
        $worksheet->getCell('A'.$row3)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row3+1))->setValue( $tax->property_acquisition_tax );
        $worksheet->getCell('E'.($row3+1))->setValue( $tax->property_acquisition_tax_memo );
        $worksheet->getCell('C'.($row3+2))->setValue( $tax->the_following_year_the_city_tax );
        $worksheet->getCell('E'.($row3+2))->setValue( $tax->the_following_year_the_city_tax_memo );
        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row4 = $row3 + 3;
        if( count( $expense['taxes']['additional']['entries']) > 0 ){
            $worksheet->insertNewRowBefore( $row4, count( $expense['taxes']['additional']['entries'] ));
            foreach( $expense['taxes']['additional']['entries'] as $key => $value ){
                $worksheet->mergeCells('A'.$row4.':'.'B'.$row4)->getCell('A' . $row4)->setValue($value['name']);
                $worksheet->mergeCells('C'.$row4.':'.'D'.$row4)->getCell('C' . $row4)->setValue($value['value']);
                $worksheet->mergeCells('E'.$row4.':'.'I'.$row4)->getCell('E' . $row4)->setValue($value['memo']);
                $row4++;
            }
        }
        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell('C'.$row3)->setValue('=SUM(C'.($row3+1).':D'.$row4.')');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Construction related expenses (工事関連費用)
        // ------------------------------------------------------------------
        $row4 = $row4 + 1;
        $construction = $expense->constructions;
        $worksheet->getCell('A'.$row4)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row4+1))->setValue( $construction->building_demolition );
        $worksheet->getCell('E'.($row4+1))->setValue( $construction->building_demolition_memo );
        $worksheet->getCell('C'.($row4+2))->setValue( $construction->retaining_wall_demolition );
        $worksheet->getCell('E'.($row4+2))->setValue( $construction->retaining_wall_demolition_memo );
        $worksheet->getCell('C'.($row4+3))->setValue( $construction->transfer_electric_pole );
        $worksheet->getCell('E'.($row4+3))->setValue( $construction->transfer_electric_pole_memo );
        $worksheet->getCell('C'.($row4+4))->setValue( $construction->waterwork_construction );
        $worksheet->getCell('E'.($row4+4))->setValue( $construction->waterwork_construction_memo );
        $worksheet->getCell('C'.($row4+5))->setValue( $construction->fill_work );
        $worksheet->getCell('E'.($row4+5))->setValue( $construction->fill_work_memo );
        $worksheet->getCell('C'.($row4+6))->setValue( $construction->retaining_wall_construction );
        $worksheet->getCell('E'.($row4+6))->setValue( $construction->retaining_wall_construction_memo );
        $worksheet->getCell('C'.($row4+7))->setValue( $construction->road_construction );
        $worksheet->getCell('E'.($row4+7))->setValue( $construction->road_construction_memo );
        $worksheet->getCell('C'.($row4+8))->setValue( $construction->side_groove_construction );
        $worksheet->getCell('E'.($row4+8))->setValue( $construction->side_groove_construction_memo );
        $worksheet->getCell('C'.($row4+9))->setValue( $construction->construction_work_set );
        $worksheet->getCell('E'.($row4+9))->setValue( $construction->construction_work_set_memo );
        $worksheet->getCell('C'.($row4+10))->setValue( $construction->location_designation_application_fee );
        $worksheet->getCell('E'.($row4+10))->setValue( $construction->location_designation_application_fee_memo );
        $worksheet->getCell('C'.($row4+11))->setValue( $construction->development_commissions_fee );
        $worksheet->getCell('E'.($row4+11))->setValue( $construction->development_commissions_fee_memo );
        $worksheet->getCell('C'.($row4+12))->setValue( $construction->cultural_property_research_fee );
        $worksheet->getCell('E'.($row4+12))->setValue( $construction->cultural_property_research_fee_memo );
        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row5 = $row4 + 13;
        if( !empty( $construction->additional->entries )){
            // --------------------------------------------------------------
            $additionalConstruction = $construction->additional->entries;
            $worksheet->insertNewRowBefore( $row5, count( $additionalConstruction ));
            // --------------------------------------------------------------
            foreach( $additionalConstruction as $key => $entry ){
                $worksheet->mergeCells( "A{$row5}:B{$row5}" )->getCell( "A{$row5}" )->setValue( $entry->name );
                $worksheet->mergeCells( "C{$row5}:D{$row5}" )->getCell( "C{$row5}" )->setValue( $entry->value );
                $worksheet->mergeCells( "E{$row5}:I{$row5}" )->getCell( "E{$row5}" )->setValue( $entry->memo );
                $row5++;
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell( "C{$row4}" )->setValue('=SUM(C'.($row4+1).':D'.$row5.')');
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Output Report for Plan Selected
        // ------------------------------------------------------------------
        // 販売の部
        // ------------------------------------------------------------------
        $row6 = $row5 + 2;
        foreach( $sale->plans as $key => $plan ){
            $plan = (object) $plan;
            if( $plan->export ){
                // ----------------------------------------------------------
                $worksheet->getCell( 'A'.( $row5 +1 ))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell( 'A'.$row6 )->setValue( $plan->plan_name )->getStyle()->getFont()->setBold(true);
                // ----------------------------------------------------------
                $i = 2;
                foreach( $plan->sections as $key => $section ){
                    // ------------------------------------------------------
                    $index = $row6 + $i;
                    $section = (object) $section;
                    if( $key > 1 ) $worksheet->insertNewRowBefore( $index );
                    // ------------------------------------------------------
                    $types = collect([ 1 => '収', 2 => '支', 3 => '無' ]);
                    $brokerageFeeType = $types->get( $section->brokerage_fee_type );
                    // ------------------------------------------------------
                    $worksheet->getCell( "A{$index}" )->setValue( $section->divisions_number );
                    $worksheet->mergeCells( "B{$index}:C{$index}" )->getCell( "B{$index}" )->setValue( $section->reference_area );
                    $worksheet->mergeCells( "D{$index}:F{$index}" )->getCell( "D{$index}" )->setValue( $section->planned_area );
                    $worksheet->mergeCells( "G{$index}:H{$index}" )->getCell( "G{$index}" )->setValue( $section->unit_selling_price );
                    $worksheet->getCell( "I{$index}" )->setValue( "=G{$index}/D{$index}" );
                    $worksheet->mergeCells( "K{$index}:L{$index}" )->getCell( "K{$index}" )->setValue( $section->brokerage_fee );
                    $worksheet->getCell( "M{$index}" )->setValue( $brokerageFeeType );
                    $i++;
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
                $index = $row6 + $i;
                $worksheet->mergeCells( "B{$index}:C{$index}" )->getCell( "B{$index}" )->setValue( $plan->reference_area_total );
                $worksheet->mergeCells( "D{$index}:F{$index}" )->getCell( "D{$index}" )->setValue( $plan->planned_area_total );
                $worksheet->mergeCells( "G{$index}:H{$index}" )->getCell( "G{$index}" )->setValue( $plan->unit_selling_price_total );
                // ----------------------------------------------------------
                $worksheet->getCell('C'.($row6+$i+2))->setValue( $plan->plan_memo );
                $worksheet->getCell('A'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('C'.($row6+$i+4))->setValue( $plan->gross_profit_plan );
                $worksheet->getCell('E'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('G'.($row6+$i+4))->setValue( $plan->gross_profit_plan_percentage )->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('H'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('A'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('I'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('M'.($row6+$i+4))->setValue( $plan->gross_profit_total_plan )->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('P'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('Q'.($row6+$i+4))->setValue( '=M'.($row6+$i+4).'/G'.($row6+$i).'*100' )->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('R'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create Output
        // ------------------------------------------------------------------
        $writer = IOFactory::createWriter( $spreadsheet, 'Xlsx' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $public = public_path();
        $project = $data->project; $sheet = $data->sheet;
        $projectID = $project->id; $sheetID = $sheet->id;
        $projectHash = sha1( "port-project-$projectID" );
        $sheetHash = sha1( "port-sheet-$sheetID" );
        // ------------------------------------------------------------------
        $directory = "reports/output/{$projectHash}/{$sheetHash}";
        $filepath = "{$directory}/PJシート-{$sheetID}.xlsx";
        // ------------------------------------------------------------------
        File::makeDirectory( "{$public}/{$directory}", 0777, true, true );
        $writer->save( "{$public}/{$filepath}" );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return url( $filepath );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------

        // Create Output
        // $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $projectId = $response->data->project['id'];
        // $sheetIndex = $sheetIndex + 1;
        // $path = public_path().'/reports/output/' . 'project_'. $projectId. '/'. 'sheet_'. $sheetIndex;
        // File::makeDirectory($path, $mode = 0777, true, true);

        // $writer->save(public_path().'/reports/output/' . 'project_'. $projectId. '/'. 'sheet_'. $sheetIndex. '/output_expense_sheet_'.$sheetIndex.'.xlsx');
        // ------------------------------------------------------------------


    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
