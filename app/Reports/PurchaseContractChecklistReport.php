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

// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PurchaseContractChecklistReport
{
    // ----------------------------------------------------------------------
    // Get property condition
    // ----------------------------------------------------------------------
    private static function getPropertyCondition($property, $residential_relation, $road_relation, $field, $condition){
        $result = 0; // initial data

        // purchase sale condition
        // ---------------------------------------------------------------------
        if ($condition == 1) {
            foreach ($property->residentials as $key => $residential) {
                if ($residential->{$residential_relation} && $residential->{$residential_relation}->urbanization_area == 3
                    && ($residential->{$residential_relation}->{$field} == 1
                    || $residential->{$residential_relation}->{$field} == 2)) {
                    $result += 1;
                }
            }
            foreach ($property->roads as $key => $road) {
                if ($road->{$road_relation} && $road->{$road_relation}->urbanization_area == 3
                && ($road->{$road_relation}->{$field} == 1 || $road->{$road_relation}->{$field} == 2)) {
                    $result += 1;
                }
            }
        }
        // ---------------------------------------------------------------------

        // assist b condition
        // ---------------------------------------------------------------------
        elseif ($condition == 2) {
            foreach ($property->residentials as $key => $residential) {
                if ($residential->{$residential_relation} && $residential->{$residential_relation}->{$field} == 1) {
                    $result += 1;
                }
            }
            foreach ($property->roads as $key => $road) {
                if ($road->{$road_relation} && $road->{$road_relation}->{$field} == 1) {
                    $result += 1;
                }
            }
        }
        // ---------------------------------------------------------------------

        return $result;
    }
    // ----------------------------------------------------------------------
    // Create Report Output Of Expense
    // ----------------------------------------------------------------------
    public static function reportPurchaseContractChecklist($data){
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = IOFactory::load('reports/template/purchase-contract-checklist.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Data
        // ------------------------------------------------------------------
        $project = $data->project;
        $checklist = $data->purchase_doc;
        $property = $data->property;

        // ------------------------------------------------------------------
        // Output Report for Sheet Selected
        // ------------------------------------------------------------------
        $worksheet->getCell('A1')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('A2')->setValue('物件名称：' . $project->title);
        $worksheet->getCell('A4')->getStyle()->getFont()->setBold(true);
        $worksheet->getCell('B3')->setValue(date('Y') . '年 ' . date('m') . '月 ' . date('d') . '日');

        $row = 5;
        $num = 1;
        if (!$checklist->road_type_contract_a || ($checklist->road_type_contract_b
            || $checklist->road_type_contract_c || $checklist->road_type_contract_d
            || $checklist->road_type_contract_e || $checklist->road_type_contract_f
            || $checklist->road_type_contract_g || $checklist->road_type_contract_h
            || $checklist->road_type_contract_i)) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('前面道路の土地謄本');
            $row = $row + 1;
            $num = $num + 1;
        }
        if ($checklist->heads_up_a == 1) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('上下水道引き込み見積取得');
            $row = $row + 1;
            $num = $num + 1;
        }
        if ($checklist->heads_up_c == 1) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('都計道路');
            $row = $row + 1;
            $num = $num + 1;
        }
        if ($checklist->heads_up_d == 1) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('工事見積取得');
            $row = $row + 1;
            $num = $num + 1;
        }
        if ($checklist->heads_up_e == 1) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('地質調査');
            $row = $row + 1;
            $num = $num + 1;
        }
        if ($checklist->heads_up_f != 1 && $checklist->heads_up_g == 1) {
            $worksheet->getCell('A' . $row)->setValue($num);
            $worksheet->getCell('B' . $row)->setValue('設計コンサルチェックへ');
            $row = $row + 1;
            $num = $num + 1;
        }
        if (!empty($property) && (!empty($property->residentials) || !empty($property->roads))) {
            $urban = static::getPropertyCondition($property, 'residential_purchase', 'road_purchase', 'urbanization_area_sub', 1);
            if ($urban != 0) {
                $worksheet->getCell('A' . $row)->setValue($num);
                $worksheet->getCell('B' . $row)->setValue('使用収益開始日確認');
                $row = $row + 1;
                $num = $num + 1;
            }

            $cultural = static::getPropertyCondition($property, 'residential_b', 'road_b', 'cultural_property_reserves', 2);
            if ($cultural != 0) {
                $worksheet->getCell('A' . $row)->setValue($num);
                $worksheet->getCell('B' . $row)->setValue('地区計画資料取得');
                $row = $row + 1;
                $num = $num + 1;
            }

            $district = static::getPropertyCondition($property, 'residential_b', 'road_b', 'district_planning', 2);
            if ($district != 0) {
                $worksheet->getCell('A' . $row)->setValue($num);
                $worksheet->getCell('B' . $row)->setValue('風致地区');
                $row = $row + 1;
                $num = $num + 1;
            }

            $landslide = static::getPropertyCondition($property, 'residential_b', 'road_b', 'landslide', 2);
            if ($landslide != 0) {
                $worksheet->getCell('A' . $row)->setValue($num);
                $worksheet->getCell('B' . $row)->setValue('土砂災害警戒区域等指定箇所');
                $row = $row + 1;
                $num = $num + 1;
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
        $projectID = $project->id; $targetID = $checklist->pj_purchase_target_id;
        $projectHash = sha1( "port-project-$projectID" );
        $targetHash = sha1( "port-sheet-$targetID" );
        // ------------------------------------------------------------------
        $directory = "reports/output/{$projectHash}/{$targetHash}";
        $filepath = "{$directory}/取り纏め依頼チェックリスト-{$targetID}.xlsx";
        // ------------------------------------------------------------------
        File::makeDirectory( "{$public}/{$directory}", 0777, true, true );
        $writer->save( "{$public}/{$filepath}" );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return url( $filepath );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
