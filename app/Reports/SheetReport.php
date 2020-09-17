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
class SheetReport
{
    // ----------------------------------------------------------------------
    // Create Report Output Of Sheet
    // ----------------------------------------------------------------------
    public static function reportSheet($data){
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = IOFactory::load('reports/template/sheet-report.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Parameter for retrive sheet and project data
        // ------------------------------------------------------------------
        $retriveDataSheet = $data->sheet;
        $retriveDataChecklist = $data->sheet['checklist'];
        $retriveDataReport = $data->sheet['stock'];
        $retriveDataSale = $data->sale;
        $retriveDataProject = $data->project;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Data
        // ------------------------------------------------------------------
        $worksheet->getCell('A1')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('A3')->setValue($retriveDataProject['title'])->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('A4')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('B4')->setValue($retriveDataSheet['name'])->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('A6')->setValue($retriveDataProject['overall_area']);
        $worksheet->getCell('G6')->setValue($retriveDataChecklist['breakthrough_rate']);
        $worksheet->getCell('J6')->setValue($retriveDataChecklist['effective_area']);
        $worksheet->getCell('A8')->setValue($retriveDataProject['fixed_asset_tax_route_value']);
        $worksheet->getCell('A10')->getStyle()->getFont()->setBold(true);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for Sheet Selected
        // ------------------------------------------------------------------

        // 仕入の部

        // 仕入予定価格
        $purchase = $retriveDataReport['procurements'];
        $types = collect([ 1 => '収', 2 => '支', 3 => '無' ]);
        $brokerageFeeType = $types->get( $purchase->brokerage_fee_type );

        $worksheet->getCell('A12')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C12')->setValue($retriveDataReport['procurements']['price']);
        $worksheet->getCell('A15')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C15')->setValue($retriveDataReport['procurements']['brokerage_fee']);
        $worksheet->getCell('E15')->setValue( $brokerageFeeType );
        $worksheet->getCell('G15')->setValue($retriveDataReport['procurements']['brokerage_fee_memo']);

        // 測量関連費用
        $worksheet->getCell('K12')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('M13')->setValue($retriveDataReport['surveys']['fixed_survey']);
        $worksheet->getCell('O13')->setValue($retriveDataReport['surveys']['fixed_survey_memo']);
        $worksheet->getCell('M14')->setValue($retriveDataReport['surveys']['divisional_registration']);
        $worksheet->getCell('O14')->setValue($retriveDataReport['surveys']['divisional_registration_memo']);
        $worksheet->getCell('M15')->setValue($retriveDataReport['surveys']['boundary_pile_restoration']);
        $worksheet->getCell('O15')->setValue($retriveDataReport['surveys']['boundary_pile_restoration_memo']);

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row1 = 16;
        if (count($retriveDataReport['surveys']['additional']['entries']) > 0) {
            $worksheet->insertNewRowBefore($row1, count($retriveDataReport['surveys']['additional']['entries']));
            foreach ($retriveDataReport['surveys']['additional']['entries'] as $key => $value) {
                $worksheet->mergeCells('K'.$row1.':'.'L'.$row1)->getCell('K'.$row1)->setValue($value['name']);
                $worksheet->mergeCells('M'.$row1.':'.'N'.$row1)->getCell('M'.$row1)->setValue($value['value']);
                $worksheet->mergeCells('O'.$row1.':'.'R'.$row1)->getCell('O'.$row1)->setValue($value['memo']);
                $row1++;
            }
        }

        // ------------------------------------------------------------------
        // Styling for additional row
        // ------------------------------------------------------------------
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
        ];
        $worksheet->getStyle('A16:'.'J'.$row1)->applyFromArray($styleArray);
        $worksheet->getStyle('A16:'.'I'.$row1)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');

        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell('M12')->setValue('=SUM(M13:N'.$row1.')');

        // 登記関連費用
        $row1 = $row1 + 1;
        $registration = $retriveDataReport['registers'];
        $taxDate = Carbon::parse( $registration->fixed_assets_tax_date );

        $worksheet->getCell('A'.$row1)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row1+1))->setValue($retriveDataReport['registers']['transfer_of_ownership']);
        $worksheet->getCell('E'.($row1+1))->setValue($retriveDataReport['registers']['transfer_of_ownership_memo']);
        $worksheet->getCell('C'.($row1+2))->setValue($retriveDataReport['registers']['mortgage_setting']);
        $worksheet->getCell('G'.($row1+2))->setValue($retriveDataReport['registers']['mortgage_setting_plan']);
        $worksheet->getCell('C'.($row1+3))->setValue($retriveDataReport['registers']['fixed_assets_tax']);
        $worksheet->getCell('G'.($row1+3))->setValue( $taxDate->format( 'Y/m/d' ));
        $worksheet->getCell('G'.($row1+3))->getStyle()->getAlignment()->setHorizontal('right');
        $worksheet->getCell('C'.($row1+4))->setValue($retriveDataReport['registers']['loss']);
        $worksheet->getCell('E'.($row1+4))->setValue($retriveDataReport['registers']['loss_memo']);

        // その他
        $worksheet->getCell('K'.$row1)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('M'.($row1+1))->setValue($retriveDataReport['others']['referral_fee']);
        $worksheet->getCell('O'.($row1+1))->setValue($retriveDataReport['others']['referral_fee_memo']);
        $worksheet->getCell('M'.($row1+2))->setValue($retriveDataReport['others']['eviction_fee']);
        $worksheet->getCell('O'.($row1+2))->setValue($retriveDataReport['others']['eviction_fee_memo']);
        $worksheet->getCell('M'.($row1+3))->setValue($retriveDataReport['others']['water_supply_subscription']);
        $worksheet->getCell('O'.($row1+3))->setValue($retriveDataReport['others']['water_supply_subscription_memo']);

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row2 = $row1 + 5;
        if (count($retriveDataReport['registers']['additional']['entries']) > 0 || count($retriveDataReport['others']['additional']['entries']) > 0) {
            if (count($retriveDataReport['registers']['additional']['entries']) > (count($retriveDataReport['others']['additional']['entries']) - 1)) {
                $worksheet->insertNewRowBefore($row2, count($retriveDataReport['registers']['additional']['entries']));
                foreach ($retriveDataReport['registers']['additional']['entries'] as $key => $value) {
                    $worksheet->mergeCells('A'.$row2.':'.'B'.$row2)->getCell('A' . $row2)->setValue($value['name']);
                    $worksheet->mergeCells('C'.$row2.':'.'D'.$row2)->getCell('C' . $row2)->setValue($value['value']);
                    $worksheet->mergeCells('E'.$row2.':'.'I'.$row2)->getCell('E' . $row2)->setValue($value['memo']);
                    if (!empty($retriveDataReport['others']['additional']['entries'][$key])) {
                        $row2--;
                        $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K' . $row2)->setValue($retriveDataReport['others']['additional']['entries'][$key]['name']);
                        $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M' . $row2)->setValue($retriveDataReport['others']['additional']['entries'][$key]['value']);
                        $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O' . $row2)->setValue($retriveDataReport['others']['additional']['entries'][$key]['memo']);
                        $row2++;

                        // ------------------------------------------------------------------
                        // Styling for additional row
                        // ------------------------------------------------------------------
                        $styleArray = [
                            'borders' => [
                                'left' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'right' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                        ];
                        $worksheet->getStyle('K'.$row1.':'.'L'.$row2)->applyFromArray($styleArray);
                        $worksheet->getStyle('M'.$row1.':'.'N'.$row2)->applyFromArray($styleArray);
                        $worksheet->getStyle('O'.$row1.':'.'R'.$row2)->applyFromArray($styleArray);
                    } else {
                        $row2--;
                        $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K'.$row2)->setValue('');
                        $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M'.$row2)->setValue('');
                        $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O'.$row2)->setValue('');
                        $row2++;
                    }
                    $row2++;
                }

            } else {
                if (count($retriveDataReport['others']['additional']['entries']) > 1) {
                    $worksheet->insertNewRowBefore($row2, count($retriveDataReport['others']['additional']['entries']) - 1);
                }
                $row2--;
                foreach ($retriveDataReport['others']['additional']['entries'] as $key => $value) {
                    $worksheet->mergeCells('K'.$row2.':'.'L'.$row2)->getCell('K' . $row2)->setValue($value['name']);
                    $worksheet->mergeCells('M'.$row2.':'.'N'.$row2)->getCell('M' . $row2)->setValue($value['value']);
                    $worksheet->mergeCells('O'.$row2.':'.'R'.$row2)->getCell('O' . $row2)->setValue($value['memo']);

                    // ------------------------------------------------------------------
                    // Styling for additional row
                    // ------------------------------------------------------------------
                    $styleArray = [
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'right' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ];
                    $worksheet->getStyle('K'.$row1.':'.'L'.$row2)->applyFromArray($styleArray);
                    $worksheet->getStyle('M'.$row1.':'.'N'.$row2)->applyFromArray($styleArray);
                    $worksheet->getStyle('O'.$row1.':'.'R'.$row2)->applyFromArray($styleArray);
                    $row2++;
                    if (!empty($retriveDataReport['registers']['additional']['entries'][$key])) {
                        $worksheet->mergeCells('A'.$row2.':'.'B'.$row2)->getCell('A' . $row2)->setValue($retriveDataReport['registers']['additional']['entries'][$key]['name']);
                        $worksheet->mergeCells('C'.$row2.':'.'D'.$row2)->getCell('C' . $row2)->setValue($retriveDataReport['registers']['additional']['entries'][$key]['value']);
                        $worksheet->mergeCells('E'.$row2.':'.'I'.$row2)->getCell('E' . $row2)->setValue($retriveDataReport['registers']['additional']['entries'][$key]['memo']);
                    }
                }
            }
        }

        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell('C'.$row1)->setValue('=SUM(C'.($row1+1).':D'.$row2.')');
        $worksheet->getCell('M'.$row1)->setValue('=SUM(M'.($row1+1).':N'.$row2.')');

        // 融資関連費用
        $row2 = $row2 + 1;
        $worksheet->getCell('A'.$row2)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('K'.$row2)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('K'.($row2+1))->setValue($retriveDataReport['total_budget_cost'])->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('M'.($row2+1))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('N'.($row2+1))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('O'.($row2+1))->setValue($retriveDataReport['total_budget_cost'] / ($retriveDataChecklist['effective_area'] * 0.3025))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('Q'.($row2+1))->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row2+1))->setValue($retriveDataReport['finances']['total_interest_rate']);
        $worksheet->getCell('G'.($row2+1))->setValue($retriveDataReport['finances']['expected_interest_rate']);
        $worksheet->getCell('C'.($row2+2))->setValue($retriveDataReport['finances']['banking_fee']);
        $worksheet->getCell('E'.($row2+2))->setValue($retriveDataReport['finances']['banking_fee_memo']);
        $worksheet->getCell('C'.($row2+3))->setValue($retriveDataReport['finances']['stamp']);
        $worksheet->getCell('E'.($row2+3))->setValue($retriveDataReport['finances']['stamp_memo']);

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row3 = $row2 + 4;
        if (count($retriveDataReport['finances']['additional']['entries']) > 0) {
            $worksheet->insertNewRowBefore($row3, count($retriveDataReport['finances']['additional']['entries']));
            foreach ($retriveDataReport['finances']['additional']['entries'] as $key => $value) {
                $worksheet->mergeCells('A'.$row3.':'.'B'.$row3)->getCell('A' . $row3)->setValue($value['name']);
                $worksheet->mergeCells('C'.$row3.':'.'D'.$row3)->getCell('C' . $row3)->setValue($value['value']);
                $worksheet->mergeCells('E'.$row3.':'.'I'.$row3)->getCell('E' . $row3)->setValue($value['memo']);
                $row3++;
            }
        }

        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell('C'.$row2)->setValue('=SUM(C'.($row2+1).':D'.$row3.')');

        // 税金等
        $row3 = $row3 + 1;
        $worksheet->getCell('A'.$row3)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row3+1))->setValue($retriveDataReport['taxes']['property_acquisition_tax']);
        $worksheet->getCell('E'.($row3+1))->setValue($retriveDataReport['taxes']['property_acquisition_tax_memo']);
        $worksheet->getCell('C'.($row3+2))->setValue($retriveDataReport['taxes']['the_following_year_the_city_tax']);
        $worksheet->getCell('E'.($row3+2))->setValue($retriveDataReport['taxes']['the_following_year_the_city_tax_memo']);

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row4 = $row3 + 3;
        if (count($retriveDataReport['taxes']['additional']['entries']) > 0) {
            $worksheet->insertNewRowBefore($row4, count($retriveDataReport['taxes']['additional']['entries']));
            foreach ($retriveDataReport['taxes']['additional']['entries'] as $key => $value) {
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

        // 工事関連費用
        $row4 = $row4 + 1;
        $worksheet->getCell('A'.$row4)->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('C'.($row4+1))->setValue($retriveDataReport['constructions']['building_demolition']);
        $worksheet->getCell('E'.($row4+1))->setValue($retriveDataReport['constructions']['building_demolition_memo']);
        $worksheet->getCell('C'.($row4+2))->setValue($retriveDataReport['constructions']['retaining_wall_demolition']);
        $worksheet->getCell('E'.($row4+2))->setValue($retriveDataReport['constructions']['retaining_wall_demolition_memo']);
        $worksheet->getCell('C'.($row4+3))->setValue($retriveDataReport['constructions']['transfer_electric_pole']);
        $worksheet->getCell('E'.($row4+3))->setValue($retriveDataReport['constructions']['transfer_electric_pole_memo']);
        $worksheet->getCell('C'.($row4+4))->setValue($retriveDataReport['constructions']['waterwork_construction']);
        $worksheet->getCell('E'.($row4+4))->setValue($retriveDataReport['constructions']['waterwork_construction_memo']);
        $worksheet->getCell('C'.($row4+5))->setValue($retriveDataReport['constructions']['fill_work']);
        $worksheet->getCell('E'.($row4+5))->setValue($retriveDataReport['constructions']['fill_work_memo']);
        $worksheet->getCell('C'.($row4+6))->setValue($retriveDataReport['constructions']['retaining_wall_construction']);
        $worksheet->getCell('E'.($row4+6))->setValue($retriveDataReport['constructions']['retaining_wall_construction_memo']);
        $worksheet->getCell('C'.($row4+7))->setValue($retriveDataReport['constructions']['road_construction']);
        $worksheet->getCell('E'.($row4+7))->setValue($retriveDataReport['constructions']['road_construction_memo']);
        $worksheet->getCell('C'.($row4+8))->setValue($retriveDataReport['constructions']['side_groove_construction']);
        $worksheet->getCell('E'.($row4+8))->setValue($retriveDataReport['constructions']['side_groove_construction_memo']);
        $worksheet->getCell('C'.($row4+9))->setValue($retriveDataReport['constructions']['construction_work_set']);
        $worksheet->getCell('E'.($row4+9))->setValue($retriveDataReport['constructions']['construction_work_set_memo']);
        $worksheet->getCell('C'.($row4+10))->setValue($retriveDataReport['constructions']['location_designation_application_fee']);
        $worksheet->getCell('E'.($row4+10))->setValue($retriveDataReport['constructions']['location_designation_application_fee_memo']);
        $worksheet->getCell('C'.($row4+11))->setValue($retriveDataReport['constructions']['development_commissions_fee']);
        $worksheet->getCell('E'.($row4+11))->setValue($retriveDataReport['constructions']['development_commissions_fee_memo']);
        $worksheet->getCell('C'.($row4+12))->setValue($retriveDataReport['constructions']['cultural_property_research_fee']);
        $worksheet->getCell('E'.($row4+12))->setValue($retriveDataReport['constructions']['cultural_property_research_fee_memo']);

        // ------------------------------------------------------------------
        // Additional row
        // ------------------------------------------------------------------
        $row5 = $row4 + 13;
        if (count($retriveDataReport['constructions']['additional']['entries']) > 0) {
            $worksheet->insertNewRowBefore($row5, count($retriveDataReport['constructions']['additional']['entries']));
            foreach ($retriveDataReport['constructions']['additional']['entries'] as $key => $value) {
                $worksheet->mergeCells('A'.$row5.':'.'B'.$row5)->getCell('A' . $row5)->setValue($value['name']);
                $worksheet->mergeCells('C'.$row5.':'.'D'.$row5)->getCell('C' . $row5)->setValue($value['value']);
                $worksheet->mergeCells('E'.$row5.':'.'I'.$row5)->getCell('E' . $row5)->setValue($value['memo']);
                $row5++;
            }
        }

        // ------------------------------------------------------------------
        // Formula after additional row
        // ------------------------------------------------------------------
        $worksheet->getCell('C'.$row4)->setValue('=SUM(C'.($row4+1).':D'.$row5.')');

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for Plan Selected
        // ------------------------------------------------------------------

        // 販売の部
        $row6 = $row5 + 2;
        foreach ($retriveDataSale['plans'] as $key => $plans) {
            if ($plans['export'] == true) {
                $worksheet->getCell('A'.($row5+1))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('A'.$row6)->setValue($plans['plan_name'])->getStyle()->getFont()->setBold(true);
                $i = 2;
                foreach ($plans['sections'] as $key => $section) {
                    if ($key > 1) {
                        $worksheet->insertNewRowBefore($row6+$i);
                    }
                    $worksheet->getCell('A'.($row6+$i))->setValue($section['divisions_number']);
                    $worksheet->mergeCells('B'.($row6+$i).':'.'C'.($row6+$i))->getCell('B'.($row6+$i))->setValue($section['reference_area']);
                    $worksheet->mergeCells('D'.($row6+$i).':'.'F'.($row6+$i))->getCell('D'.($row6+$i))->setValue($section['planned_area']);
                    $worksheet->mergeCells('G'.($row6+$i).':'.'H'.($row6+$i))->getCell('G'.($row6+$i))->setValue($section['unit_selling_price']);
                    $worksheet->getCell('I'.($row6+$i))->setValue('=G'.($row6+$i).'/D'.($row6+$i));
                    $worksheet->mergeCells('K'.($row6+$i).':'.'L'.($row6+$i))->getCell('K'.($row6+$i))->setValue($section['brokerage_fee']);
                    $worksheet->getCell('M'.($row6+$i))->setValue($section['brokerage_fee_type'] == 1 ? '収' : ($section['brokerage_fee_type'] == 2 ? '支' : ($section['brokerage_fee_type'] == 3 ? '無' : '')));
                    $i++;
                }
                $worksheet->mergeCells('B'.($row6+$i).':'.'C'.($row6+$i))->getCell('B'.($row6+$i))->setValue($plans['reference_area_total']);
                $worksheet->mergeCells('D'.($row6+$i).':'.'F'.($row6+$i))->getCell('D'.($row6+$i))->setValue($plans['planned_area_total']);
                $worksheet->mergeCells('G'.($row6+$i).':'.'H'.($row6+$i))->getCell('G'.($row6+$i))->setValue($plans['unit_selling_price_total']);
                $worksheet->getCell('C'.($row6+$i+2))->setValue($plans['plan_memo']);
                $worksheet->getCell('A'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('C'.($row6+$i+4))->setValue($plans['gross_profit_plan']);
                $worksheet->getCell('E'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('G'.($row6+$i+4))->setValue($plans['gross_profit_plan_percentage'])->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('H'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('A'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('I'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('M'.($row6+$i+4))->setValue($plans['gross_profit_total_plan'])->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('P'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('Q'.($row6+$i+4))->setValue('=M'.($row6+$i+4).'/G'.($row6+$i).'*100')->getStyle()->getFont()->setBold(true);
                $worksheet->getCell('R'.($row6+$i+4))->getStyle()->getFont()->setBold(true);
            }
        }

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
