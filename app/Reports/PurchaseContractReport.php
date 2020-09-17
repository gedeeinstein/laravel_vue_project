<?php

namespace App\Reports;

// --------------------------------------------------------------------------
use App\Models\PjPurchaseDoc;
use App\Models\PjProperty;
use App\Models\PjLotResidentialA;
use App\Models\PjLotRoadA;
use App\Models\PjLotBuildingA;
use App\Models\PjBuildingFloorSize;
use App\Models\PjPurchaseDocOptionalMemo;
use App\Models\PjPurchaseSale;
use App\Models\PjLotBuildingPurchaseCreate;
use App\Models\PjLotResidentialPurchaseCreate;
use App\Models\PjLotRoadPurchaseCreate;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseTargetContractor;
use App\Models\PjLotCommon;
use Carbon\Carbon;
use App\Models\MasterRegion;

use App\Models\PjLotContractor;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// --------------------------------------------------------------------------

use File;
// --------------------------------------------------------------------------

class PurchaseContractReport
{
    private static $allRowsAddedCounter = 0;

    public static function reportPurchaseContract($data, $purchaseId){
        ini_set('max_execution_time', 120);

        $projectId = $data->project->id;
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('reports/template/purchase-certificate.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();
        //$worksheet = $spreadsheet->getSheet(1);
        // ------------------------------------------------------------------
        $retriveDataPurchaseTarget = PjPurchaseTarget::where('id', $data->purchase_target->id)->first();
        // $retriveDataContractor = PjPurchaseTargetContractor::with('contractor')->where('pj_purchase_target_id', $data->purchase_target->id)->first();
        $retriveDataPurchaseDocs = PjPurchaseDoc::where('pj_purchase_target_id', $data->purchase_target->id)->get();
        $getPropertiesId = PjProperty::where('project_id', $projectId)->first();

        // get purchase information data
        // ---------------------------------------------------------------------
        $purchase_target_contractors = PjPurchaseTargetContractor::where('pj_purchase_target_id', $data->purchase_target->id)->get();

        // get same contractor name in pj lot contractor
        // ---------------------------------------------------------------------
        $purchase_lot_contractors_group_by_name = collect([]);
        foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $same_contractor = collect([]);
            $same_contractor_name = PjLotContractor::where("name", $purchase_target_contractor->contractor->name)->get();

            // check pj lot contractor property id
            // -----------------------------------------------------------------
            foreach ($same_contractor_name as $key => $contractor_name) {
                if ($contractor_name->common->pj_property_id == $purchase_target_contractor->contractor->common->pj_property_id) {
                  $same_contractor->push($contractor_name);
                }
            }
            // -----------------------------------------------------------------
            $purchase_lot_contractors_group_by_name->push($same_contractor);
        }
        // ---------------------------------------------------------------------

        // purchase sale and building third person occupied condition
        // ---------------------------------------------------------------------
        $purchaseSale['urbanization_area'] = null;
        $purchaseSale['urbanization_area_sub_1'] = null;
        $purchaseSale['urbanization_area_sub_2'] = null;
        $purchaseThirdPersonOccupied = null;
        foreach ($purchase_lot_contractors_group_by_name as $k => $purchase_lot_contractors) {
          foreach ($purchase_lot_contractors as $key => $purchase_lot_contractor) {
              // -----------------------------------------------------------------
              // condition for purchase sale
              // -----------------------------------------------------------------
              if ($purchase_lot_contractor->common->residential_a != null && $purchaseSale['urbanization_area'] == null && $purchase_lot_contractor->common->residential_a->residential_purchase != null)
              $purchaseSale['urbanization_area'] = $purchase_lot_contractor->common->residential_a->residential_purchase->urbanization_area == 3 ? 1 : null;
              if ($purchase_lot_contractor->common->road_a != null && $purchaseSale['urbanization_area'] == null && $purchase_lot_contractor->common->road_a->road_purchase != null)
              $purchaseSale['urbanization_area'] = $purchase_lot_contractor->common->road_a->road_purchase->urbanization_area == 3 ? 1 : null;

              if ($purchase_lot_contractor->common->residential_a != null && $purchaseSale['urbanization_area_sub_1'] == null && $purchase_lot_contractor->common->residential_a->residential_purchase != null)
              $purchaseSale['urbanization_area_sub_1'] = $purchase_lot_contractor->common->residential_a->residential_purchase->urbanization_area == 3 && $purchase_lot_contractor->common->residential_a->residential_purchase->urbanization_area_sub == 1 ? 1 : null;
              if ($purchase_lot_contractor->common->road_a != null && $purchaseSale['urbanization_area_sub_1'] == null && $purchase_lot_contractor->common->road_a->road_purchase != null)
              $purchaseSale['urbanization_area_sub_1'] = $purchase_lot_contractor->common->road_a->road_purchase->urbanization_area == 3 && $purchase_lot_contractor->common->road_a->road_purchase->urbanization_area_sub == 1 ? 1 : null;

              if ($purchase_lot_contractor->common->residential_a != null && $purchaseSale['urbanization_area_sub_2'] == null && $purchase_lot_contractor->common->residential_a->residential_purchase != null)
              $purchaseSale['urbanization_area_sub_2'] = $purchase_lot_contractor->common->residential_a->residential_purchase->urbanization_area == 3 && $purchase_lot_contractor->common->residential_a->residential_purchase->urbanization_area_sub == 2 ? 1 : null;
              if ($purchase_lot_contractor->common->road_a != null && $purchaseSale['urbanization_area_sub_2'] == null && $purchase_lot_contractor->common->road_a->road_purchase != null)
              $purchaseSale['urbanization_area_sub_2'] = $purchase_lot_contractor->common->road_a->road_purchase->urbanization_area == 3 && $purchase_lot_contractor->common->road_a->road_purchase->urbanization_area_sub == 2 ? 1 : null;
              // -----------------------------------------------------------------

              // -----------------------------------------------------------------
              // condition for purchase third person occupied
              // -----------------------------------------------------------------
              foreach ($retriveDataPurchaseTarget->purchase_target_buildings as $key => $building) {
                  if ($purchaseThirdPersonOccupied == null) { $purchaseThirdPersonOccupied = $building->purchase_third_person_occupied; }
              }
              // -----------------------------------------------------------------
          }
        }
        // ---------------------------------------------------------------------

        // enumerate contractor name
        // ---------------------------------------------------------------------
        $retriveDataContractor = null; $implode_contractor_name = '';
        foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $implode_contractor_name .= $purchase_target_contractor->contractor->name;
            if ($key < count($purchase_target_contractors) - 1) $implode_contractor_name .= ', ';
            elseif ($key == count($purchase_target_contractors) - 1) $implode_contractor_name .= ' 様';
        }
        $retriveDataContractor = $implode_contractor_name;
        // ---------------------------------------------------------------------

        // get residentials, roads and building data and purchase create data
        // ---------------------------------------------------------------------
        $residentials = collect([]); $roads = collect([]); $buildings = collect([]);
        $purchase_equity_texts = collect([]);
        foreach ($purchase_lot_contractors_group_by_name as $key => $purchase_lot_contractors) {
            foreach ($purchase_lot_contractors as $key => $purchase_lot_contractor) {
                if ($purchase_lot_contractor->common->residential_a) {
                    $residentials->push($purchase_lot_contractor->common->residential_a);
                    // collect purchase equity text data
                    if ($purchase_lot_contractor->residential_purchase_create->purchase_equity == 2 && $purchase_lot_contractor->residential_purchase_create->purchase_equity_text)
                        $purchase_equity_texts->push($purchase_lot_contractor->residential_purchase_create->purchase_equity_text);
                }
                if ($purchase_lot_contractor->common->road_a) {
                    $roads->push($purchase_lot_contractor->common->road_a);
                    // collect purchase equity text data
                    if ($purchase_lot_contractor->road_purchase_create->purchase_equity == 2 && $purchase_lot_contractor->road_purchase_create->purchase_equity_text)
                        $purchase_equity_texts->push($purchase_lot_contractor->road_purchase_create->purchase_equity_text);
                }
                if ($purchase_lot_contractor->common->building_a) {
                    $buildings->push($purchase_lot_contractor->common->building_a);
                    // collect purchase equity text data
                    if ($purchase_lot_contractor->building_purchase_create->purchase_equity == 2 && $purchase_lot_contractor->building_purchase_create->purchase_equity_text)
                        $purchase_equity_texts->push($purchase_lot_contractor->building_purchase_create->purchase_equity_text);
                }
            }
        }
        // ---------------------------------------------------------------------

        // group residentiald, road and building data
        // ---------------------------------------------------------------------
        $retriveDataPjLotResidentialA = $residentials->groupBy(function ($item, $key) {
                                            return $item['parcel_city'].$item['parcel_city_extra'].
                                                   $item['parcel_town'].$item['parcel_number_first'].
                                                   $item['parcel_number_second'];
                                        });
        $retriveDataPjLotRoadlA = $roads->groupBy(function ($item, $key) {
                                        return $item['parcel_city'].$item['parcel_city_extra'].
                                               $item['parcel_town'].$item['parcel_number_first'].
                                               $item['parcel_number_second'];
                                  });
        $retriveDataPjLotBuildingA = $buildings->groupBy(function ($item, $key) {
                                          return $item['parcel_city'].$item['parcel_city_extra'].
                                                 $item['parcel_town'].$item['building_number_first'].
                                                 $item['building_number_second'].$item['building_number_third'];
                                      });
        // ---------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 契約希望条件
        // ------------------------------------------------------------------
        // $desiredContractDate = Carbon::create($retriveDataPurchaseDocs[0]->desired_contract_date);
        $desiredContractDate = $retriveDataPurchaseDocs[0]->desired_contract_date;
        $settlementDate = $retriveDataPurchaseDocs[0]->settlement_date;
        $spreadsheet->getActiveSheet()->mergeCells('H26:Y26');
        $spreadsheet->getActiveSheet()->mergeCells('H27:Y27');
        // $worksheet->getCell('H26')->setValue($desiredContractDate->year.' 年 '.$desiredContractDate->month.' 月 '.$desiredContractDate->day.' 日')->getStyle()->getAlignment()->setHorizontal('right');
        // $worksheet->getCell('H27')->setValue($settlementDate->year.' 年 '.$settlementDate->month.' 月 '.$settlementDate->day.' 日')->getStyle()->getAlignment()->setHorizontal('right');
        $worksheet->getCell('H26')->setValue($desiredContractDate)->getStyle()->getAlignment()->setHorizontal('left');
        $worksheet->getCell('H27')->setValue($settlementDate)->getStyle()->getAlignment()->setHorizontal('left');

        $spreadsheet->getActiveSheet()->mergeCells('F28:K28');
        $spreadsheet->getActiveSheet()->mergeCells('P28:V28');
        $spreadsheet->getActiveSheet()->getStyle('F28')->getAlignment()->setHorizontal('right');
        $spreadsheet->getActiveSheet()->getStyle('P28')->getAlignment()->setHorizontal('right');
        $worksheet->getCell('F28')->setValue(number_format($retriveDataPurchaseTarget->purchase_price, 0, ',', '.'));
        $worksheet->getCell('P28')->setValue(number_format($retriveDataPurchaseTarget->purchase_deposit, 0, ',', '.'));

        $expireDate = Carbon::create($retriveDataPurchaseDocs[0]->expiration_date);

        $worksheet->getCell('C29')->setValue('有効期限');

        $spreadsheet->getActiveSheet()->mergeCells('J29:L29');

        $worksheet->getCell('J29')->setValue($expireDate->year);
        $worksheet->getCell('N29')->setValue($expireDate->month);
        $worksheet->getCell('P29')->setValue($expireDate->day);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(3);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 非表示にした定型文ID
        // ------------------------------------------------------------------
        $hiddenID = '';

        $hiddenIDNotice = [
            'a' => 'A01', 'b' => 'A02', 'c' => 'A03', 'd' => 'A04', 'e' => 'A05'
        ];

        //foreach($retriveDataPurchaseDocs as $purchaseDoc){
            foreach($hiddenIDNotice as $key => $getID){
                $getDataNotice = PjPurchaseDoc::select('properties_description_a', 'properties_description_e' , 'notices_'.$key.' as notice')->where('pj_purchase_target_id',$purchaseId)->first();
                //dd($getDataNotice);
                if($getDataNotice->notice == 0){
                    if($key == 'a'){
                        $hiddenID .= ' '.$getID;
                    }
                    if($getDataNotice->properties_description_a == 3 || $getDataNotice->properties_description_a == 4){
                        if($key == 'b'){
                            $hiddenID .= ' '.$getID;
                        }
                    }
                    if($getDataNotice->properties_description_e == 2 || $getDataNotice->properties_description_e == 3){
                        if($key == 'c'){
                            $hiddenID .= ' '.$getID;
                        }
                    }
                    if($key == 'd'){
                        $hiddenID .= ' '.$getID;
                    }
                    if($key == 'e'){
                        $hiddenID .= ' '.$getID;
                    }
                }
            }
        //}

        $hiddenIDPermisison = [
            'a' => 'B01', 'b' => 'B02', 'c' => 'B03', 'd' => 'B04'
        ];

        foreach($hiddenIDPermisison as $key => $getID){
            $getDataPermission = PjPurchaseDoc::select('request_permission_'.$key.' as permission')->where('pj_purchase_target_id',$purchaseId)->first();
            if($getDataPermission->permission == 0){
                $hiddenID .= ' '.$getID;
            }
        }

        $arrHiddenIdDesired = [];
        $hiddenIDCollect = '';
        $incrementAlphabet = 'a';
        for($i = 1; $i <= 12; $i++){
            if($i < 10){
                $hiddenIDCollect .= ' '.'C0'.$i;
                $arrHiddenIdDesired[$incrementAlphabet] = $hiddenIDCollect;
            }
            else{
                $hiddenIDCollect .= ' '.'C'.$i;
                $arrHiddenIdDesired[$incrementAlphabet] = $hiddenIDCollect;
            }
            $hiddenIDCollect = '';
            $incrementAlphabet++;
        }

        $arrHiddenIdDesired['m'] = 'C18';

        $hiddenIDCollect = '';
        $incrementAlphabet = 'n';
        for($i = 60; $i <= 66; $i++){
            $hiddenIDCollect .= ' '.'C'.$i;
            $arrHiddenIdDesired[$incrementAlphabet] = $hiddenIDCollect;

            $hiddenIDCollect = '';
            $incrementAlphabet++;
        }

        $hiddenIDCollect = '';
        $incrementAlphabet = 'u';
        for($i = 50; $i <= 57; $i++){
            $hiddenIDCollect .= ' '.'C'.$i;
            $arrHiddenIdDesired[$incrementAlphabet] = $hiddenIDCollect;

            $hiddenIDCollect = '';
            $incrementAlphabet++;
        }

        $arrHiddenIdOptionalItems = [];
        $hiddenIDCollectOptionalItems = '';
        $incrementAlphabet = 'a';
        for($i = 1; $i <= 11; $i++){
            if($i < 10){
                $hiddenIDCollectOptionalItems .= ' '.'D0'.$i;
                $arrHiddenIdOptionalItems[$incrementAlphabet] = $hiddenIDCollectOptionalItems;
            }
            else{
                $hiddenIDCollectOptionalItems .= ' '.'D'.$i;
                $arrHiddenIdOptionalItems[$incrementAlphabet] = $hiddenIDCollectOptionalItems;
            }
            $hiddenIDCollectOptionalItems = '';
            $incrementAlphabet++;
        }
        //dd($arrHiddenIdDesired);

        /*foreach($arrHiddenIdDesired as $key => $getID){
            $getDataDesired = PjPurchaseDoc::select('desired_contract_terms_'.$key.' as desired')->where('pj_purchase_target_id',$purchaseId)->first();
            if($getDataDesired->desired == 0){
                $hiddenID .= ' '.$getID;
            }
        }*/

        $styleArray = [
            'font' => [
                'color' => [
                    'argb' => 'FFA0A0A0',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 契約希望条件
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for Desired Contract Term
        // ------------------------------------------------------------------

        /*$pjPurchaseTarget = PjPurchaseTarget::where('pj_purchase_id', $purchaseId)->get();
        foreach($pjPurchaseTarget as $purchaseTarget){
            $purchaseTargetContractor = PjPurchaseTargetContractor::where('pj_purchase_target_id', $purchaseTarget->id)->get();
        }
        $purchaseTargetContractor =  $pjPurchaseTarget->purchase_target_contractors;*/

        //dd($purchaseTargetContractor->common['road_a']['road_purchase']['urbanization_area_sub']);

        $dataCollectDesiredContract = [];

        foreach($retriveDataPurchaseDocs as $purchaseDoc){
            if($purchaseDoc->heads_up_a == 1){
                $dataCollectDesiredContract['a'] = '本物件資料から上下水道が直ちに利用可能であると認識しております。利用可能であることを契約条件とさせて下さい。';
            }
            if($purchaseDoc->contract_a == 1){
                $dataCollectDesiredContract['b'] = '売買対象面積は公簿面積で確定するものとし、公簿面積と実測面積に変動ある場合でも、売買代金清算はしないものとさせて下さい。';
            }
            if($purchaseDoc->contract_a == 2){
                $dataCollectDesiredContract['c'] = '売買対象面積について、公簿面積と実測面積とに変動ある場合、実測面積を基準として売買代金清算とさせて下さい。尚、実測清算の対象は宅地部分に限るものし、道路部分は含みません。';
            }
            if($purchaseDoc->contract_b == 1){
                $dataCollectDesiredContract['d'] = '売主様の責任と負担において確定測量および地積更生登記をお願いします。不調時は白紙解除とさせて下さい。';
            }
            if($purchaseDoc->contract_b == 2){
                $dataCollectDesiredContract['e'] = '買主負担で確定測量および地積更生登記を実施しますが、不調時は白紙解除とさせて下さい。';
            }
            if($purchaseDoc->contract_b == 3){
                $dataCollectDesiredContract['f'] = '引渡までに境界の明示をお願いします。境界不明時は境界を復元の上、引渡をお願いします。';
            }
            if($purchaseDoc->contract_b == 4){
                $dataCollectDesiredContract['g'] = '売主様は確定測量および地積更生登記を行わないことに、買主は同意しております。';
            }

            if($purchaseSale['urbanization_area'] && $purchaseSale['urbanization_area_sub_2'] && $purchaseDoc->contract_c == 1){
                $dataCollectDesiredContract['h'] = '区画整理事業完了時において、清算金が発生した場合は売主様負担とさせて下さい。';
            }
            if($purchaseSale['urbanization_area'] && $purchaseSale['urbanization_area_sub_2'] && $purchaseDoc->contract_c == 2){
                $dataCollectDesiredContract['i'] = '区画整理事業完了時において、清算金が発生した場合は買主負担とします。';
            }
            if($purchaseSale['urbanization_area'] && $purchaseSale['urbanization_area_sub_2'] && $purchaseDoc->contract_d == 1){
                $dataCollectDesiredContract['j'] = '区画整理事業における賦課金が発生した場合は売主様負担とさせて下さい。';
            }
            if($purchaseSale['urbanization_area'] && $purchaseSale['urbanization_area_sub_2'] && $purchaseDoc->contract_d == 2){
                $dataCollectDesiredContract['k'] = '区画整理事業における賦課金が発生した場合は買主負担とします。';
            }

            $dataCollectDesiredContract['l'] = '売主様の責任と負担において解体工事を行い、更地での引渡をお願いします。';

            if($purchaseThirdPersonOccupied == 1){
                $dataCollectDesiredContract['m'] = '本物件について、被占有物件に該当しないことを前提として本書を提出させていただいております。実態として、本物件が被占有物件であった場合は、申告頂くようお願いします。';
            }

            if($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 1){
                    $dataCollectDesiredContract['n'] = '売主様の責任と負担において既存建物及び工作物の解体撤去工事を行い、滅失登記を完了させた上で引渡をお願いします。';
            }
            if(($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 1)){
                    $dataCollectDesiredContract['o'] = '売主様の責任と負担において、ホームスペクション（建物状況調査）を実施完了後、お引渡しいただきますようにお願い致します。';
            }
            if(($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 1)){
                    $dataCollectDesiredContract['p'] = 'お引渡しまでの間に発覚した不具合については引渡し前に補修していただきますようにお願いします。';
            }
            if(($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 3)){
                    $dataCollectDesiredContract['q'] = '本物件の敷地内（建物内外問わず）に、放置物等がある場合、売主様の責任と負担において、決済日までに撤去した上でお引き渡しいただきますようお願い致します。';
            }
            if(($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 3)){
                    $dataCollectDesiredContract['r'] = ['more' => [
                        '契約日より、７日前までに、以下の書類・データ等について確認させていただきますようお願いいたします。'
                        => ['イ）本物件の賃借人との間における賃貸借契約書及び重要事項説明書', 'ロ）本物件の賃借人の過去６月間の賃料入金履歴', '以上の内容次第で、本書の申込み条件の変更、もしくは取り下げの可能性があることをご了承下さい。']
                    ]];
            }
            if(($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 3 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_e == 3)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_e == 3)){
                    $dataCollectDesiredContract['s'] = '売主様の責任と負担において、居住部分がある場合、ホームスペクション（建物状況調査）を実施完了後、お引渡しいただきますようにお願い致します。';
            }
            if(($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 2)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_e == 3)){
                    $dataCollectDesiredContract['t'] = '売主の責任と負担において、買主が指定した建物の解体撤去工事及び滅失登記を引渡し時までにしていただきますようお願い致します。';
            }

            if($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 0){
                $dataCollectDesiredContract['u'] = '現況有姿にてお引き渡しを受けるものとします。　ただし、残置物については売主様にて撤去いただくようお願い致します。';
            }
            if(($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 1)){
                    $dataCollectDesiredContract['v'] = '現況有姿にてお引き渡しを受けるものとします。';
            }
            if($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 0){
                $dataCollectDesiredContract['w'] = '現況有姿にてお引き渡しを受けるものとしますが、解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。';
            }
            if($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 1){
                $dataCollectDesiredContract['x'] = '現況有姿にてお引き渡しを受けるものとしますが、解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。';
            }
            if($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 0){
                $dataCollectDesiredContract['y'] = '現況有姿にてお引き渡しを受けるものとします。　ただし、買主が指定した建物の残置物については売主様にて撤去いただくようお願い致します。';
            }
            if($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 0){
                $dataCollectDesiredContract['z'] = '現況有姿にてお引き渡しを受けるものしますが、買主が指定した建物については解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。また、残置物については売主様にて撤去いただくようお願い致します。';
            }

            if($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 1){
                $dataCollectDesiredContract['aa'] = '現況有姿にてお引き渡しを受けるものとしますが、買主が指定した建物については解体予定のため建物の所有権移転は行わず、それに代えて買主の責任と負担で引渡し後速やかに滅失登記を行うこととします。';
            }
            if(($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 1 && $purchaseDoc->properties_description_f == 1)
                || ($purchaseDoc->properties_description_a == 2 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 1 && $purchaseDoc->properties_description_f == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 1 && $purchaseDoc->properties_description_d == 1 && $purchaseDoc->properties_description_f == 1)
                || ($purchaseDoc->properties_description_a == 4 && $purchaseDoc->properties_description_b == 2 && $purchaseDoc->properties_description_c == 2 && $purchaseDoc->properties_description_d == 1 && $purchaseDoc->properties_description_f == 1)){
                    $dataCollectDesiredContract['ab'] = '売主様の責任と負担にて、建物内に設置されているエアコン（室外機含む）を撤去していただいた後のお引き渡しとしていただきますようお願い致します。';
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for Optional Items
        // ------------------------------------------------------------------
        $dataCollectOptionalItems = [];
        foreach($retriveDataPurchaseDocs as $purchaseDoc){
            if(($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_size_contract_a)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_d)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_size_contract_a)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_a)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_i && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_i && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['a'] = '引渡日までに本物件において建築確認済み証を取得することを停止条件とさせて下さい。尚、当該申請にかかる費用は買主の負担とします。';
            }
            if(($purchaseDoc->road_type_contract_b && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_type_sub2_contract_b == 2)
                || ($purchaseDoc->road_type_contract_b && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_i && $purchaseDoc->road_type_sub3_contract == 1)){
                    $dataCollectOptionalItems['b'] = '前面道路の持分を取得することを条件とさせて下さい。尚、前面道路の持分の価格は本物件価格に含まれるものとします。';
            }
            if(($purchaseDoc->road_type_contract_b && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_i && $purchaseDoc->road_type_sub3_contract == 1)){
                    $dataCollectOptionalItems['c'] = '前面道路の他共有者全員から通行掘削同意書の署名・捺印を頂くことを条件とさせて下さい。';
            }
            if(($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['d'] = '本書面に定めのない前面道路に関する事項は、別途協議させて頂きたいと思います。';
            }
            if(($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_a && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d))
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['e'] = '引渡日までに売主様の責任と負担において、道路部分と宅地部分の分筆登記を完了して頂きますようお願いします。';
            }
            if(($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_size_contract_a)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_size_contract_a)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_a && $purchaseDoc->road_size_contract_a)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['f'] = '引渡日までに売主様の責任と負担において、狭隘協議を行った上で分筆登記を完了して頂きますようお願いします。';
            }
            if(($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_a)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['g'] = '役所が指導する道路後退距離を確保するよう、売主様の責任と負担において引き渡し日までに分筆登記を完了して頂きますようお願いします。';
            }
            if($purchaseSale['urbanization_area'] && $purchaseSale['urbanization_area_sub_1']){
                    $dataCollectOptionalItems['h'] = '使用収益開始日以降の決済とさせて下さい。';
            }

            $dataCollectOptionalItems['i'] = '売主様は、買主が再販売を目的として購入する事を承諾し、契約日以降の販売活動を認めるものとします。';

            if(($purchaseDoc->road_type_contract_b && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_c && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_e && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_size_contract_a && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_f && $purchaseDoc->road_type_sub2_contract_b && ($purchaseDoc->road_size_contract_b || $purchaseDoc->road_size_contract_c || $purchaseDoc->road_size_contract_d) && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_g && $purchaseDoc->road_type_sub2_contract_b && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 2)
                || ($purchaseDoc->road_type_contract_i && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['j'] = '引渡日までに前面道路の持分と、他共有者全員から通行掘削同意書の署名・捺印を取得することを停止条件とさせて下さい。';
            }
            if(($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 1)
                || ($purchaseDoc->road_type_contract_h && $purchaseDoc->road_type_sub3_contract == 2)){
                    $dataCollectOptionalItems['k'] = '本物件の前面道路について、契約日までに取り決め等についての詳細調査を行い、その結果次第では本書を取り下げさせていただく可能性があることをご了承下さい。';
            }

        }
        // ------------------------------------------------------------------

        $keyCollectionDesiredActive = [];

        foreach($dataCollectDesiredContract as $key => $data){
            $keyCollectionDesiredActive[$key] = $key;
        }

        foreach($arrHiddenIdDesired as $key => $getID){
            if(array_key_exists($key, $keyCollectionDesiredActive)){
                $getDataDesired = PjPurchaseDoc::select('desired_contract_terms_'.$key.' as desired')->where('pj_purchase_target_id',$purchaseId)->first();
                if($getDataDesired->desired == 0){
                    $hiddenID .= ' '.$getID;
                }
            }
        }

        foreach($dataCollectOptionalItems as $key => $getID){
            $getDataDesiredContractTerm = PjPurchaseDoc::select('optional_items_'.$key.' as optional')->where('pj_purchase_target_id',$purchaseId)->first();
            if($getDataDesiredContractTerm->optional == 0){
                $hiddenID .= $arrHiddenIdOptionalItems[$key];
            }
        }
        //dd($hiddenID);
        $worksheet->getCell('Y38')->setValue($hiddenID)->getStyle()->applyFromArray($styleArray);

        $counterDisired = 0;
        $numberCounter = 1;
        //dd($dataCollectDesiredContract);
        foreach($dataCollectDesiredContract as $key => $data){

            $getDataDesiredContractTerm = PjPurchaseDoc::select('desired_contract_terms_'.$key.' as desired')->where('pj_purchase_target_id',$purchaseId)->first();
            if($getDataDesiredContractTerm->desired == 1){
                if ($key == 'm') {
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$counterDisired, 3); // Create new row
                    static::$allRowsAddedCounter+=3;
                    $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$counterDisired).':'.'Y'.(25+($counterDisired+2)));

                    $worksheet->getCell('C'.(25+$counterDisired))->setValue('('.$numberCounter.')');
                    $worksheet->getCell('AD'.(25+$counterDisired))->setValue('('.$numberCounter.')');
                    $worksheet->getCell('D'.(25+$counterDisired))->setValue($data);

                    $worksheet->getCell('AE'.(25+$counterDisired))->setValue('□ 被占有物件ではない');
                    $worksheet->getCell('AE'.(25+$counterDisired+1))->setValue('□ 被占有物件である (内容：　　　                                                                          )');

                    $counterDisired += 3;
                    $numberCounter++;
                }
                elseif(!empty($data['more'])){
                    foreach($data['more'] as $key => $dataMore){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$counterDisired, 2); // Create new row
                        //$spreadsheet->getActiveSheet()->insertNewRowBefore(25+$counterDisired); // Create new row
                        static::$allRowsAddedCounter+=2;
                        $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$counterDisired).':'.'Y'.(25+($counterDisired+1)));
                        //$spreadsheet->getActiveSheet()->mergeCells('AE'.(25+$counterDisired).':'.'AZ'.(25+($counterDisired+1)));

                        $worksheet->getCell('C'.(25+$counterDisired))->setValue('('.$numberCounter.')');
                        $worksheet->getCell('AD'.(25+$counterDisired))->setValue('('.$numberCounter.')');

                        $worksheet->getCell('D'.(25+$counterDisired))->setValue($key);
                        //$worksheet->getCell('AE'.(25+$counterDisired))->setValue($key);

                        $counterDisired +=2;

                        // A86-C64 A and B agreement
                        // -----------------------------------------------------
                        foreach ($dataMore as $key => $getDataDesiredContractTerm) {
                            if ($key == 0 || $key == 1) {
                                $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$counterDisired); // Create new row
                                static::$allRowsAddedCounter++;
                                $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$counterDisired).':'.'Y'.(25+$counterDisired));

                                $worksheet->getCell('D'.(25+$counterDisired))->setValue($getDataDesiredContractTerm);

                                if ($key == 0) $worksheet->getCell('AE'.(25+$counterDisired))->setValue('イ）　□ 有（内容：　　　                                                                              ）　□ 無');
                                elseif ($key == 1) $worksheet->getCell('AE'.(25+$counterDisired))->setValue('ロ）　□ 有（内容：　　　                                                                              ）　□ 無');

                                $counterDisired++;
                            }elseif ($key == 2) {
                                $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$counterDisired, 2); // Create new row

                                static::$allRowsAddedCounter+=2;
                                $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$counterDisired).':'.'Y'.(25+($counterDisired+1)));

                                $worksheet->getCell('D'.(25+$counterDisired))->setValue($getDataDesiredContractTerm);

                                $counterDisired += 2;
                            }
                        }
                        // -----------------------------------------------------
                    }
                    $numberCounter++;
                }else{
                    $dataCounter = PurchaseContractReport::createDynamicRowsContractCondition($worksheet, $spreadsheet, $data, $counterDisired, $numberCounter);
                    $counterDisired = $dataCounter;
                    $numberCounter++;
                }
            }
        }

        //dd($arrHiddenIdOptionalItems);
        foreach($dataCollectOptionalItems as $key => $data){
            $getDataDesiredContractTerm = PjPurchaseDoc::select('optional_items_'.$key.' as optional')->where('pj_purchase_target_id',$purchaseId)->first();
            if($getDataDesiredContractTerm->optional == 1){
                $dataCounter = PurchaseContractReport::createDynamicRowsContractCondition($worksheet, $spreadsheet, $data, $counterDisired, $numberCounter);
                $counterDisired = $dataCounter;
                $numberCounter++;
            }
        }

        $getPurchaseDocId = PjPurchaseDoc::where('pj_purchase_target_id', $purchaseId)->first();
        $getDataOptionalMemos = PjPurchaseDocOptionalMemo::where('pj_purchase_doc_id', $getPurchaseDocId['id'])->get();
        foreach($getDataOptionalMemos as $optionlMemeo){
            if(!empty($optionlMemeo->content)){
                $dataCounter = PurchaseContractReport::createDynamicRowsContractCondition($worksheet, $spreadsheet, $optionlMemeo->content, $counterDisired, $numberCounter);
                $counterDisired = $dataCounter;
                $numberCounter++;
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 許諾依頼事項
        // ------------------------------------------------------------------
        $rowCounterRequestPermision = 1;
        $numberCounter = 1;
        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            if($dataPurchaseDoc->request_permission_a == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision, 3); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                static::$allRowsAddedCounter+=3;
                $spreadsheet->getActiveSheet()->mergeCells('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+2)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+2)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+2)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+2)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                $worksheet->getCell('D'.(22+$rowCounterRequestPermision))->setValue('本書面に記載の金額、条件等が取りまとめ仲介業者様及び売主様以外に漏洩している事が発覚した場合は、本書の申し込みを取り下げる場合がある事をご了承下さい。');
                //dd($numberCounter);
                $worksheet->getCell('AD'. (22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                //$worksheet->getCell('AE'.(22+$rowCounterRequestPermision))->setValue('本書面に記載の金額、条件等が取りまとめ仲介業者様及び売主様以外に漏洩している事が発覚した場合は、本書の申し込みを取り下げる場合がある事をご了承下さい。');
                PurchaseContractReport::checkBoxInputOutput($worksheet, 22, $rowCounterRequestPermision);

                $rowCounterRequestPermision += 3;
                $numberCounter++;
            }
            if($dataPurchaseDoc->request_permission_b == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision, 4); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                static::$allRowsAddedCounter+=4;
                $spreadsheet->getActiveSheet()->mergeCells('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+3)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+3)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+3)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+3)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                $worksheet->getCell('D'.(22+$rowCounterRequestPermision))->setValue('本書について受託いただける場合、末尾記載の有効期限満了日までに売主様直筆の署名による売渡承諾書を発行頂き、仲介業者様もしくは売主様による手渡し、FAXもしくはEメールの手段で当社が確認できるようにお願いします。FAXの場合は、電話で双方の確認とさせて下さい。');

                $worksheet->getCell('AD'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                //$worksheet->getCell('AE'.(22+$rowCounterRequestPermision))->setValue('本書について受託いただける場合、末尾記載の有効期限満了日までに売主様直筆の署名による売渡承諾書を発行頂き、仲介業者様もしくは売主様による手渡し、FAXもしくはEメールの手段で当社が確認できるようにお願いします。FAXの場合は、電話で双方の確認とさせて下さい。');
                PurchaseContractReport::checkBoxInputOutput($worksheet, 22, $rowCounterRequestPermision);

                $rowCounterRequestPermision += 4;
                $numberCounter++;
            }
            if($dataPurchaseDoc->request_permission_c == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision, 2); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                static::$allRowsAddedCounter+=2;
                $spreadsheet->getActiveSheet()->mergeCells('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                $worksheet->getCell('D'.(22+$rowCounterRequestPermision))->setValue('末尾記載の有効期限日時点で売渡承諾書が確認できない場合、本書は取り下げさせて下さい。');

                $worksheet->getCell('AD'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                //$worksheet->getCell('AE'.(22+$rowCounterRequestPermision))->setValue('末尾記載の有効期限日時点で売渡承諾書が確認できない場合、本書は取り下げさせて下さい。');
                PurchaseContractReport::checkBoxInputOutput($worksheet, 22, $rowCounterRequestPermision);

                $rowCounterRequestPermision += 2;
                $numberCounter++;
            }
            if($dataPurchaseDoc->request_permission_d == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision, 2); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                static::$allRowsAddedCounter+=2;

                $spreadsheet->getActiveSheet()->mergeCells('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                $worksheet->getCell('D'.(22+$rowCounterRequestPermision))->setValue('売主様からご申告をいただいた本書面は、本物件が契約合意に至った場合、契約書に添付することを同意お願いします。');

                $worksheet->getCell('AD'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                //$worksheet->getCell('AE'.(22+$rowCounterRequestPermision))->setValue('売主様からご申告をいただいた本書面は、本物件が契約合意に至った場合、契約書に添付することを同意お願いします。');
                PurchaseContractReport::checkBoxInputOutput($worksheet, 22, $rowCounterRequestPermision);

                $rowCounterRequestPermision += 2;
                $numberCounter++;
            }
            if($dataPurchaseDoc->request_permission_e == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision, 2); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(22+$rowCounterRequestPermision); // Create new row
                static::$allRowsAddedCounter+=2;

                $spreadsheet->getActiveSheet()->mergeCells('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(22+$rowCounterRequestPermision).':'.'Y'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(22+$rowCounterRequestPermision).':'.'AZ'.(22+($rowCounterRequestPermision+1)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                $worksheet->getCell('D'.(22+$rowCounterRequestPermision))->setValue('購入法人名義について当社もしくは当社グループ会社になる可能性がある事をご了承ください。');

                $worksheet->getCell('AD'.(22+$rowCounterRequestPermision))->setValue('('.$numberCounter.')');
                //$worksheet->getCell('AE'.(22+$rowCounterRequestPermision))->setValue('売主様からご申告をいただいた本書面は、本物件が契約合意に至った場合、契約書に添付することを同意お願いします。');
                PurchaseContractReport::checkBoxInputOutput($worksheet, 22, $rowCounterRequestPermision);

                $rowCounterRequestPermision += 2;
                $numberCounter++;
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 告知事項
        // ------------------------------------------------------------------
        $rowCounterNotice = 1;
        $numberCounter = 1;
        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            if($dataPurchaseDoc->notices_a == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
                static::$allRowsAddedCounter++;
                $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+$rowCounterNotice));
                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+$rowCounterNotice));

                $worksheet->getCell('C'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('C'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                $worksheet->getCell('AD'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('AD'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');


                $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('自殺や事故、近隣トラブルなど。')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                PurchaseContractReport::checkBoxInputOutputContent($worksheet, $spreadsheet, 20, $rowCounterNotice);

                $rowCounterNotice++;
                $numberCounter++;
            }
            if($dataPurchaseDoc->properties_description_a == 3 || $dataPurchaseDoc->properties_description_a == 4){
                if($dataPurchaseDoc->notices_b == 1){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
                    static::$allRowsAddedCounter++;
                    $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+$rowCounterNotice));
                    //$spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+$rowCounterNotice));

                    $worksheet->getCell('C'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                    //$worksheet->getStyle('C'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                    $worksheet->getCell('AD'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                    //$worksheet->getStyle('AD'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                    $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('建物の補修履歴及び実施内容。また現時点での不具合や補修検討事項。');
                    PurchaseContractReport::checkBoxInputOutputContent($worksheet, $spreadsheet, 20, $rowCounterNotice);

                    $rowCounterNotice++;
                    $numberCounter++;
                }
            }
            if($dataPurchaseDoc->properties_description_e == 2 || $dataPurchaseDoc->properties_description_e == 3){
                if($dataPurchaseDoc->notices_c == 1){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
                    static::$allRowsAddedCounter++;
                    $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+$rowCounterNotice));
                    //$spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+$rowCounterNotice));

                    $worksheet->getCell('C'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                    //$worksheet->getStyle('C'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                    $worksheet->getCell('AD'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                    //$worksheet->getStyle('AD'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                    $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('賃借人についての告知事項（賃料滞納者の有無、クレーマー等）。');
                    //$worksheet->getCell('AE'.(20+$rowCounterNotice))->setValue('賃借人についての告知事項（賃料滞納者の有無、クレーマー等）。')->getStyle()->getAlignment()->setVertical('top');
                    PurchaseContractReport::checkBoxInputOutputContent($worksheet, $spreadsheet, 20, $rowCounterNotice);

                    $rowCounterNotice++;
                    $numberCounter++;
                }
            }
            if($dataPurchaseDoc->notices_d == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice, 2); // Create new row
                //$spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
                static::$allRowsAddedCounter+=2;

                $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+($rowCounterNotice+1)));
                $spreadsheet->getActiveSheet()->getStyle('D'.(20+$rowCounterNotice).':'.'Y'.(20+($rowCounterNotice+1)))->getAlignment()->setWrapText(true); // Wrap Text

                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+($rowCounterNotice+1)));
                //$spreadsheet->getActiveSheet()->getStyle('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+($rowCounterNotice+1)))->getAlignment()->setWrapText(true); // Wrap Text

                $worksheet->getCell('C'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('C'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                $worksheet->getCell('AD'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('AD'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('本物件及び隣接地について、過去または現在においてガソリンスタンドやクリーニング工場等、土壌汚染に関わる施設の存在。');
                //$worksheet->getCell('AE'.(20+$rowCounterNotice))->setValue('本物件及び隣接地について、過去または現在においてガソリンスタンドやクリーニング工場等、土壌汚染に関わる施設の存在。')->getStyle()->getAlignment()->setVertical('top');
                PurchaseContractReport::checkBoxInputOutputContent($worksheet, $spreadsheet, 20, $rowCounterNotice);

                $rowCounterNotice += 2;
                $numberCounter++;
            }
            if($dataPurchaseDoc->notices_e == 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
                static::$allRowsAddedCounter++;
                $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+$rowCounterNotice));
                //$spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+$rowCounterNotice));

                $worksheet->getCell('C'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('C'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                $worksheet->getCell('AD'.(20+$rowCounterNotice))->setValue('('.$numberCounter.')')->getStyle()->getFont()->setSize(10)->setName('Calibri');
                //$worksheet->getStyle('AD'.(20 + $rowCounterNotice))->getFont()->setName('Calibri');

                $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('その他、特記事項など。');
                //$worksheet->getCell('AE'.(20+$rowCounterNotice))->setValue('その他、特記事項など。');
                PurchaseContractReport::checkBoxInputOutputContent($worksheet, $spreadsheet, 20, $rowCounterNotice);

                $rowCounterNotice++;
            }
        }

        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            if($dataPurchaseDoc->gathering_request_title == 1){
                $title = '取り纏め依頼書';
            }else{
                $title = '買付証明書';
            }
        }

        $spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice,2); // Create new row
        //$spreadsheet->getActiveSheet()->insertNewRowBefore(20+$rowCounterNotice); // Create new row
        static::$allRowsAddedCounter+=2;

        $spreadsheet->getActiveSheet()->mergeCells('D'.(20+$rowCounterNotice).':'.'Y'.(20+($rowCounterNotice+1)));
        $spreadsheet->getActiveSheet()->getStyle('D'.(20+$rowCounterNotice).':'.'Y'.(20+($rowCounterNotice+1)))->getAlignment()->setWrapText(true); // Wrap Text

        $spreadsheet->getActiveSheet()->mergeCells('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+($rowCounterNotice+1)));
        $spreadsheet->getActiveSheet()->getStyle('AE'.(20+$rowCounterNotice).':'.'AZ'.(20+($rowCounterNotice+1)))->getAlignment()->setWrapText(true); // Wrap Text

        $worksheet->getCell('D'.(20+$rowCounterNotice))->setValue('上記の内容によっては、本書の申し込み条件の変更、もしくは取り下げの可能性があることをご了承下さい。');
        $worksheet->getCell('AE'.(20+$rowCounterNotice))->setValue('□上記の内容によって、御社が提出した'.$title.'の内容が変更になることを承諾します。');

        $rowCounterNotice += 2;
        $numberCounter++;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 買付証明書 (Purchase certificate) A-1
        // ------------------------------------------------------------------

        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            //
            $spreadsheet->getActiveSheet()->unmergeCells('B6:AA6');
            $spreadsheet->getActiveSheet()->mergeCells('B6:AA7');
            $spreadsheet->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal('left')->setWrapText(true);
            if($dataPurchaseDoc->gathering_request_title == 1){
                $worksheet->getCell('B1')->setValue('取り纏め依頼書');
                $worksheet->getCell('B6')->setValue('弊社は、末尾表示の不動産を購入したく、下記条件にて'.'取り纏め'.'を申し込み致します。');
            }else{
                $worksheet->getCell('B1')->setValue('買付証明書');
                $worksheet->getCell('B6')->setValue('弊社は、末尾表示の不動産を購入したく、下記条件にて'.'買付'.'を申し込み致します。');
            }

            if($dataPurchaseDoc->gathering_request_to_check == 1){
                $worksheet->getCell('B4')->setValue($retriveDataContractor);
            }else{
                if ($retriveDataContractor) {
                    $worksheet->getCell('B4')->setValue($dataPurchaseDoc->gathering_request_to.' 様');
                }
            }
        }

        // ------------------------------------------------------------------
        // Output report for 購入持分(Purchasing Interest)
        // ------------------------------------------------------------------
        $loopCounterText = 0;
        foreach ($purchase_equity_texts as $purchase_equity_text) {
                $spreadsheet->getActiveSheet()->insertNewRowBefore(16+$loopCounterText);
                static::$allRowsAddedCounter++;

                $spreadsheet->getActiveSheet()->mergeCells('C'.(16+$loopCounterText).':'.'Y'.(16+$loopCounterText)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AD'.(16+$loopCounterText).':'.'AZ'.(16+$loopCounterText)); // Merge cell
                $worksheet->getCell('C'.(16+$loopCounterText))->setValue('※ '.$purchase_equity_text);
                $worksheet->getCell('AD'.(16+$loopCounterText))->setValue('※ '.$purchase_equity_text);
                $loopCounterText++;
        }

        $spreadsheet->getActiveSheet()->removeRow(16+$loopCounterText);

        if($loopCounterText == 0){
            $spreadsheet->getActiveSheet()->getRowDimension(16)->setVisible(false);
            $spreadsheet->getActiveSheet()->getRowDimension(17)->setVisible(false);
        }
        if($loopCounterText >= 1){
            $spreadsheet->getActiveSheet()->getRowDimension(16+$loopCounterText)->setVisible(false);
            $spreadsheet->getActiveSheet()->getRowDimension(17+$loopCounterText)->setVisible(false);

            $spreadsheet->getActiveSheet()->insertNewRowBefore(16+$loopCounterText);
            static::$allRowsAddedCounter++;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for (Pj Lot Residential)
        // ------------------------------------------------------------------
        $rowCounterPjLot = 0;
        $maxRowPjLot = 8;

        $landNumberPjResidentialBefore = '';
        $parcelSizeResident = 0;

        $loopCounterResident = 0;
        $totalParcelSizeResident = 0;
        foreach($retriveDataPjLotResidentialA as $pjLotResidentialGrouped){
            $pjLotResidential = $pjLotResidentialGrouped[0];

            $parcelCity = $pjLotResidential->parcel_city;
            $masterRegion = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();

            $parcelCityText = '';
            $parcelCityExtra = '';
            if($parcelCity == -1){
                // $parcelCityText = 'その他';
                $parcelCityText = '';
                $parcelCityExtra = $pjLotResidential->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $parcelCity){
                        $parcelCityText = $region->name;
                    }
                }

            }

            $parcelTown = $pjLotResidential->parcel_town;
            $parcelNumberFirst = $pjLotResidential->parcel_number_first;
            $parcelNumberSecond = $pjLotResidential->parcel_number_second;

            $landNumber = $parcelCityText. $parcelCityExtra. $parcelTown. $parcelNumberFirst. '番'. $parcelNumberSecond;

            if($landNumberPjResidentialBefore == $landNumber){
                $parcelSizeResident += $pjLotResidential->parcel_size;

                $worksheet->getCell('T'.(10+($loopCounterResident-1)))->setValue($parcelSizeResident);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AU'.(10+($loopCounterResident-1)))->setValue($parcelSizeResident);

                $totalParcelSizeResident += $pjLotResidential->parcel_size;
            }else
            {
                if($loopCounterResident >= 3){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(10+$loopCounterResident);

                    $worksheet->getStyle('T'.(10+$loopCounterResident))->getAlignment()->setHorizontal('right');
                    $worksheet->getStyle('T'.(10+$loopCounterResident))->getNumberFormat()->setFormatCode('#,##0.00');

                    $worksheet->getStyle('C'.(10+$loopCounterResident))->getAlignment()->setHorizontal('left');
                    $spreadsheet->getActiveSheet()->getStyle('C'.(10+$loopCounterResident).':'.'Y'.(10+$loopCounterResident))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('FFFFFF');

                    static::$allRowsAddedCounter++;

                    $spreadsheet->getActiveSheet()->mergeCells('C'.(10+$loopCounterResident).':'.'P'.(10+$loopCounterResident)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('Q'.(10+$loopCounterResident).':'.'S'.(10+$loopCounterResident)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('T'.(10+$loopCounterResident).':'.'W'.(10+$loopCounterResident)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('X'.(10+$loopCounterResident).':'.'Y'.(10+$loopCounterResident)); // Merge cell

                    if($rowCounterPjLot <= $maxRowPjLot){
                        $spreadsheet->getActiveSheet()->mergeCells('AD'.(10+$loopCounterResident).':'.'AQ'.(10+$loopCounterResident)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AR'.(10+$loopCounterResident).':'.'AT'.(10+$loopCounterResident)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AU'.(10+$loopCounterResident).':'.'AX'.(10+$loopCounterResident)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AY'.(10+$loopCounterResident).':'.'AZ'.(10+$loopCounterResident)); // Merge cell
                    }
                }

                $worksheet->getCell('C'.(10+$loopCounterResident))->setValue($landNumber);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AD'.(10+$loopCounterResident))->setValue($landNumber);

                $worksheet->getCell('Q'.(10+$loopCounterResident))->setValue('土地');

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AR'.(10+$loopCounterResident))->setValue('土地');

                $parcelSizeResident = $pjLotResidential->parcel_size;
                $worksheet->getStyle('T'.(10+$loopCounterResident))->getNumberFormat()->setFormatCode('#,##0.00');
                $worksheet->getCell('T'.(10+$loopCounterResident))->setValue($parcelSizeResident);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AU'.(10+$loopCounterResident))->setValue($parcelSizeResident);

                $totalParcelSizeResident += $parcelSizeResident;

                $worksheet->getCell('X'.(10+$loopCounterResident))->setValue('㎡');

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AY'.(10+$loopCounterResident))->setValue('㎡');

                $loopCounterResident++;
                $rowCounterPjLot++;
            }
            $landNumberPjResidentialBefore = $landNumber;

            if($rowCounterPjLot > $maxRowPjLot){
                $allRowsAdded = static::$allRowsAddedCounter;
                $loopCounterResident = ($allRowsAdded+31);
            }
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Output Report for (Pj Lot Road)
        // ------------------------------------------------------------------
        $landNumberPjRoadBefore = '';
        $parcelSizeRoad = 0;

        $loopCounterRoad = $loopCounterResident;
        $totalParcelSizeRoad = 0;
        foreach($retriveDataPjLotRoadlA as $pjLotRoadGrouped){
            $pjLotRoad = $pjLotRoadGrouped[0];

            $parcelCity = $pjLotRoad->parcel_city;
            $masterRegion = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();

            $parcelCityText = '';
            $parcelCityExtra = '';
            if($parcelCity == -1){
                // $parcelCityText = 'その他';
                $parcelCityText = '';
                $parcelCityExtra = $pjLotRoad->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $parcelCity){
                        $parcelCityText = $region->name;
                    }
                }

            }
            //$parcelCityExtra = $pjLotRoad->parcel_city_extra;
            $parcelTown = $pjLotRoad->parcel_town;
            $parcelNumberFirst = $pjLotRoad->parcel_number_first;
            $parcelNumberSecond = $pjLotRoad->parcel_number_second;

            $landNumber = $parcelCityText. $parcelCityExtra. $parcelTown. $parcelNumberFirst. '番'. $parcelNumberSecond;

            if($landNumberPjRoadBefore == $landNumber){
                $parcelSizeRoad += $pjLotRoad->parcel_size;
                $worksheet->getCell('T'.(10+($loopCounterRoad-1)))->setValue($parcelSizeRoad);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AU'.(10+($loopCounterRoad-1)))->setValue($parcelSizeRoad);

                $totalParcelSizeRoad += $pjLotRoad->parcel_size;
            }else{
                $parcelSizeRoad = 0;
                if($loopCounterRoad >= 3){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(10+$loopCounterRoad);

                    $worksheet->getStyle('T'.(10+$loopCounterRoad))->getAlignment()->setHorizontal('right');
                    $worksheet->getStyle('T'.(10+$loopCounterRoad))->getNumberFormat()->setFormatCode('#,##0.00');

                    $worksheet->getStyle('C'.(10+$loopCounterRoad))->getAlignment()->setHorizontal('left');
                    $spreadsheet->getActiveSheet()->getStyle('C'.(10+$loopCounterRoad).':'.'Y'.(10+$loopCounterRoad))
                                                ->getFill()
                                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                ->getStartColor()->setARGB('FFFFFF');

                    static::$allRowsAddedCounter++;

                    $spreadsheet->getActiveSheet()->mergeCells('C'.(10+$loopCounterRoad).':'.'P'.(10+$loopCounterRoad)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('Q'.(10+$loopCounterRoad).':'.'S'.(10+$loopCounterRoad)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('T'.(10+$loopCounterRoad).':'.'W'.(10+$loopCounterRoad)); // Merge cell
                    $spreadsheet->getActiveSheet()->mergeCells('X'.(10+$loopCounterRoad).':'.'Y'.(10+$loopCounterRoad)); // Merge cell

                    if($rowCounterPjLot <= $maxRowPjLot){
                        $spreadsheet->getActiveSheet()->mergeCells('AD'.(10+$loopCounterRoad).':'.'AQ'.(10+$loopCounterRoad)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AR'.(10+$loopCounterRoad).':'.'AT'.(10+$loopCounterRoad)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AU'.(10+$loopCounterRoad).':'.'AX'.(10+$loopCounterRoad)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('AY'.(10+$loopCounterRoad).':'.'AZ'.(10+$loopCounterRoad)); // Merge cell
                    }
                }

                $worksheet->getCell('C'.(10+$loopCounterRoad))->setValue($landNumber);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AD'.(10+$loopCounterRoad))->setValue($landNumber);

                $worksheet->getCell('Q'.(10+$loopCounterRoad))->setValue('道路');

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AR'.(10+$loopCounterRoad))->setValue('道路');

                $parcelSizeRoad = $pjLotRoad->parcel_size;
                $worksheet->getStyle('T'.(10+$loopCounterRoad))->getNumberFormat()->setFormatCode('#,##0.00');
                $worksheet->getCell('T'.(10+$loopCounterRoad))->setValue($parcelSizeRoad);

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AU'.(10+$loopCounterRoad))->setValue($parcelSizeRoad);

                $totalParcelSizeRoad += $parcelSizeRoad;

                $worksheet->getCell('X'.(10+$loopCounterRoad))->setValue('㎡');

                if($rowCounterPjLot <= $maxRowPjLot)
                    $worksheet->getCell('AY'.(10+$loopCounterRoad))->setValue('㎡');

                $loopCounterRoad++;
                $rowCounterPjLot++;
            }
            $landNumberPjRoadBefore = $landNumber;

            if($rowCounterPjLot > $maxRowPjLot){
                $allRowsAdded = static::$allRowsAddedCounter;
                $loopCounterRoad = ($allRowsAdded+31);
                //dd($loopCounterRoad);
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for (Pj Lot Building)
        // ------------------------------------------------------------------
        $landNumberBefore = '';
        $totalFloorSize = 0;

        $loopCounterBuilding = $loopCounterRoad;
        $totalFloorSizeBuilding = 0;
        foreach($retriveDataPjLotBuildingA as $pjLotBuildingGrouped){
            $pjLotBuilding = $pjLotBuildingGrouped[0];

            $pjBuildingFloorSize = PjBuildingFloorSize::where('pj_lot_building_a_id', $pjLotBuilding->id)->get();
            foreach($pjBuildingFloorSize as $floorSize){

                $parcelCity = $pjLotBuilding->parcel_city; // A44-2
                $masterRegion = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();

                $parcelCityText = '';
                $parcelCityExtra = '';
                if($parcelCity == -1){
                    // $parcelCityText = 'その他';
                    $parcelCityText = '';
                    $parcelCityExtra = $pjLotBuilding->parcel_city_extra; //A44-3
                }else{
                    foreach($masterRegion as $region){
                        if($region->id == $parcelCity){
                            $parcelCityText = $region->name;
                        }
                    }

                }
                //$parcelCityExtra = $pjLotBuilding->parcel_city_extra; // A44-3
                $parcelTown = $pjLotBuilding->parcel_town; // A44-4

                $parcelNumberFirst  = $pjLotBuilding->building_number_first; // A44-7
                $parcelNumberSecond = $pjLotBuilding->building_number_second; // A44-8
                $parcelNumberThird  = $pjLotBuilding->building_number_third; // A44-9
                $parcelNumberThird  = $parcelNumberThird ? 'の' .$parcelNumberThird : '';

                $landNumber = $parcelCityText. $parcelCityExtra. $parcelTown.
                              $parcelNumberFirst. '番'. $parcelNumberSecond.
                              $parcelNumberThird;

                if($landNumberBefore == $landNumber){
                    $totalFloorSize += $floorSize->floor_size;
                    $worksheet->getCell('T'.(10+($loopCounterBuilding-1)))->setValue($totalFloorSize);

                    $worksheet->getCell('AU'.(10+($loopCounterBuilding-1)))->setValue($totalFloorSize);
                    if($loopCounterBuilding > 50){
                        $worksheet->getCell('AU'.(10+($loopCounterBuilding-1)))->setValue('');
                    }

                    $totalFloorSizeBuilding += $floorSize->floor_size;
                }
                else{
                    $totalFloorSize = 0;
                    if($loopCounterBuilding >= 3){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(10+$loopCounterBuilding);

                        $worksheet->getStyle('T'.(10+$loopCounterBuilding))->getAlignment()->setHorizontal('right');
                        $worksheet->getStyle('T'.(10+$loopCounterBuilding))->getNumberFormat()->setFormatCode('#,##0.00');

                        $worksheet->getStyle('C'.(10+$loopCounterBuilding))->getAlignment()->setHorizontal('left');
                        $spreadsheet->getActiveSheet()->getStyle('C'.(10+($loopCounterBuilding)).':'.'Y'.(10+($loopCounterBuilding)))
                                                ->getFill()
                                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                ->getStartColor()->setARGB('FFFFFF');

                        static::$allRowsAddedCounter++;

                        $spreadsheet->getActiveSheet()->mergeCells('C'.(10+$loopCounterBuilding).':'.'P'.(10+$loopCounterBuilding)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('Q'.(10+$loopCounterBuilding).':'.'S'.(10+$loopCounterBuilding)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('T'.(10+$loopCounterBuilding).':'.'W'.(10+$loopCounterBuilding)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('X'.(10+$loopCounterBuilding).':'.'Y'.(10+$loopCounterBuilding)); // Merge cell
                        if($rowCounterPjLot <= $maxRowPjLot){
                            $spreadsheet->getActiveSheet()->mergeCells('AD'.(10+$loopCounterBuilding).':'.'AQ'.(10+$loopCounterBuilding)); // Merge cell
                            $spreadsheet->getActiveSheet()->mergeCells('AR'.(10+$loopCounterBuilding).':'.'AT'.(10+$loopCounterBuilding)); // Merge cell
                            $spreadsheet->getActiveSheet()->mergeCells('AU'.(10+$loopCounterBuilding).':'.'AX'.(10+$loopCounterBuilding)); // Merge cell
                            $spreadsheet->getActiveSheet()->mergeCells('AY'.(10+$loopCounterBuilding).':'.'AZ'.(10+$loopCounterBuilding)); // Merge cell
                        }
                    }
                    $worksheet->getCell('C'.(10+$loopCounterBuilding))->setValue($landNumber);

                    if($rowCounterPjLot <= $maxRowPjLot)
                        $worksheet->getCell('AD'.(10+$loopCounterBuilding))->setValue($landNumber);

                    $worksheet->getCell('Q'.(10+$loopCounterBuilding))->setValue('建物');

                    if($rowCounterPjLot <= $maxRowPjLot)
                        $worksheet->getCell('AR'.(10+$loopCounterBuilding))->setValue('建物');

                    $totalFloorSize = $floorSize->floor_size;
                    $worksheet->getStyle('T'.(10+$loopCounterBuilding))->getNumberFormat()->setFormatCode('#,##0.00');
                    $worksheet->getCell('T'.(10+$loopCounterBuilding))->setValue($totalFloorSize);

                    if($rowCounterPjLot <= $maxRowPjLot)
                        $worksheet->getCell('AU'.(10+$loopCounterBuilding))->setValue($totalFloorSize);

                    $totalFloorSizeBuilding += $floorSize->floor_size;

                    $worksheet->getCell('X'.(10+$loopCounterBuilding))->setValue('㎡');

                    if($rowCounterPjLot <= $maxRowPjLot)
                        $worksheet->getCell('AY'.(10+$loopCounterBuilding))->setValue('㎡');

                    $loopCounterBuilding++;
                    $rowCounterPjLot++;
                }
                $landNumberBefore = $landNumber;
            }
            if($rowCounterPjLot > $maxRowPjLot){
                $allRowsAdded = static::$allRowsAddedCounter;
                $loopCounterBuilding = ($allRowsAdded+31);
                //dd($loopCounterBuilding);
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for Total
        // ------------------------------------------------------------------
        if($rowCounterPjLot <= 9){
            //dd(static::$allRowsAddedCounter);
            $totalRowAdded = static::$allRowsAddedCounter;
            $spreadsheet->getActiveSheet()->removeRow(39+$totalRowAdded, 2);
            //$spreadsheet->getActiveSheet()->unmergeCells('C'.(40+$totalRowAdded).':'.'P'.(40+$totalRowAdded)); // UnMerge cell
            //$spreadsheet->getActiveSheet()->unmergeCells('Q'.(40+$totalRowAdded).':'.'S'.(40+$totalRowAdded)); // UnMerge cell
            //$spreadsheet->getActiveSheet()->unmergeCells('T'.(40+$totalRowAdded).':'.'Y'.(40+$totalRowAdded)); // UnMerge cell
        }
        if($maxRowPjLot < $rowCounterPjLot ){
            $loopCounterBuilding = 9;

            $spreadsheet->getActiveSheet()->mergeCells('C19:P19');
            $spreadsheet->getActiveSheet()->getStyle('C19')->getAlignment()->setHorizontal('left')->setWrapText(true);
            $worksheet->getCell('C19')->setValue("(その他別紙参照)");

            $spreadsheet->getActiveSheet()->mergeCells('AD19:AQ19');
            $spreadsheet->getActiveSheet()->getStyle('AD19')->getAlignment()->setHorizontal('left')->setWrapText(true);
            $worksheet->getCell('AD19')->setValue("(その他別紙参照)");

            $spreadsheet->getActiveSheet()->getPageSetup()->setScale(10);
            $totalRowsAdded = static::$allRowsAddedCounter;
            $extraLot = $rowCounterPjLot - $loopCounterBuilding;
            $spreadsheet->getActiveSheet()->setBreak('A'.(38 + $totalRowsAdded - $extraLot), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
        }


        if($maxRowPjLot < 8){
            //dd($maxRowPjLot);
            $loopCounterBuilding = (10 - ($rowCounterPjLot - $maxRowPjLot));
        }

        //dd($loopCounterBuilding);

        $worksheet->getCell('F'.(10+($loopCounterBuilding+1)))->setValue($totalParcelSizeResident);
        $worksheet->getCell('AG'.(10+($loopCounterBuilding+1)))->setValue($totalParcelSizeResident);

        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(4);

        $spreadsheet->getActiveSheet()->getColumnDimension('AG')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('AH')->setWidth(4);

        $worksheet->getCell('M'.(10+($loopCounterBuilding+1)))->setValue($totalParcelSizeRoad);
        $worksheet->getCell('AN'.(10+($loopCounterBuilding+1)))->setValue($totalParcelSizeRoad);

        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(4);

        $spreadsheet->getActiveSheet()->getColumnDimension('AN')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('AO')->setWidth(4);

        $worksheet->getCell('T'.(10+($loopCounterBuilding+1)))->setValue($totalFloorSizeBuilding);
        $worksheet->getCell('AU'.(10+($loopCounterBuilding+1)))->setValue($totalFloorSizeBuilding);

        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(4);

        $spreadsheet->getActiveSheet()->getColumnDimension('AU')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('AV')->setWidth(4);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output report for 売渡承諾書 (Sales consent form	) B-1
        // ------------------------------------------------------------------
        $retriveDataPurchaseSale = PjPurchaseSale::with('organizer')->where('project_id', $projectId)->get();

        $worksheet->getCell('AC1')->setValue('売渡承諾書')->getStyle()->getFont()->setBold(true);

        if (count($retriveDataPurchaseSale) > 0) {
            foreach ($retriveDataPurchaseSale as $key => $purchaseSale) {
                $worksheet->getCell('AC4')->setValue($purchaseSale->organizer['name'].' 殿')->getStyle()->getFont()->setUnderline(true);
            }
        }

        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            //
            $spreadsheet->getActiveSheet()->unmergeCells('AC6:AZ6');
            $spreadsheet->getActiveSheet()->mergeCells('AC6:AZ7');
            $spreadsheet->getActiveSheet()->getStyle('AC6')->getAlignment()->setHorizontal('left')->setWrapText(true);
            if ($dataPurchaseDoc->gathering_request_title == 1) {
                $worksheet->getCell('AC6')->setValue('私は、御社が提出した別紙'.'取り纏め依頼書'.'に対して、以下の条件で売り渡すことを承諾しました。');
            } else {
                $worksheet->getCell('AC6')->setValue('私は、御社が提出した別紙'.'買付証明書'.'に対して、以下の条件で売り渡すことを承諾しました。');
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create Output
        // ------------------------------------------------------------------
        $writer = IOFactory::createWriter( $spreadsheet, 'Xlsx' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $fileName = '';
        foreach($retriveDataPurchaseDocs as $dataPurchaseDoc){
            if($dataPurchaseDoc->gathering_request_title == 1){
                $fileName = '取り纏め依頼書';
                $spreadsheet->getActiveSheet()->setTitle('取り纏め依頼書');
            }else{
                $fileName = '買付証明書';
                $spreadsheet->getActiveSheet()->setTitle('買付証明書');
            }
        }

        $public = public_path();
        $projectID = $projectId;
        $targetID = $purchaseId;
        $projectHash = sha1( "port-project-$projectID" );
        $targetHash = sha1( "port-sheet-$targetID" );
        // ------------------------------------------------------------------
        $directory = "reports/output/{$projectHash}/{$targetHash}";
        $filepath = "{$directory}/{$fileName}-{$targetID}.xlsx";
        // ------------------------------------------------------------------
        File::makeDirectory( "{$public}/{$directory}", 0777, true, true );
        $writer->save( "{$public}/{$filepath}" );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return url( $filepath );
        // ------------------------------------------------------------------
    }

    static function checkBoxInputOutput($worksheet, $targetRow, $rowCounter){
        $worksheet->getCell('AE'.($targetRow+$rowCounter))->setValue('□');
        $worksheet->getCell('AF'.($targetRow+$rowCounter))->setValue('応');

        $worksheet->getCell('AH'.($targetRow+$rowCounter))->setValue('□');
        $worksheet->getCell('AI'.($targetRow+$rowCounter))->setValue('否');
    }

    static function checkBoxInputOutputContent($worksheet, $spreadsheet, $targetRow, $rowCounter){
        $worksheet->getCell('AE'.($targetRow+$rowCounter))->setValue('□');
        $worksheet->getCell('AF'.($targetRow+$rowCounter))->setValue('有');

        $spreadsheet->getActiveSheet()->mergeCells('AG'.($targetRow+$rowCounter).':'.'AH'.($targetRow+$rowCounter));
        $spreadsheet->getActiveSheet()->mergeCells('AI'.($targetRow+$rowCounter).':'.'AQ'.($targetRow+$rowCounter));
        $worksheet->getCell('AG'.($targetRow+$rowCounter))->setValue('(内容:');
        $worksheet->getCell('AI'.($targetRow+$rowCounter))->setValue('___________________________)')->getStyle()->getAlignment()->setVertical('bottom');;
        $spreadsheet->getActiveSheet()->getStyle('AI'.($targetRow+$rowCounter).':'.'AQ'.($targetRow+$rowCounter))->getAlignment()->setWrapText(false); // Wrap Text

        $worksheet->getCell('AR'.($targetRow+$rowCounter))->setValue('□');
        $worksheet->getCell('AS'.($targetRow+$rowCounter))->setValue('無');
    }

    static function createDynamicRowsContractCondition($worksheet, $spreadsheet, $data, $dataCounter, $numberCounter){
        if(strlen($data) < 100){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter); // Create new row
            static::$allRowsAddedCounter++;
        }else if(strlen($data) < 200){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter, 2); // Create new row
            //$spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter); // Create new row
            static::$allRowsAddedCounter+=2;
        }
        else if(strlen($data) < 300){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter, 3); // Create new row
            //$spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter); // Create new row
            //$spreadsheet->getActiveSheet()->insertNewRowBefore(25+$dataCounter); // Create new row
            static::$allRowsAddedCounter+=3;
        }

        if(strlen($data) < 100){
            $worksheet->getCell('C'.(25+$dataCounter))->setValue('('.$numberCounter.')');
            $worksheet->getCell('AD'.(25+$dataCounter))->setValue('('.$numberCounter.')');

            $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$dataCounter).':'.'Y'.(25+$dataCounter));
            //$spreadsheet->getActiveSheet()->mergeCells('AE'.(25+$dataCounter).':'.'AZ'.(25+$dataCounter));

            $worksheet->getCell('D'.(25+$dataCounter))->setValue($data);
            //$worksheet->getCell('AE'.(25+$dataCounter))->setValue($data);
            PurchaseContractReport::checkBoxInputOutput($worksheet, 25, $dataCounter);

            $dataCounter++;
            $numberCounter++;
        }
        elseif(strlen($data) < 200){
            $worksheet->getCell('C'.(25+$dataCounter))->setValue('('.$numberCounter.')');
            $worksheet->getCell('AD'.(25+$dataCounter))->setValue('('.$numberCounter.')');

            $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$dataCounter).':'.'Y'.(25+($dataCounter+1)));
            $spreadsheet->getActiveSheet()->getStyle('D'.(25+$dataCounter).':'.'Y'.(25+($dataCounter+1)))->getAlignment()->setWrapText(true); // Wrap Text

            //$spreadsheet->getActiveSheet()->mergeCells('AE'.(25+$dataCounter).':'.'AZ'.(25+($dataCounter+1)));
            //$spreadsheet->getActiveSheet()->getStyle('AE'.(25+$dataCounter).':'.'AZ'.(25+($dataCounter+1)))->getAlignment()->setWrapText(true); // Wrap Text

            $worksheet->getCell('D'.(25+$dataCounter))->setValue($data);
            //$worksheet->getCell('AE'.(25+$dataCounter))->setValue($data);
            PurchaseContractReport::checkBoxInputOutput($worksheet, 25, $dataCounter);

            $dataCounter += 2;
            $numberCounter++;
        }elseif(strlen($data) < 300){
            $worksheet->getCell('C'.(25+$dataCounter))->setValue('('.$numberCounter.')');
            $worksheet->getCell('AD'.(25+$dataCounter))->setValue('('.$numberCounter.')');

            $spreadsheet->getActiveSheet()->mergeCells('D'.(25+$dataCounter).':'.'Y'.(25+($dataCounter+2)));
            $spreadsheet->getActiveSheet()->getStyle('D'.(25+$dataCounter).':'.'Y'.(25+($dataCounter+2)))->getAlignment()->setWrapText(true); // Wrap Text

            //$spreadsheet->getActiveSheet()->mergeCells('AE'.(25+$dataCounter).':'.'AZ'.(25+($dataCounter+2)));
            //$spreadsheet->getActiveSheet()->getStyle('AE'.(25+$dataCounter).':'.'AZ'.(25+($dataCounter+2)))->getAlignment()->setWrapText(true); // Wrap Text

            $worksheet->getCell('D'.(25+$dataCounter))->setValue($data);
            //$worksheet->getCell('AE'.(25+$dataCounter))->setValue($data);
            PurchaseContractReport::checkBoxInputOutput($worksheet, 25, $dataCounter);

            $dataCounter += 3;
            $numberCounter++;
        }
        return $dataCounter;
    }

}
