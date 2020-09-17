<?php

namespace App\Reports;

// --------------------------------------------------------------------------
use App\Models\PjProperty;
use App\Models\MasterValue;
use App\Models\PjLotResidentialA;
use App\Models\PjLotRoadA;
use App\Models\PjLotBuildingA;
use App\Models\PjLotResidentialOwner;
use App\Models\PjLotRoadOwner;
use App\Models\PjLotBuildingOwner;
use App\Models\PjBuildingFloorSize;
use App\Models\Company;
use App\Models\CompanyOffice;
use App\Models\User;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseContractCreate;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseSale;
use App\Models\MasterRegion;
use App\Models\PjPurchaseTargetBuilding;
use App\Models\PjPurchaseContractMediation;
// --------------------------------------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
// --------------------------------------------------------------------------

use File;
use Carbon\Carbon;
use Auth;
// --------------------------------------------------------------------------

class PurchaseContractCreateReport
{
    private static $numberOfRow = 0;
    // ----------------------------------------------------------------------
    // Create Report Output Of PurchaseContract
    // ----------------------------------------------------------------------

    public static function reportPurchaseContractCreate($data, $projectId, $targetId){
        ini_set('max_execution_time', 250);

        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('reports/template/purchase-contract-create.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue("F148", "1. "." ")
            ->setCellValue("F154", "1. "." ")
            ->setCellValue("F156", "2. "." ")
            ->setCellValue("F162", "1. "." ")
            ->setCellValue("F165", "2. "." ")
            ->setCellValue("F172", "1. "." ")
            ->setCellValue("F201", "1. "." ")
            ->setCellValue("F204", "2. "." ")
            ->setCellValue("F207", "3. "." ")
            ->setCellValue("F220", "1. "." ")
            ->setCellValue("F225", "2. "." ")
            ->setCellValue("F228", "3. "." ")
            ->setCellValue("F243", "1. "." ")
            ->setCellValue("F260", "1. "." ")
            ->setCellValue("F269", "1. "." ")
            ->setCellValue("F287", "2. "." ")
            ->setCellValue("F296", "3. "." ")
            ->setCellValue("F299", "4. "." ")
            ->setCellValue("F302", "5. "." ")
            ->setCellValue("F305", "6. "." ")
            ->setCellValue("F308", "7. "." ")
            ->setCellValue("F311", "8. "." ")
            ->setCellValue("F319", "1. "." ")
            ->setCellValue("F322", "2. "." ")
            ->setCellValue("F327", "3. "." ")
            ->setCellValue("F330", "4. "." ")
            ->setCellValue("F341", "1. "." ")
            ->setCellValue("F350", "1. "." ")
            ->setCellValue("F353", "2. "." ")
            ->setCellValue("F367", "3. "." ")
            ->setCellValue("F373", "1. "." ")
            ->setCellValue("F379", "1. "." ")
            ->setCellValue("F382", "2. "." ")
            ->setCellValue("F387", "1. "." ")
            ->setCellValue("F389", "2. "." ")
            ->setCellValue("F394", "1. "." ")
            ->setCellValue("F400", "1. "." ");
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Retrive data from database
        // ------------------------------------------------------------------
        $purchaseContractId = 1;

        $getPropertiesId = PjProperty::where('project_id', $projectId)->first();
        //dd($getPropertiesId);

        $getPurchaseContractCreate = PjPurchaseContractCreate::where('pj_purchase_contract_id', $purchaseContractId)->first();
        /*$getPurchaseContractCreate = (object) array(
            'notices_residential_contract' => '本物件資料から上下水道が直ちに利用可能であると認識しております。利用可能であることを契約条件とさせて下さい。',
            'notices_road_contract' => '売買対象面積は公簿面積で確定するものとし、公簿面積と実測面積に変動ある場合でも、売買代金清算はしないものとさせて下さい。',
            'notices_building_contract' => '売買対象面積について、公簿面積と実測面積とに変動ある場合、実測面積を基準として売買代金清算とさせて下さい。尚、実測清算の対象は宅地部分に限るものし、道路部分は含みません。',
            'c_article4_contract' => 2,
            'c_article4_sub_text_contract' => '公簿面積と実測面積とに変動ある場合、実測面積を基準として売',
            'c_article4_clearing_standard_area' => 123,
            'c_article12_contract_text' => '実測面積を基準として売',
            'c_article15_loan_contract_0' => '公簿面積と実測面積とに変動ある場合',
            'c_article15_loan_contract_1' => '実測面積を基準として売',
            'c_article15_loan_amount_contract_0' => '契約条件とさせて下',
            'c_article15_loan_amount_contract_1' => '売買代金清算はしないもの',
            'c_article15_loan_release_date_contract' => '2020/05/01',
            'c_article23_confirm_write' => '下水道が直ちに利用可能',
            'c_article23_confirm' => 2,
            'property_description_kind' => 1,
            'c_article23_create_date' => '2020/05/01',
            'c_article23_creator' => 'Robert Downey, Jr',
            'c_article23_other' => '利用可能であることを契約条件と',
            'c_article10_contract' => 1,
            'c_article12_contract' => 1,
            //'c_article15_contract' => 2, // Update from https://trello.com/c/rCI0r79B/111-a14-detailchange-the-column-name
            'c_article15_contract' => 2, // Update from https://trello.com/c/rCI0r79B/111-a14-detailchange-the-column-name
            //'c_article15_contract' => 1, // Update from https://trello.com/c/rCI0r79B/111-a14-detailchange-the-column-name
            'c_article16_contract' => 1, // Update from https://trello.com/c/rCI0r79B/111-a14-detailchange-the-column-name
            'project_buy_building_number' => 123,
        );*/
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for B.売買代金および支払い方法等
        // ------------------------------------------------------------------
        $getTaxRateMasterValue = MasterValue::where('type', 'general_tax_rate')->first();

        //$retriveDataPurchaseContract = $data->purchase_targets[0]->purchase_contract;
        $retriveDataPurchaseContract = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();

        //$retriveDataPurchaseContract = PjPurchaseTarget::with(['purchase_contract.purchase_contract_mediations', 'purchase_contract.purchase_contract_deposits'])->where('pj_purchase_id', $targetId)->get();

        $pjPurchaseTargetBuilding = PjPurchaseTargetBuilding::where('pj_purchase_target_id', $targetId)->first();

        if($pjPurchaseTargetBuilding->kind == 1){
            $worksheet->getCell('AE50')->setValue(number_format($retriveDataPurchaseContract->contract_price). '円'); // Output for 売 買 代 金 総 額

            $calculateContractPrice = $retriveDataPurchaseContract->contract_price - $retriveDataPurchaseContract->contract_price_building;
            $worksheet->getCell('AE51')->setValue(number_format($calculateContractPrice). '円'); // Output for 土地代金(b)

            if($retriveDataPurchaseContract->contract_price_building_no_tax == 0){
                $calculateTaxConsumption = ($retriveDataPurchaseContract->contract_price_building * $getTaxRateMasterValue->value / (100 + $getTaxRateMasterValue->value));
                $worksheet->getCell('AE52')->setValue( number_format($calculateTaxConsumption). '円'); // Output for (うち消費税額及び地方消費税額の合計額)
            }else{
                $worksheet->getCell('AE52')->setValue('-'.'円');
            }
        }else{
            for($i = 0; $i < 3; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(50+$i)->setVisible(false);
            }
        }

        $worksheet->getCell('AE53')->setValue(number_format($retriveDataPurchaseContract->contract_deposit). '円'); // Output for 手付金

        if($getPurchaseContractCreate->c_article4_contract == 2){
            $worksheet->getCell('P59')->setValue('■');
            $worksheet->getCell('P60')->setValue('■');
            $worksheet->getCell('P61')->setValue('■');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （物件状況の告知） Article 23
        // ------------------------------------------------------------------
        if($retriveDataPurchaseContract->contract_building_kind != 1){
            $spreadsheet->getActiveSheet()->removeRow(397,19);
        }
        // ------------------------------------------------------------------

        $user = Auth::user();
        $prefectureName = MasterValue::find($user->real_estate_notary_prefecture_id);
        $purchaseSale = PjPurchaseSale::where('project_id', $projectId)->first();

        $company = Company::find($purchaseSale->company_id_organizer);

        $masterValueLicenseOrgan = MasterValue::where('id', $company->license_authorizer_id)->where('type', 'realestate_license_organ')->first();

        $worksheet->getCell('I104')->setValue($masterValueLicenseOrgan['value'] .'('. $company->license_update . ')'. $company->license_number);
        $worksheet->getCell('I105')->setValue($company->real_estate_agent_office_main_address);
        $worksheet->getCell('I106')->setValue($company->name);
        $worksheet->getCell('I107')->setValue($company->real_estate_agent_representative_name);

        //dd($purchaseSale->organizer_realestate_explainer);
        $userRealEstateExplainer = User::find($purchaseSale->organizer_realestate_explainer);
        //dd($userRealEstateExplainer);
        //dd($prefectureName->name);
        $worksheet->getCell('AH104')->setValue('('.$prefectureName['value'].')'. $userRealEstateExplainer->real_estate_notary_number);
        $worksheet->getCell('AH105')->setValue($userRealEstateExplainer->last_name . ' ' . $userRealEstateExplainer->first_name );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （印紙の負担区分） Article 15
        // ------------------------------------------------------------------
        //dd($getPurchaseContractCreate);
        //dd($getPurchaseContractCreate->c_article16_contract. '-' . $getPurchaseContractCreate->c_article16_burden_contract. '-' . $getPurchaseContractCreate->c_article16_base_contract);
        PurchaseContractCreateReport::article16Condition($worksheet, $spreadsheet,
            $getPurchaseContractCreate->c_article16_contract,
            $getPurchaseContractCreate->c_article16_burden_contract,
            $getPurchaseContractCreate->c_article16_base_contract);
        //PurchaseContractCreateReport::article16Condition($worksheet, $spreadsheet, 1,3,2);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （契約の解除・違約金・手付金の効力） Article 13
        // ------------------------------------------------------------------
        $purchaseContract = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();
        PurchaseContractCreateReport::article13Condition($worksheet, $spreadsheet,$purchaseContract->seller);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （契約の解除・違約金・手付金の効力） Article 12
        // ------------------------------------------------------------------
        PurchaseContractCreateReport::article12Condition($worksheet, $spreadsheet,$purchaseContract->seller);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （瑕疵担保責任）or Article 10
        // ------------------------------------------------------------------
        $pjLotBuilding = PjLotBuildingA::where('pj_property_id', $getPropertiesId->id)->first();
        //dd($purchaseContract->seller . '-' . $pjLotBuilding->exists_building_residential);
        PurchaseContractCreateReport::article10Condition($worksheet, $spreadsheet,
            $purchaseContract->seller,
            $pjLotBuilding->exists_building_residential);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （引き渡し）or Article 8
        // ------------------------------------------------------------------

        // Branch 2
        $getArticle8ContractText = $getPurchaseContractCreate->c_article8_contract_text;
        //$getArticle8ContractText = 'Test 123';

        //PurchaseContractCreateReport::article8Condition($worksheet, $spreadsheet, 2, array(2,2,2,1), array(2,4), $getArticle8ContractText);
        PurchaseContractCreateReport::article8Condition($worksheet, $spreadsheet, 2,
                    array($retriveDataPurchaseContract->contract_building_kind,
                            $retriveDataPurchaseContract->ontract_building_unregistered_kind,
                            $getPurchaseContractCreate->property_description_dismantling,
                            $getPurchaseContractCreate->property_description_removal_by_buyer),
                    array($retriveDataPurchaseContract->mediation,
                            $getPurchaseContractCreate->c_article8_contract), $getArticle8ContractText);

        // Branch 1
        //PurchaseContractCreateReport::article8Condition($worksheet, $spreadsheet, 1, array(2,2,2,1), array(2,1), $getArticle8ContractText);
        PurchaseContractCreateReport::article8Condition($worksheet, $spreadsheet, 1,
                    array($retriveDataPurchaseContract->contract_building_kind,
                            $retriveDataPurchaseContract->ontract_building_unregistered_kind,
                            $getPurchaseContractCreate->property_description_dismantling,
                            $getPurchaseContractCreate->property_description_removal_by_buyer),
                    array($retriveDataPurchaseContract->mediation,
                            $getPurchaseContractCreate->c_article8_contract), $getArticle8ContractText);

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （支払い遅延及び受領遅延）or Article 7
        // ------------------------------------------------------------------
        $purchaseSale = PjPurchaseSale::where('project_id', $projectId)->first();
        if($purchaseSale->project_urbanization_area_sub != 1){
            for($i = 0; $i < 3; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(207+$i)->setVisible(false);
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （所有権の移転、登記）or Article 6
        // ------------------------------------------------------------------
        //dd($purchaseSale->project_urbanization_area_sub . '-' .  $getPurchaseContractCreate->c_article6_1_contract . '-'. $getPurchaseContractCreate->c_article6_2_contract. '-' .$purchaseSale->offer_date);
        PurchaseContractCreateReport::article6Condition($worksheet, $spreadsheet,
            $purchaseSale->project_urbanization_area_sub,
            $getPurchaseContractCreate->c_article6_1_contract,
            $getPurchaseContractCreate->c_article6_2_contract, $purchaseSale->offer_date);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （境界の明示）or Article 5
        // Parameter worksheet, spreadsheet, c_article5_fixed_survey_contract, c_article5_burden_contract, c_article5_burden2_contract, c_article5_fixed_survey_options_contract
        // ------------------------------------------------------------------
        PurchaseContractCreateReport::article5Condition($worksheet, $spreadsheet,
            $getPurchaseContractCreate->c_article5_fixed_survey_contract,
            $getPurchaseContractCreate->c_article5_burden_contract,
            $getPurchaseContractCreate->c_article5_burden2_contract,
            $getPurchaseContractCreate->c_article5_fixed_survey_options_contract);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for （売買対象面積）or Article 4
        // ------------------------------------------------------------------
        PurchaseContractCreateReport::article4Output($worksheet, $spreadsheet, $getPurchaseContractCreate->c_article4_contract);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for vow description
        // ------------------------------------------------------------------
        PurchaseContractCreateReport::vowDescription($worksheet, $spreadsheet,
            $getPurchaseContractCreate->c_article16_contract,
            $getPurchaseContractCreate->c_article16_burden_contract,
            $getPurchaseContractCreate->c_article16_base_contract);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output for 《媒介業者》 and 〈宅地建物取引士〉
        // ------------------------------------------------------------------

        $loopCounterMediation = 0;
        $rowBlockAdded = 0;

        $nextTitleMedia = 0;
        $nextTitleTrader = 0;
        $houseBuilderBySeller = false;

        for($i = 1; $i <= 2; $i++){
            if($i == 1){
                $getMediationHouseBuilder = PjPurchaseContractMediation::where('pj_purchase_contract_id', $targetId)->get();
            }else if($i == 2){
                $getMediationHouseBuilder = PjPurchaseContractCreate::select('broker_housebuilder_by_seller')->where('pj_purchase_contract_id', $targetId)->get();
                $checkSellerBrokerCompanyId = PjPurchaseContract::select('seller_broker_company_id')->where('pj_purchase_target_id', $targetId)->first();

                if(empty($getMediationHouseBuilder)){
                    continue 1;
                }
                if(empty($checkSellerBrokerCompanyId->seller_broker_company_id)){
                    continue 1;
                }
            }

            foreach($getMediationHouseBuilder as $builderTarget){
                if($loopCounterMediation % 2 == 0){
                    // ------------------------------------------------------------------
                    // Display in left position
                    // ------------------------------------------------------------------
                    if($houseBuilderBySeller == true){
                        continue 2;
                    }
                    if($loopCounterMediation >= 1){
                        // ------------------------------------------------------------------
                        // Created dynamic rows
                        // ------------------------------------------------------------------
                        $titleCounter = 0;
                        for($j = 0; $j < 2; $j++){
                            $spreadsheet->getActiveSheet()->insertNewRowBefore((127+$rowBlockAdded)+$j); // Create new row
                            $titleCounter++;
                        }
                        $nextTitleMedia = $titleCounter;
                        $worksheet->getCell('B'.((127+$rowBlockAdded)+($titleCounter-2)))->setValue('《媒介業者》')->getStyle()->getFont()->setSize(18);
                        $worksheet->getStyle('B'.((127+$rowBlockAdded)+($titleCounter-2)))->getFont()->setBold(true);
                        $worksheet->getStyle('B'.((127+$rowBlockAdded)+($titleCounter-2)))->getAlignment()->setVertical('center');

                        $arrTittleMedia = ['免許証番号', '事務所所在地', '商号', '代 表 者 等', '電話', ''];
                        for($k = 0; $k < 6; $k++){
                            $spreadsheet->getActiveSheet()->insertNewRowBefore(((129+$rowBlockAdded)+$k)); // Create new row
                            $worksheet->getCell('B'.((129+$rowBlockAdded) + $k))->setValue($arrTittleMedia[$k]);
                            $worksheet->getStyle('B'.(129+$k))->getFont()->setSize(14);
                        }
                        $worksheet->getStyle('B'.((127+$rowBlockAdded)+($titleCounter-2)))->getFont()->setSize(18);

                        $titleCounter = 0;
                        for($l = 0; $l < 2; $l++){
                            $spreadsheet->getActiveSheet()->insertNewRowBefore(((135+$rowBlockAdded)+$l)); // Create new row
                            $titleCounter++;
                        }
                        $nextTitleTrader = $titleCounter;

                        $worksheet->getCell('B'.((135+$rowBlockAdded)+($titleCounter-2)))->setValue('〈宅地建物取引士〉');
                        $worksheet->getStyle('B'.((135+$rowBlockAdded)+($titleCounter-2)))->getFont()->setSize(18);
                        $worksheet->getStyle('B'.((135+$rowBlockAdded)+($titleCounter-2)))->getFont()->setBold(true);
                        $worksheet->getStyle('B'.((135+$rowBlockAdded)+($titleCounter-2)))->getAlignment()->setVertical('center');

                        $arrTittleTrader = ['登録番号', '氏名', '', ''];
                        for($m = 0; $m < 4; $m++){
                            $spreadsheet->getActiveSheet()->insertNewRowBefore(((137+$rowBlockAdded)+$m)); // Create new row
                            $worksheet->getCell('B'.((137+$rowBlockAdded)+$m))->setValue($arrTittleTrader[$m]);
                            $worksheet->getStyle('B'.(137+$m))->getFont()->setSize(14);
                        }
                        //$rowBlockAdded = $rowBlockAdded + 14;
                        // ------------------------------------------------------------------
                    }
                    if($i == 1){
                        $retriveDataUser = User::find($builderTarget->trader_company_user);
                        $retriveDataCompany = Company::find($builderTarget->trader_company_id);
                        $retriveCompanyOffice = CompanyOffice::find($retriveDataUser['real_estate_notary_office_id']);
                        $prefectureName = MasterValue::find($retriveDataUser['real_estate_notary_prefecture_id']);
                    }else if($i == 2){
                        $retriveDataUser = User::find($builderTarget->broker_housebuilder_by_seller);

                        $pjPurchaseConctractBrokerCompany = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();
                        $retriveDataCompany = Company::find($pjPurchaseConctractBrokerCompany->seller_broker_company_id);
                        $retriveCompanyOffice = CompanyOffice::find($retriveDataUser['real_estate_notary_office_id']);
                        $prefectureName = MasterValue::find($retriveDataUser['real_estate_notary_prefecture_id']);
                        $houseBuilderBySeller = true;
                    }

                    $masterValueLicenseOrgan = MasterValue::where('id', $retriveDataCompany['license_authorizer_id'])->where('type', 'realestate_license_organ')->first();

                    $worksheet->getCell('H' . (115 + $rowBlockAdded))->setValue($masterValueLicenseOrgan['value'].'('. $retriveDataCompany['license_update'].')'.$retriveDataCompany['license_number']);
                    $worksheet->getCell('H' . (116 + $rowBlockAdded))->setValue($retriveDataCompany['real_estate_agent_office_main_address']);
                    $worksheet->getCell('H' . (117 + $rowBlockAdded))->setValue($retriveDataCompany['name']);
                    $worksheet->getCell('H' . (118 + $rowBlockAdded))->setValue($retriveDataCompany['real_estate_agent_representative_name']);

                    if(!empty($retriveCompanyOffice->number)){
                        $worksheet->getCell('H' . (119 + $rowBlockAdded))->setValue($retriveCompanyOffice->number);
                    }else{
                        $worksheet->getCell('H' . (119 + $rowBlockAdded))->setValue('-');
                    }


                    $worksheet->getCell('H' . (123 + $rowBlockAdded))->setValue('('. $prefectureName['value'] . ')' . $retriveDataUser['real_estate_notary_number']);
                    $worksheet->getCell('H' . (124 + $rowBlockAdded))->setValue($retriveDataUser['last_name'] . ' ' . $retriveDataUser['first_name']);

                    $loopCounterMediation++;

                }
                else{
                    // ------------------------------------------------------------------
                    // Display in right position
                    // ------------------------------------------------------------------
                    if($houseBuilderBySeller == true){
                        continue 2;
                    }
                    if($loopCounterMediation >= 1){
                        // ------------------------------------------------------------------
                        // Created dynamic rows
                        // ------------------------------------------------------------------
                        $spreadsheet->getActiveSheet()->mergeCells('AA'.(113+$rowBlockAdded).':'.'AW'.(113+$rowBlockAdded+1));
                        $worksheet->getCell('AA'.(113+$rowBlockAdded))->setValue('《媒介業者》')->getStyle()->getFont()->setSize(18);
                        $worksheet->getStyle('AA'.(113+$rowBlockAdded))->getFont()->setBold(true);
                        $worksheet->getStyle('AA'.(113+$rowBlockAdded))->getAlignment()->setVertical('center');

                        $arrTittleMedia = ['免許証番号', '事務所所在地', '商号', '代 表 者 等', '電話', ''];
                        for($k = 0; $k < 6; $k++){
                            $worksheet->getCell('AA'.((115+$rowBlockAdded) + $k))->setValue($arrTittleMedia[$k]);
                            $worksheet->getStyle('AA'.(115+$k))->getFont()->setSize(14);
                        }

                        $spreadsheet->getActiveSheet()->mergeCells('AA'.(121+$rowBlockAdded).':'.'AW'.(121+($rowBlockAdded+1)));
                        $worksheet->getCell('AA'.(121+$rowBlockAdded))->setValue('〈宅地建物取引士〉');
                        $worksheet->getStyle('AA'.(121+$rowBlockAdded))->getFont()->setSize(18);
                        $worksheet->getStyle('AA'.(121+$rowBlockAdded))->getFont()->setBold(true);
                        $worksheet->getStyle('AA'.(121+$rowBlockAdded))->getAlignment()->setVertical('center');

                        $arrTittleTrader = ['登録番号', '氏名', '', ''];
                        for($m = 0; $m < 4; $m++){
                            $worksheet->getCell('AA'.((123+$rowBlockAdded)+$m))->setValue($arrTittleTrader[$m]);
                            $worksheet->getStyle('AA'.(124+$m))->getFont()->setSize(14);
                        }
                        $worksheet->getStyle('AA'.(113+$rowBlockAdded))->getFont()->setSize(18);

                        // ------------------------------------------------------------------
                    }
                    //$retriveDataUser = User::find($builderTarget->trader_company_user);
                    if($i == 1){
                        $retriveDataUser = User::find($builderTarget->trader_company_user);
                        $retriveDataCompany = Company::find($builderTarget->trader_company_id);
                        $retriveCompanyOffice = CompanyOffice::find($retriveDataUser['real_estate_notary_office_id']);
                        $prefectureName = MasterValue::find($retriveDataUser['real_estate_notary_prefecture_id']);
                    }else if($i == 2){
                        $retriveDataUser = User::find($builderTarget->broker_housebuilder_by_seller);

                        $pjPurchaseConctractBrokerCompany = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();
                        $retriveDataCompany = Company::find($pjPurchaseConctractBrokerCompany->seller_broker_company_id);
                        $retriveCompanyOffice = CompanyOffice::find($retriveDataUser['real_estate_notary_office_id']);
                        $prefectureName = MasterValue::find($retriveDataUser['real_estate_notary_prefecture_id']);
                        $houseBuilderBySeller = true;
                    }

                    $masterValueLicenseOrgan = MasterValue::where('id', $retriveDataCompany['license_authorizer_id'])->where('type', 'realestate_license_organ')->first();

                    $worksheet->getCell('AG' . (115 + $rowBlockAdded))->setValue($masterValueLicenseOrgan['value'].'('. $retriveDataCompany['license_update'].')'.$retriveDataCompany['license_number']);
                    $worksheet->getCell('AG' . (116 + $rowBlockAdded))->setValue($retriveDataCompany['real_estate_agent_office_main_address']);
                    $worksheet->getCell('AG' . (117 + $rowBlockAdded))->setValue($retriveDataCompany['name']);
                    $worksheet->getCell('AG' . (118 + $rowBlockAdded))->setValue($retriveDataCompany['real_estate_agent_representative_name']);

                    if(!empty($retriveCompanyOffice->number)){
                        $worksheet->getCell('AG' . (119 + $rowBlockAdded))->setValue($retriveCompanyOffice->number);
                    }else{
                        $worksheet->getCell('AG' . (119 + $rowBlockAdded))->setValue('-');
                    }

                    $worksheet->getCell('AG' . (123 + $rowBlockAdded))->setValue('('. $prefectureName['value'] . ')' . $retriveDataUser['real_estate_notary_number']);
                    $worksheet->getCell('AG' . (124 + $rowBlockAdded))->setValue($retriveDataUser['last_name'] . ' ' .$retriveDataUser['first_name']);

                    $rowBlockAdded = $rowBlockAdded + 14;
                    $loopCounterMediation++;
                }
            }
        }
        if($loopCounterMediation <= 0){
            for($i = 0; $i <= 26; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension((113 + $rowBlockAdded) + $i)->setVisible(false);
            }
        }
        //dd($rowBlockAdded);
        if($loopCounterMediation % 2 == 0){
            for($i = 0; $i <= 12; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension((127 + ($rowBlockAdded - 14)) + $i)->setVisible(false);
            }
        }else{
            for($i = 0; $i <= 12; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension((127 + $rowBlockAdded) + $i)->setVisible(false);
            }
        }

        //dd($rowBlockAdded);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output for 内金
        // ------------------------------------------------------------------
        $loopCounterDeposit = 0;
        $sumPurchaseContractDepositPrice = 0;
        foreach($retriveDataPurchaseContract->purchase_contract_deposits as $purchaseContractDeposit){
            if($loopCounterDeposit >= 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore((54 + $loopCounterDeposit)); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('K'.(54+$loopCounterDeposit).':'.'O'.(54+$loopCounterDeposit));
                $spreadsheet->getActiveSheet()->mergeCells('P'.(54+$loopCounterDeposit).':'.'AD'.(54+$loopCounterDeposit));
                $spreadsheet->getActiveSheet()->mergeCells('AE'.(54+$loopCounterDeposit).':'.'AW'.(54+$loopCounterDeposit));
                $worksheet->getCell('K'.(54 + $loopCounterDeposit))->setValue('第'.($loopCounterDeposit+1).'回');
            }

            $purchaseContractDepositDate = PurchaseContractCreateReport::dateConverter($purchaseContractDeposit->date);
            $worksheet->getCell('P'.(54 + $loopCounterDeposit))->setValue($purchaseContractDepositDate);
            $worksheet->getCell('AE'.(54 + $loopCounterDeposit))->setValue(number_format($purchaseContractDeposit->price) .  '円');
            $sumPurchaseContractDepositPrice = $sumPurchaseContractDepositPrice + $purchaseContractDeposit->price;

            $loopCounterDeposit++;
        }
        //dd($loopCounterDeposit);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output for 残代金
        // ------------------------------------------------------------------
        $updatedRowDeposit = $loopCounterDeposit;
        if($loopCounterDeposit >= 1){
            $updatedRowDeposit = $loopCounterDeposit - 1;
        }

        $contractDeliveryDate = PurchaseContractCreateReport::dateConverter($retriveDataPurchaseContract->contract_delivery_date);
        $worksheet->getCell('P'.(58 + ($updatedRowDeposit)))->setValue($contractDeliveryDate);

        $calculateContractDeposit = (($retriveDataPurchaseContract->contract_price - $retriveDataPurchaseContract->contract_deposit) - $sumPurchaseContractDepositPrice);
        $worksheet->getCell('AE'.(58 + ($updatedRowDeposit)))->setValue( number_format($calculateContractDeposit) . '円');
        $contractDeliveryDate = PurchaseContractCreateReport::dateConverter($retriveDataPurchaseContract->contract_delivery_date);
        $worksheet->getCell('P'.(63 + ($updatedRowDeposit)))->setValue($contractDeliveryDate);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output for 清算の対象となる土地
        // ------------------------------------------------------------------

        if($getPurchaseContractCreate->c_article4_contract == 1){
            $worksheet->getCell('B'.(60 + ($updatedRowDeposit)))->setValue("□有 ■無")->getStyle()->getAlignment()->setVertical('top');

            $worksheet->getCell('R'.(59 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('R'.(60 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('R'.(61 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
        }else{
            $worksheet->getCell('B'.(60 + ($updatedRowDeposit)))->setValue("■有 □無");
        }
        if(!empty($getPurchaseContractCreate->c_article4_sub_text_contract)){
            $worksheet->getCell('R'.(61 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article4_sub_text_contract);
        }else{
            $worksheet->getCell('R'.(61 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article4_sub_text_contract);
        }
        if($getPurchaseContractCreate->c_article4_contract == 1){
            $worksheet->getCell('P'.(62 + ($updatedRowDeposit)))->setValue('');
            $worksheet->getCell('AQ'.(62 + ($updatedRowDeposit)))->setValue('');
            $worksheet->getCell('B'.(62 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AE'.(62 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AJ'.(62 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
        }else{
            $worksheet->getCell('P'.(62 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article4_clearing_standard_area. '円');
            $worksheet->getCell('AQ'.(62 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article4_clearing_standard_area. '円');
        }
        if(!empty($getPurchaseContractCreate->c_article12_contract_text)){
            $worksheet->getCell('AO'.(65 + ($updatedRowDeposit)))->setValue(number_format($getPurchaseContractCreate->c_article12_contract_text));
        }else{
            $worksheet->getCell('AO'.(65 + ($updatedRowDeposit)))->setValue('');
        }

        if($getPurchaseContractCreate->c_article12_contract == 1){
            $worksheet->getCell('P'.(65 + ($updatedRowDeposit)))->setValue('■');
            $worksheet->getCell('X'.(65 + ($updatedRowDeposit)))->setValue('□');
        }elseif($getPurchaseContractCreate->c_article12_contract == 2){
            $worksheet->getCell('X'.(65 + ($updatedRowDeposit)))->setValue('■');
            $worksheet->getCell('P'.(65 + ($updatedRowDeposit)))->setValue('□');
        }elseif($getPurchaseContractCreate->c_article12_contract == 3){
            $worksheet->getCell('AM'.(65 + ($updatedRowDeposit)))->setValue('■');
            $worksheet->getCell('X'.(65 + ($updatedRowDeposit)))->setValue('□');
            $worksheet->getCell('P'.(65 + ($updatedRowDeposit)))->setValue('□');
        }

        if($getPurchaseContractCreate->c_article16_contract == 1){
            $worksheet->getCell('Q'.(66 + ($updatedRowDeposit)))->setValue('■');
            $worksheet->getCell('U'.(66 + ($updatedRowDeposit)))->setValue('□');
        }elseif($getPurchaseContractCreate->c_article16_contract == 2){
            $worksheet->getCell('U'.(66 + ($updatedRowDeposit)))->setValue('■');
            $worksheet->getCell('Q'.(66 + ($updatedRowDeposit)))->setValue('□');

            $worksheet->getCell('D'.(67 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('D'.(68 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

            $worksheet->getCell('I'.(67 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('I'.(68 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

            $worksheet->getCell('AE'.(67 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AE'.(68 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

            $worksheet->getCell('AI'.(67 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AI'.(68 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

        }

        //dd($getPurchaseContractCreate->c_article15_loan_contract_0);
        if(!empty($getPurchaseContractCreate->c_article15_loan_contract_0)){
            $worksheet->getCell('I'.(67 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article15_loan_contract_0);
        }else{
            $worksheet->getCell('I'.(67 + ($updatedRowDeposit)))->setValue('');
        }
        if(!empty($getPurchaseContractCreate->c_article15_loan_contract_1)){
            $worksheet->getCell('I'.(68 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article15_loan_contract_1);
        }else{
            $worksheet->getCell('I'.(68 + ($updatedRowDeposit)))->setValue('');
        }

        if(!empty($getPurchaseContractCreate->c_article15_loan_amount_contract_0)){
            $worksheet->getCell('AI'.(67 + ($updatedRowDeposit)))->setValue(number_format($getPurchaseContractCreate->c_article15_loan_amount_contract_0). '円')->getStyle()->getAlignment()->setHorizontal('right');
        }else{
            $worksheet->getCell('AI'.(67 + ($updatedRowDeposit)))->setValue('');
        }
        if(!empty($getPurchaseContractCreate->c_article15_loan_amount_contract_1)){
            $worksheet->getCell('AI'.(68 + ($updatedRowDeposit)))->setValue(number_format($getPurchaseContractCreate->c_article15_loan_amount_contract_1). '円')->getStyle()->getAlignment()->setHorizontal('right');
        }else{
            $worksheet->getCell('AI'.(68 + ($updatedRowDeposit)))->setValue('');
        }

        if(!empty($getPurchaseContractCreate->c_article15_loan_release_date_contract)){
            $cArticle14LoanReleaseDateContract = PurchaseContractCreateReport::dateConverter($getPurchaseContractCreate->c_article15_loan_release_date_contract);
            $worksheet->getCell('AE'.(69 + ($updatedRowDeposit)))->setValue($cArticle14LoanReleaseDateContract);
        }else{
            $worksheet->getCell('AE'.(69 + ($updatedRowDeposit)))->setValue('');
        }

        if($getPurchaseContractCreate->c_article10_contract == 1){
            $worksheet->getCell('AE'.(70 + ($updatedRowDeposit)))->setValue('■負担する 3か月');
        }elseif($getPurchaseContractCreate->c_article10_contract == 2){
            $worksheet->getCell('AE'.(70 + ($updatedRowDeposit)))->setValue('■負担する 2年');
        }elseif($getPurchaseContractCreate->c_article10_contract == 3){
            $worksheet->getCell('AE'.(70 + ($updatedRowDeposit)))->setValue('■負担しない');
        }elseif($getPurchaseContractCreate->c_article10_contract == 4){
            $worksheet->getCell('AE'.(70 + ($updatedRowDeposit)))->setValue('■負担する');
        }else{
            $worksheet->getCell('AE'.(70 + ($updatedRowDeposit)))->setValue('-');
        }

        if($getPurchaseContractCreate->c_article23_confirm == 1){
            $worksheet->getCell('AE'.(71 + ($updatedRowDeposit)))->setValue('■ 該当する  □ 該当しない');
        }elseif($getPurchaseContractCreate->c_article23_confirm == 2){
            $worksheet->getCell('AE'.(71 + ($updatedRowDeposit)))->setValue('□ 該当する  ■ 該当しない');
        }

        if($getPurchaseContractCreate->c_article23_confirm == 1){
            $worksheet->getCell('L'.(72 + ($updatedRowDeposit)))->setValue('■ 1.有  □ 2.無');
        }elseif($getPurchaseContractCreate->c_article23_confirm == 2){
            $worksheet->getCell('L'.(72 + ($updatedRowDeposit)))->setValue('□ 1.有  ■ 2.無');

            $worksheet->getCell('B'.(72 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('L'.(73 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AE'.(73 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

            $worksheet->getCell('L'.(74 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('T'.(74 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AE'.(74 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AM'.(74 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

            $worksheet->getCell('L'.(75 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('T'.(75 + ($updatedRowDeposit)))->getStyle()->getFont()->setStrikethrough(true);

        }

        if(!empty($getPurchaseContractCreate->c_article23_confirm_write)){
            $worksheet->getCell('AE'.(73 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article23_confirm_write);
        }else{
            $worksheet->getCell('AE'.(73 + ($updatedRowDeposit)))->setValue('');
        }
        if(!empty($getPurchaseContractCreate->c_article23_creator)){
            $worksheet->getCell('T'.(74 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article23_creator);
        }else{
            $worksheet->getCell('T'.(74 + ($updatedRowDeposit)))->setValue('');
        }
        if(!empty($getPurchaseContractCreate->c_article23_create_date)){
            //dd($getPurchaseContractCreate->c_article23_create_date);
            $cArticle23CreateDate = PurchaseContractCreateReport::dateConverter($getPurchaseContractCreate->c_article23_create_date);
            $worksheet->getCell('AM'.(74 + ($updatedRowDeposit)))->setValue($cArticle23CreateDate);
        }else{
            $worksheet->getCell('AM'.(74 + ($updatedRowDeposit)))->setValue('');
        }
        //dd('T'.(75 + ($updatedRowDeposit)));
        if(!empty($getPurchaseContractCreate->c_article23_other)){
            $worksheet->getCell('T'.(75 + ($updatedRowDeposit)))->setValue($getPurchaseContractCreate->c_article23_other);
        }else{
            $worksheet->getCell('T'.(75 + ($updatedRowDeposit)))->setValue('');
        }

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Everything in below for dynamic output report (Dynamic Cells and Rows created)
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for A.不動産の表示（宅地分）(Pj Lot Residential)
        // ------------------------------------------------------------------
        $retriveDataPjLotResidentialA = PjLotResidentialA::where('pj_property_id', $getPropertiesId->id)->get();
        $retriveDataPjLotResidentialA = $retriveDataPjLotResidentialA->groupBy(function ($item, $key) {
                                            return $item['parcel_city'].$item['parcel_city_extra'].
                                                   $item['parcel_town'].$item['parcel_number_first'].
                                                   $item['parcel_number_second'];
                                        });
        $masterRegion = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();

        $isEmptyPjLotResidential = false;

        if(count($retriveDataPjLotResidentialA) <= 0){
            $isEmptyPjLotResidential = true;
        }

        if($isEmptyPjLotResidential){
            for($i = 0; $i <= 11; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(7+$i)->setVisible(false);
            }
        }

        $loopCounterResident = 0;
        $updatedRowResident = 10+$loopCounterResident;

        $sumPjLotResident = 0;
        //for($i = 0; $i < 4; $i++){
        foreach($retriveDataPjLotResidentialA as $pjLotResidentGrouped){
            $pjLotResident = $pjLotResidentGrouped[0];

            $parcelCity = $pjLotResident->parcel_city;
            $parcelCityExtra = $pjLotResident->parcel_city_extra;
            $parcelTown = $pjLotResident->parcel_town;
            $parcelNumberFirst = $pjLotResident->parcel_number_first;
            $parcelNumberSecond = $pjLotResident->parcel_number_second;
            $parcelLandCategory = $pjLotResident->parcel_land_category;
            $parcelSize = $pjLotResident->parcel_size;
            $sumPjLotResident = $sumPjLotResident + $parcelSize; //Sum([pj_lot_residential.parcel_size])
            if($loopCounterResident >= 1){
                //Create dynamic cell
                $spreadsheet->getActiveSheet()->insertNewRowBefore($updatedRowResident); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('D'.($updatedRowResident).':'.'E'.($updatedRowResident)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('F'.($updatedRowResident).':'.'AE'.($updatedRowResident)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AF'.($updatedRowResident).':'.'AK'.($updatedRowResident)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AL'.($updatedRowResident).':'.'AQ'.($updatedRowResident)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AR'.($updatedRowResident).':'.'AW'.($updatedRowResident)); // Merge cell

                $worksheet->getCell('D'.($updatedRowResident))->setValue((string)($loopCounterResident+1).'.');
            }

            $parcelCityText = '';
            $parcelCityExtra = '';
            if($parcelCity == -1){
                $parcelCityText = 'その他';
                $parcelCityExtra = $pjLotResident->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $parcelCity){
                        $parcelCityText = $region->name;
                    }
                }
            }

            $worksheet->getCell('F'.($updatedRowResident))->setValue($parcelCityText.$parcelCityExtra.$parcelTown.$parcelNumberFirst.'番'.$parcelNumberSecond)->getStyle()->getAlignment()->setHorizontal('left');
            $worksheet->getCell('AF'.($updatedRowResident))->setValue($parcelLandCategory);
            $worksheet->getCell('AL'.($updatedRowResident))->setValue(number_format($parcelSize , 2).'㎡');

            $retriveDataPjLotResidentialOwner = PjLotResidentialOwner::where('pj_lot_residential_a_id', $pjLotResident->id)->get();

            $owner = PurchaseContractCreateReport::ownerShare($retriveDataPjLotResidentialOwner);
            $worksheet->getCell('AR'.($updatedRowResident))->setValue($owner->share_denom.'分の'.$owner->share_number);

            $loopCounterResident++;
            $updatedRowResident = 10+$loopCounterResident;
        }
        //}

        $worksheet->getCell('D'.(11+$loopCounterResident))->setValue( '合計 ('. $sumPjLotResident.'㎡ '. $loopCounterResident.'筆'. ')');

        if(!empty($getPurchaseContractCreate->notices_residential_contract)){
            //dd($getPurchaseContractCreate->notices_residential_contract);
            $worksheet->getCell('F'.(12+$loopCounterResident))->setValue($getPurchaseContractCreate->notices_residential_contract);
        }else{
            $worksheet->getCell('F'.(12+$loopCounterResident))->setValue('');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for A.不動産の表示（宅地分）(Pj Lot Road)
        // ------------------------------------------------------------------
        $retriveDataPjLotRoadA = PjLotRoadA::where('pj_property_id', $getPropertiesId->id)->get();
        $retriveDataPjLotRoadA = $retriveDataPjLotRoadA->groupBy(function ($item, $key) {
                                        return $item['parcel_city'].$item['parcel_city_extra'].
                                               $item['parcel_town'].$item['parcel_number_first'].
                                               $item['parcel_number_second'];
                                  });

        $isEmptyPjLotRoad = false;

        if(count($retriveDataPjLotRoadA) <= 0){
            $isEmptyPjLotRoad = true;
        }

        if($isEmptyPjLotRoad){
            for($i = 0; $i <= 11; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(19+$i)->setVisible(false);
            }
        }

        $loopCounterRoad = 0;
        $sumPjLotRoad = 0;

        if($loopCounterResident <= 0){
            $loopCounterResident = $loopCounterResident;
        }else{
            $loopCounterResident = $loopCounterResident - 1;
        }

        $updatedRowRoad = (22 + $loopCounterResident) + $loopCounterRoad;
        //for($i = 0; $i < 4; $i++){
        foreach($retriveDataPjLotRoadA as $pjLotRoadGrouped){
            $pjLotRoad = $pjLotRoadGrouped[0];

            $roadParcelCity = $pjLotRoad->parcel_city;
            $roadParcelCityExtra = $pjLotRoad->parcel_city_extra;
            $roadParcelTown = $pjLotRoad->parcel_town;
            $roadParcelNumberFirst = $pjLotRoad->parcel_number_first;
            $roadParcelNumberSecond = $pjLotRoad->parcel_number_second;
            $roadParcelLandCategory = $pjLotRoad->parcel_land_category;
            $roadParcelSize = $pjLotRoad->parcel_size;
            $sumPjLotRoad = $sumPjLotRoad + $roadParcelSize; //Sum([pj_lot_residential.parcel_size])
            if($loopCounterRoad >= 1){
                //Create dynamic cell
                $spreadsheet->getActiveSheet()->insertNewRowBefore($updatedRowRoad); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('D'.($updatedRowRoad).':'.'E'.($updatedRowRoad)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('F'.($updatedRowRoad).':'.'AE'.($updatedRowRoad)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AF'.($updatedRowRoad).':'.'AK'.($updatedRowRoad)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AL'.($updatedRowRoad).':'.'AQ'.($updatedRowRoad)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AR'.($updatedRowRoad).':'.'AW'.($updatedRowRoad)); // Merge cell

                $worksheet->getCell('D'.($updatedRowRoad))->setValue((string)($loopCounterRoad+1).'.');

            }else{
                //$spreadsheet->getActiveSheet()->getRowDimension()->setVisible(false);
            }

            $parcelCityText = '';
            $parcelCityExtra = '';
            if($roadParcelCity == -1){
                $parcelCityText = 'その他';
                $parcelCityExtra = $pjLotRoad->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $roadParcelCity){
                        $parcelCityText = $region->name;
                    }
                }
            }

            $worksheet->getCell('F'.($updatedRowRoad))->setValue($parcelCityText.$parcelCityExtra.$roadParcelTown.$roadParcelNumberFirst.'番'.$roadParcelNumberSecond)->getStyle()->getAlignment()->setHorizontal('left');;
            $worksheet->getCell('AF'.($updatedRowRoad))->setValue($roadParcelLandCategory);
            $worksheet->getCell('AL'.($updatedRowRoad))->setValue(number_format($roadParcelSize,2).'㎡');

            $retriveDataPjLotRoadOwner = PjLotRoadOwner::where('pj_lot_road_a_id', $pjLotRoad->id)->get();

            $owner = PurchaseContractCreateReport::ownerShare($retriveDataPjLotRoadOwner);
            $worksheet->getCell('AR'.($updatedRowRoad))->setValue($owner->share_denom.'分の'.$owner->share_number);

            $loopCounterRoad++;
            $updatedRowRoad = (22 + $loopCounterResident) + $loopCounterRoad;
        }
        //}

        $loopCounterResident = $loopCounterResident + 1;
        $worksheet->getCell('D'.((22 + $loopCounterResident) + $loopCounterRoad))->setValue( '合計 ('.  $sumPjLotRoad.'㎡ '. $loopCounterRoad.'筆' . ')');
        if(!empty($getPurchaseContractCreate->notices_road_contract)){
            $worksheet->getCell('F'.((23+$loopCounterResident) + $loopCounterRoad))->setValue($getPurchaseContractCreate->notices_road_contract);
        }else{
            $worksheet->getCell('F'.((23+$loopCounterResident) + $loopCounterRoad))->setValue('');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for A.不動産の表示（宅地分）(Pj Lot Building)
        // ------------------------------------------------------------------
        $retriveDataPjLotBuildingA = PjLotBuildingA::where('pj_property_id', $getPropertiesId->id)->get();
        $retriveDataPjLotBuildingA = $retriveDataPjLotBuildingA->groupBy(function ($item, $key) {
                                          return $item['parcel_city'].$item['parcel_city_extra'].
                                                 $item['parcel_town'].$item['building_number_first'].
                                                 $item['building_number_second'].$item['building_number_third'];
                                      });

        $isEmptyPjLotBuilding = false;

        if(count($retriveDataPjLotBuildingA) <= 0){
            $isEmptyPjLotBuilding = true;
        }

        if($isEmptyPjLotBuilding){
            for($i = 0; $i < 17; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(31+$i)->setVisible(false);
            }
        }
        $loopCounterBuilding = 0;
        $sumPjLotBuilding = 0;

        if($loopCounterRoad <= 0){
            $nextRowAfterModifyCell = ($loopCounterRoad) + ($loopCounterResident - 1);
        }else{
            $nextRowAfterModifyCell = ($loopCounterRoad - 1) + ($loopCounterResident - 1);
        }

        //for($i = 0; $i < 4; $i++){
        foreach($retriveDataPjLotBuildingA as $pjLotBuildingGrouped){
            $pjLotBuilding = $pjLotBuildingGrouped[0];

            $buildingParcelCity = $pjLotBuilding->parcel_city;
            $buildingParcelCityExtra = $pjLotBuilding->parcel_city_extra;
            $buildingParcelTown = $pjLotBuilding->parcel_town;

            $buildingParcelNumberFirst = $pjLotBuilding->parcel_number_first;
            $buildingParcelNumberSecond = $pjLotBuilding->parcel_number_second;

            $buildingNumberFirst = $pjLotBuilding->building_number_first;
            $buildingNumberSecond = $pjLotBuilding->building_number_second;
            $buildingNumberThird = $pjLotBuilding->building_number_third;

            $buildingUseType = $pjLotBuilding->building_usetype;
            $buildingStructure = $pjLotBuilding->building_structure;

            $buildingDateNengou = $pjLotBuilding->building_date_nengou;
            $buildingDateYear = $pjLotBuilding->building_date_year;
            $buildingDateMonth = $pjLotBuilding->building_date_month;
            $buildingDateDay = $pjLotBuilding->building_date_day;

            $buildingParcelLandCategory = $pjLotBuilding->parcel_land_category;
            $buildingParcelSize = $pjLotBuilding->parcel_size;

            if($loopCounterBuilding >= 1){
                // ------------------------------------------------------------------
                // Create new row for dynamic cell
                // ------------------------------------------------------------------
                $spreadsheet->getActiveSheet()->insertNewRowBefore((37 + $nextRowAfterModifyCell) + $loopCounterBuilding); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('D'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'G'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('H'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AJ'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AO'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell

                $worksheet->getCell('D'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('所 在');
                $worksheet->getCell('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('家屋番号');

                $worksheet->getStyle('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->setName('Arial');
                $worksheet->getStyle('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->setSize(14);

                $worksheet->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->setName('Arial');
                $worksheet->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->setSize(14);

                $worksheet->getStyle('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getAlignment()->setHorizontal('left');
                $worksheet->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getAlignment()->setHorizontal('left');

                $spreadsheet->getActiveSheet()->insertNewRowBefore((38 + $nextRowAfterModifyCell) + $loopCounterBuilding); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('D'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'G'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('H'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AE'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AF'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AJ'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AK'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell

                $worksheet->getCell('D'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('種類');
                $worksheet->getCell('AF'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('構造');

                $spreadsheet->getActiveSheet()->insertNewRowBefore((39 + $nextRowAfterModifyCell) + $loopCounterBuilding); // Create new row

                $spreadsheet->getActiveSheet()->mergeCells('H'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AJ'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell

                $spreadsheet->getActiveSheet()->mergeCells('AK'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AO'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AP'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell

                $worksheet->getStyle('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                        'AO'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getAlignment()->setHorizontal('center');
                $worksheet->getStyle('AK'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                        'AO'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getAlignment()->setHorizontal('center');

                $spreadsheet->getActiveSheet()->insertNewRowBefore((40 + $nextRowAfterModifyCell) + $loopCounterBuilding); // Create new row

                $spreadsheet->getActiveSheet()->mergeCells('H'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AE'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AF'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AJ'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AK'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell


                $spreadsheet->getActiveSheet()->mergeCells('D'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'G'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $worksheet->getStyle('D'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                        'G'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getAlignment()->setVertical('center'); // Merge cell
                $worksheet->getCell('AK'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('延床面積');
                $worksheet->getCell('D'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('床面積');
                $worksheet->getCell('AF'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('瑕疵担保');

                $spreadsheet->getActiveSheet()->insertNewRowBefore((41 + $nextRowAfterModifyCell) + $loopCounterBuilding); // Create new row
                $spreadsheet->getActiveSheet()->mergeCells('D'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'G'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('H'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AE'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AF'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AK'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AL'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AM'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AN'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AP'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AQ'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding)); // Merge cell

                $worksheet->getCell('D'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('建築時期');
                $worksheet->getCell('AF'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('建築確認番号');
                $worksheet->getCell('AN'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('持分');
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Styling for new row added
                // ------------------------------------------------------------------
                /*$worksheet->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->getColor()->setARGB('0000CC');
                $worksheet->getStyle('AK'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->getColor()->setARGB('0000CC');
                $worksheet->getStyle('AP'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getFont()->getColor()->setARGB('0000CC');*/

                /*$spreadsheet->getActiveSheet()->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');*/
                $spreadsheet->getActiveSheet()->getStyle('D'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getTop()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);

                $spreadsheet->getActiveSheet()->getStyle('AK'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AO'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('AP'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('AK'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $spreadsheet->getActiveSheet()->getStyle('AK'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AO'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('AP'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('AK'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getBorders()
                                            ->getLeft()
                                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                /*$spreadsheet->getActiveSheet()->getStyle('AK'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((38 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');
                $spreadsheet->getActiveSheet()->getStyle('AP'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((39 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');
                $spreadsheet->getActiveSheet()->getStyle('AK'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((40 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');
                $spreadsheet->getActiveSheet()->getStyle('AL'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AM'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');
                $spreadsheet->getActiveSheet()->getStyle('AQ'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding).':'.
                                                            'AW'.((41 + $nextRowAfterModifyCell) + $loopCounterBuilding))
                                            ->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()->setARGB('CCFFFF');*/
                // ------------------------------------------------------------------
                $nextRowAfterModifyCell = $nextRowAfterModifyCell + 4;
            }

            $parcelCityText = '';
            $parcelCityExtra = '';
            if($buildingParcelCity == -1){
                $parcelCityText = 'その他';
                $parcelCityExtra = $pjLotBuilding->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $buildingParcelCity){
                        $parcelCityText = $region->name;
                    }
                }
            }

            $worksheet->getCell('H'.((33 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($parcelCityText.$parcelCityExtra.$buildingParcelTown.$buildingParcelNumberFirst.'番'.$buildingParcelNumberSecond)->getStyle()->getAlignment()->setHorizontal('left');;
            $worksheet->getCell('AP'.((33 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($buildingNumberFirst. '番'. $buildingNumberSecond. 'の'. $buildingNumberThird);

            $masterValueBuildingUseType = MasterValue::where('id', $buildingUseType)->where('type', 'building_usetype')->first();
            $worksheet->getCell('H'.((34 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($masterValueBuildingUseType->value);

            $masterValueBuildingStrcuture =  MasterValue::where('id', $buildingStructure)->where('type', 'building_structure')->first();
            $worksheet->getCell('AK'.((34 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($masterValueBuildingStrcuture->value);

            $retriveBuildingFloorSize = PjBuildingFloorSize::where('pj_lot_building_a_id', $pjLotBuilding->id)->get();
            $collectFloorSize = '';
            $sumFloorSize = 0;
            $loopFloorSize = 1;
            foreach($retriveBuildingFloorSize as $floorSize){
                if($loopFloorSize >= 2){
                    $collectFloorSize .= '・'. $loopFloorSize. '階' .  number_format($floorSize->floor_size, 0). '㎡';
                }else{
                    $collectFloorSize .= $loopFloorSize. '階' . number_format($floorSize->floor_size, 0). '㎡';
                }

                $sumFloorSize = $sumFloorSize + $floorSize->floor_size;
                $loopFloorSize++;
            }
            //$collectFloorSize = substr($collectFloorSize, 1);
            //dd($collectFloorSize);

            if(!empty($collectFloorSize)){
                $worksheet->getCell('H'.((35 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($collectFloorSize);
            }else{
                $worksheet->getCell('H'.((35 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue('');
            }

            $worksheet->getCell('AP'.((35 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue(number_format((float)$sumFloorSize, 2, '.', ''). '㎡');
            $spreadsheet->getActiveSheet()->getRowDimension((36 + $nextRowAfterModifyCell) + $loopCounterBuilding)->setVisible(false);
            //$spreadsheet->getActiveSheet()->getStyle('AP'.((35 + $nextRowAfterModifyCell) + $loopCounterBuilding))->getNumberFormat()->setFormatCode('#,##0.0000');

            $buildingDateNengouText = '';
            if($buildingDateNengou == 1){
                $buildingDateNengouText = '昭和';
            }else if($buildingDateNengou == 2){
                $buildingDateNengouText = '平成';
            }else{
                $buildingDateNengouText = '令和';
            }

            $buildingYear = PurchaseContractCreateReport::convertNengouToWestern($buildingDateYear, $buildingDateNengou);

            $worksheet->getCell('H'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($buildingDateNengouText. $buildingYear. '年' .$buildingDateMonth. '月' .$buildingDateDay. '日');

            $worksheet->getCell('AL'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($getPurchaseContractCreate->project_buy_building_number);

            $retriveDataPjLotBuildingOwner = PjLotBuildingOwner::where('pj_lot_building_a_id', $pjLotBuilding->id)->get();

            $owner = PurchaseContractCreateReport::ownerShare($retriveDataPjLotBuildingOwner);
            $worksheet->getCell('AQ'.((37 + $nextRowAfterModifyCell) + $loopCounterBuilding))->setValue($owner->share_denom.'分の'.$owner->share_number);

            $loopCounterBuilding++;

        }
        //}
        //dd((38 + $nextRowAfterModifyCell) + $loopCounterBuilding);
        if(!empty($getPurchaseContractCreate->notices_building_contract)){
            $worksheet->getCell('F'.((37 + $nextRowAfterModifyCell) + ($loopCounterBuilding+5)))->setValue($getPurchaseContractCreate->notices_building_contract);
        }else{
            $worksheet->getCell('F'.((37 + $nextRowAfterModifyCell) + ($loopCounterBuilding+5)))->setValue('');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Remove unused rows
        // ------------------------------------------------------------------
        //$spreadsheet->getActiveSheet()->removeRow(11 + ($loopCounterResident - 1));
        //$spreadsheet->getActiveSheet()->removeRow(23 + ($loopCounterRoad + 1));
        if($loopCounterResident >= 1){
            $spreadsheet->getActiveSheet()->getRowDimension(11 + ($loopCounterResident - 1))->setVisible(false);
        }

        if($loopCounterRoad >= 1){
            $spreadsheet->getActiveSheet()->getRowDimension(23 + ($loopCounterRoad + ($loopCounterResident-2)))->setVisible(false);
        }


        /*
        $loopCounterRoad = $loopCounterRoad + 1;
        $spreadsheet->getActiveSheet()->unmergeCells('D'.(23 + ($loopCounterRoad)).':'.'E'.(23 + ($loopCounterRoad))); // Merge cell
        $spreadsheet->getActiveSheet()->unmergeCells('F'.(23 + ($loopCounterRoad)).':'.'AE'.(23 + ($loopCounterRoad))); // Merge cell
        $spreadsheet->getActiveSheet()->unmergeCells('AF'.(23 + ($loopCounterRoad)).':'.'AK'.(23 + ($loopCounterRoad))); // Merge cell
        $spreadsheet->getActiveSheet()->unmergeCells('AL'.(23 + ($loopCounterRoad)).':'.'AQ'.(23 + ($loopCounterRoad))); // Merge cell
        $spreadsheet->getActiveSheet()->unmergeCells('AR'.(23 + ($loopCounterRoad)).':'.'AW'.(23 + ($loopCounterRoad))); // Merge cell
        */
        //dd($nextRowAfterModifyCell);
        $nextRowAfterModifyCell = $nextRowAfterModifyCell - 1;
        if($loopCounterBuilding >= 1){
            for($i = 0; $i < 5; $i++){
                //dd((38 + $nextRowAfterModifyCell + $loopCounterBuilding) + $i);
                $spreadsheet->getActiveSheet()->getRowDimension((38 + $nextRowAfterModifyCell + $loopCounterBuilding) + $i)->setVisible(false);
            }
        }
        $updatedRowsDeposit = $loopCounterBuilding + ($loopCounterDeposit-1);
        for($j = 0; $j <=3; $j++){
            $spreadsheet->getActiveSheet()->getRowDimension(((55 + $nextRowAfterModifyCell) + ($updatedRowsDeposit)) + $j)->setVisible(false);
        }


        //$spreadsheet->getActiveSheet()->removeRow(23 + ($loopCounterRoad+1));

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create Output
        // ------------------------------------------------------------------
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter( $spreadsheet, 'Xlsx' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $public = public_path();
        $projectID = $projectId;
        $targetID = $targetId;
        $projectHash = sha1( "port-project-$projectID" );
        $purchaseHash = sha1( "port-purchase-$targetID" );
        // ------------------------------------------------------------------
        $directory = "reports/output/{$projectHash}/{$purchaseHash}";
        $filepath = "{$directory}/不動産売買契約書-{$targetID}.xlsx";
        // ------------------------------------------------------------------
        File::makeDirectory( "{$public}/{$directory}", 0777, true, true );
        $writer->save( "{$public}/{$filepath}" );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return url( $filepath );
        // ------------------------------------------------------------------

        // Create Output
        /*$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $projectId = $projectId;
        $path = public_path().'/reports/output/' . 'project_'. $projectId. '/'. 'contract_'. $projectId;
        File::makeDirectory($path, $mode = 0777, true, true);

        $writer->save(public_path().'/reports/output/' . 'project_'. $projectId. '/'. 'contract_'. $projectId. '/output_purchase_contract_'.$projectId.'.xlsx');*/

        // ------------------------------------------------------------------
    }

    // --------------------------------------------------------------
    //  Convert nengou year to western year or reverse
    // --------------------------------------------------------------
    static function convertNengouToWestern($dateYear, $datNengou){
        $showa = range(1926, 1990);
        $heisei = range(1989, 2020);
        $reiwa  = range(2019, 2050);

        $nengou = [
            '1' => $showa,
            '2' => $heisei,
            '3' => $reiwa,
        ];

        //$index = $dateYear;
        $selected = $nengou[$datNengou];

        foreach($selected as $index => $select){
            if($select == $dateYear){
                return $index+1;
            }
        }
    }
    // --------------------------------------------------------------

    static function dateConverter($date){
        // ------------------------------------------------------------------
        // Date converter from YY/MM/DD to 平成YY年MM月DD日
        // ------------------------------------------------------------------
        //dd($date);
        /*$convertDate = explode("-", $date);
        $convertedYY = $convertDate[0];
        $convertedMM = $convertDate[1];
        $convertedDD = $convertDate[2];*/
        $convertDate = Carbon::parse($date);
        return "平成".$convertDate->year."年".$convertDate->month."月".$convertDate->day."日";
        // ------------------------------------------------------------------
    }

    // ------------------------------------------------------------------
    // Report function for vow Description
    // ------------------------------------------------------------------
    static function vowDescription($worksheet, $spreadsheet, $cArticle15Contract,$cArticle15BurdenContract, $cArticle15BaseContract){
        $vowDescOutput1 = '下記売主と下記買主は表記の物件の売買契約を締結し、この契約を証するため契約書１通を作成し、売主及び買主が記名押印のうえ売主がその原本を、買主がその写しを保有する。';
        $vowDescOutput2 = '下記売主と下記買主は表記の物件の売買契約を締結し、この契約を証するため契約書１通を作成し、売主及び買主が記名押印のうえ買主がその原本を、売主がその写しを保有する。';
        $vowDescOutput3 = '下記売主と下記買主は表記の物件の売買契約を締結し、この契約を証するため契約書１通を作成し、売主及び買主が記名押印のうえ売主がその原本を、買主がその写しを保有する。※上記「１通：売主負担」と同文';
        $vowDescOutput4 = '下記売主と下記買主は表記の物件の売買契約を締結し、この契約を証するため契約書１通を作成し、売主及び買主が記名押印のうえ買主がその原本を、売主がその写しを保有する。※上記「１通：買主負担」と同文';
        $vowDescOutput5 = '下記売主と下記買主は表記の物件の売買契約を締結し、この契約を証するため契約書２通を作成し、売主及び買主が記名押印のうえ各自その１通を保有する。';

        if($cArticle15Contract == 1 && $cArticle15BurdenContract == 1 && empty($cArticle15BaseContract)){
            $worksheet->getCell('B78')->setValue($vowDescOutput1);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 2 && empty($cArticle15BaseContract)){
            $worksheet->getCell('B78')->setValue($vowDescOutput3);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 3 && $cArticle15BaseContract == 1){
            $worksheet->getCell('B78')->setValue($vowDescOutput4);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 3 && $cArticle15BaseContract == 2){
            $worksheet->getCell('B78')->setValue($vowDescOutput4);
        }else if($cArticle15Contract == 2 && empty($cArticle15BurdenContract) && empty($cArticle15BaseContract)){
            $worksheet->getCell('B78')->setValue($vowDescOutput5);
        }else{
            $worksheet->getCell('B78')->setValue('');
        }
        $worksheet->getStyle('B78')->getAlignment()->setVertical('top');
    }
    // ------------------------------------------------------------------

    static function article4Output($worksheet, $spreadsheet, $cArticle4Contract){
        if($cArticle4Contract == 1){
            $worksheet->getCell('I172')->setValue('売主、買主は、本物件の売買対象面積を表記土地面積とし、同面積が測量等による面積と差異が生じた場合であっても、互いに異議を申し出ず、売買代金の変更、本契約の解除、損害賠償の請求その他何らの請求もしないものとします。'); // Output for 第4条 // (1) Fix Feedback from task [A14-new-feedback]
        }else{
            $worksheet->getCell('I172')->setValue('本物件の売買対象面積は測量によって得られた面積とします。測量の結果、公簿面積との間に差異が生じた場合は、本物件の㎡単価と同額にて精算するものとします。ただし、実測面積と公簿面積の差異が１㎡未満の時は精算をしないものとします。'); // Output for 第4条
        }
    }

    // ------------------------------------------------------------------
    // Report function for Article 5
    // ------------------------------------------------------------------
    static function article5Condition($worksheet, $spreadsheet, $cArticle5FixedSurveyContract, $cArticle5BurdenContract, $cArticle5Burden2Contract, $cArticle5FixedSurveyOptionsContract){
        // ------------------------------------------------------------------
        // Conditions for Article 5
        // ------------------------------------------------------------------
        $article5Pattern1 = [
            '売主は、売主の責任と負担において、残代金支払期日までに、資格有るものにより隣地所有者の立会いを得て作製された本物件の確定測量図を買主に交付します。',
            '測量の結果、登記面積と実測に差異が生じ、公差の範囲を超える場合、売主の責任と負担において地積更正登記を行うこととします。',
            '買主は、売主の責に帰さない事由により確定測量が完了しない場合、本契約を解除出来るものとします。その場合、売主は受領済の金員を全額無利息にて買主に返還することと致します。',
        ];
        $article5Pattern2 = [
            '売主は買主に対して、残代金支払期日までに資格有るものにより隣地所有者の立会いを得て作製された本物件の確定測量図の交付および地積更正登記の実施を行いますが、費用負担は買主とします。',
            '測量の結果、登記面積と実測に差異が生じ、公差の範囲を超える場合、売主の責任と負担において地積更正登記を行うこととします。',
            '買主は、売主の責に帰さない事由により確定測量が完了しない場合、本契約を解除出来るものとします。その場合、売主は受領済の金員を全額無利息にて買主に返還することと致します。',
        ];
        $article5Pattern3 = [
            '売主は、買主に対し、残代金の支払日までに、本物件につき現地にて境界標を指示して境界を明示します。なお、境界標に不明な箇所があるときは、売主はその責任と負担において、資格有るものに依頼し、隣地所有者の立会いを得た上で境界標を復元して買主に引き渡すものとします。',
        ];
        $article5Pattern4 = [
            '売主は、本物件の境界標について明示をしません。買主はこれに同意して購入するものとし、異議を申立てないこととします。'
        ];

        if($cArticle5FixedSurveyContract == 1 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && empty($cArticle5FixedSurveyOptionsContract)){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern3);
        }
        else if($cArticle5FixedSurveyContract == 2 && $cArticle5BurdenContract == 1 && $cArticle5Burden2Contract == 1 && empty($cArticle5FixedSurveyOptionsContract)){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern1);
        }
        else if($cArticle5FixedSurveyContract == 2 && $cArticle5BurdenContract == 2 && $cArticle5Burden2Contract == 1 && empty($cArticle5FixedSurveyOptionsContract)){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern2);
        }
        else if($cArticle5FixedSurveyContract == 2 && $cArticle5BurdenContract == 1 && $cArticle5Burden2Contract == 2 && empty($cArticle5FixedSurveyOptionsContract)){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern1);
        }
        else if($cArticle5FixedSurveyContract == 2 && $cArticle5BurdenContract == 2 && $cArticle5Burden2Contract == 2 && empty($cArticle5FixedSurveyOptionsContract)){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern2);
        }
        else if($cArticle5FixedSurveyContract == 3 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && $cArticle5FixedSurveyOptionsContract == 1){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern3);
        }
        else if($cArticle5FixedSurveyContract == 3 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && $cArticle5FixedSurveyOptionsContract == 2){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern4);
        }
        else if($cArticle5FixedSurveyContract == 3 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && $cArticle5FixedSurveyOptionsContract == 3){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern3);
        }
        else if($cArticle5FixedSurveyContract == 3 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && $cArticle5FixedSurveyOptionsContract == 4){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern4);
        }
        else if($cArticle5FixedSurveyContract == 3 && empty($cArticle5BurdenContract) && empty($cArticle5Burden2Contract) && $cArticle5FixedSurveyOptionsContract == 5){
            PurchaseContractCreateReport::article5Output($worksheet, $spreadsheet, $article5Pattern4);
        }
        // ------------------------------------------------------------------
    }
    static function article5Output($worksheet, $spreadsheet, $patternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        foreach($patternInput as $pattern){
            if($patternLoop >= 1){
                //if(strlen($pattern) < 290) // Added 3 Rows
                //if(strlen($pattern) < 435) // Added 4 Rows
                //if(strlen($pattern) < 580) // Added 5 Rows
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 4; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(178+$newBlockRow,4); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(178+$newBlockRow).':'.'AW'.(181+$newBlockRow)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(178+$newBlockRow).':'.'AW'.(181+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(178+$newBlockRow).':'.'H'.(179+$newBlockRow)); // Merge Text
            }
            $worksheet->getCell('F'.(178+$newBlockRow))->setValue($patternLoop+1 .". "." ");
            $worksheet->getStyle('F'.(178+$newBlockRow))->getAlignment()->setHorizontal('left');

            $worksheet->getCell('I'.(178+$newBlockRow))->setValue($pattern);
            $worksheet->getStyle('I'.(178+$newBlockRow))->getFont()->setSize(14)->setName('Arial');
            $worksheet->getStyle('I'.(178+$newBlockRow))->getAlignment()->setVertical('top');
            //$worksheet->getStyle('I'.(178+$newBlockRow))->getFont()->setName('Arial');

            $worksheet->getStyle('F'.(178+$newBlockRow))->getAlignment()->setVertical('top');

            $patternLoop++;
            $newBlockRow = $newBlockRow + 4;
        }
        $worksheet->getCell('F'.(180+$newBlockRow))->setValue($patternLoop+1 .". "." ");
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 6
    // ------------------------------------------------------------------
    static function article6Condition($worksheet, $spreadsheet, $projectUrbanizationAreaSub, $cArticle6Contract1, $cArticle6Contract2, $dateOfCityPlanning){
        // ------------------------------------------------------------------
        // Conditions for Article 6
        // ------------------------------------------------------------------

        $convertDate = Carbon::parse($dateOfCityPlanning);
        $convertedDate = $convertDate->year."年".$convertDate->month."月".$convertDate->day."日";

        $article6Pattern0 = [
            '買主が売主に対して売買代金全額を支払い、売主がこれを受領した時は、売主買主が協力して所有権の移転 および所有権の移転手続きをおこなうものとします。',
            '前項の登記申請に要する費用は、買主の負担とします。ただし、本物件に関し、前項の所有権移 転登記手続前の所有権登記名義人の住所、氏名等の変更登記を要する場合の費用は、売主の負担とします。',
            '売主は、買主に対し、本物件について、所有権移転時期までにその責任と負担において、先取特権、抵当権等の担保権、地上権、賃借権等の用益権その他名目形式の如何を問わず、買主の完全な所有権の行使を阻害する一切の負担を除去抹消します。',
        ];
        $article6Pattern1 = [
            '本物件の使用収益は土地区画整理法における使用収益の開始日（'. $convertedDate .'）以降とします。',
            '買主は本条により本土地の引き渡しを受けたときは、当該地を使用し収益することができる。',
            '本土地を買主が使用し、収益することについて第三者から異議の申し出又は権利等の主張があったときは、甲はその責任において解決するものとします。',
            '本物件における所有権移転登記は、組合による換地処分に伴う保存登記が完了した後に行うものとする。この所有権移転登記に要する諸費用は買主又は最終土地取得者の負担とします。',
            '本物件の使用収益開始日は、契約時点での予定となっており、変更になる場合がございます。'
        ];
        $article6Pattern2 = [
            '本物件の使用収益は土地区画整理法における仮換地の指定の効力発生の日（'. $convertedDate .'）以降とします。',
            '従前地については使用収益する事が出来ません。',
            '本物件の使用収益開始予定は本契約時の予定であり、売主から買主へ所有権移転するまでに区画整理組合による工事が完了していない場合があることを買主は了承するものとします。'
        ];
        $article6Pattern3 = [
            '土地区画整理法110条に規定する土地区画整理事業の換地処分時の清算金（徴収・交付）が発生した場合は、売主が清算（納付・受領）するものとします。'
        ];
        $article6Pattern4 = [
            '土地区画整理法110条に規定する土地区画整理事業の換地処分時の清算金（徴収・交付）が発生した場合は、買主が清算（納付・受領）するものとします。'
        ];
        $article6Pattern5 = [
            '土地区画整理事業の進捗に伴い、事業費不足の補填の為、法第40条、土地区画整理組合定款（以下「定款」という）に規定する賦課金の徴収（金銭での徴収）または再減歩（追加保留地確保の為の所有土地面積減少）等の追加負担が必要となる可能性があります。これら前項に規定する清算金以外の追加負担行為が発生した場合は、売主が負担するものとします。その責務は買主が第三者に売却した時も負うこととします。'
        ];
        $article6Pattern6 = [
            '土地区画整理事業の進捗に伴い、事業費不足の補填の為、法第40条、土地区画整理組合定款（以下「定款」という）に規定する賦課金の徴収（金銭での徴収）または再減歩（追加保留地確保の為の所有土地面積減少）等の追加負担が必要となる可能性があります。これら前項に規定する清算金以外の追加負担行為が発生した場合は、買主が負担するものとします。'
        ];

        if($projectUrbanizationAreaSub == 1){
            PurchaseContractCreateReport::article6Output($worksheet, $spreadsheet, array($article6Pattern0, $article6Pattern1));
        }
        else if($projectUrbanizationAreaSub == 2 && $cArticle6Contract1 == 1 && $cArticle6Contract2 == 1){
            PurchaseContractCreateReport::article6Output($worksheet, $spreadsheet, array($article6Pattern0, $article6Pattern2, $article6Pattern3, $article6Pattern5));
        }else if($projectUrbanizationAreaSub == 2 && $cArticle6Contract1 == 1 && $cArticle6Contract2 == 2){
            PurchaseContractCreateReport::article6Output($worksheet, $spreadsheet, array($article6Pattern0, $article6Pattern2, $article6Pattern3, $article6Pattern6));
        }else if($projectUrbanizationAreaSub == 2 && $cArticle6Contract1 == 2 && $cArticle6Contract2 == 1){
            PurchaseContractCreateReport::article6Output($worksheet, $spreadsheet, array($article6Pattern0, $article6Pattern2, $article6Pattern4, $article6Pattern5));
        }else if($projectUrbanizationAreaSub == 2 && $cArticle6Contract1 == 2 && $cArticle6Contract2 == 2){
            PurchaseContractCreateReport::article6Output($worksheet, $spreadsheet, array($article6Pattern0, $article6Pattern2, $article6Pattern4, $article6Pattern6));
        }
        // ------------------------------------------------------------------
    }

    static function article6Output($worksheet, $spreadsheet, $arrPatternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        foreach($arrPatternInput as $patternInput){
            foreach($patternInput as $pattern){
                //dd(strlen($pattern));
                //if($patternLoop >= 1){
                if(strlen($pattern) < 230){ // Added 3 Rows
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 3; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(192+$newBlockRow, 3); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(192+$newBlockRow).':'.'H'.(193+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 350){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 4; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(192+$newBlockRow, 4); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+1)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+1))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(192+$newBlockRow).':'.'H'.(193+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 470){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 5; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(192+$newBlockRow, 5); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+2)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+2))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(192+$newBlockRow).':'.'H'.(193+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 600){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 6; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(192+$newBlockRow, 6); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+3)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(192+$newBlockRow).':'.'AW'.(194+$newBlockRow+3))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(192+$newBlockRow).':'.'H'.(193+$newBlockRow)); // Merge Text
                }
                //}

                if($patternLoop < 1){
                    $spreadsheet->getActiveSheet()->mergeCells('B'.(192+$newBlockRow).':'.'E'.(193+$newBlockRow)); // Merge Text
                    $worksheet->getCell('B'.(192+$newBlockRow))->setValue('第6条')->getStyle()->getFont()->setSize(14);
                    $worksheet->getStyle('B'.(192+$newBlockRow))->getAlignment()->setHorizontal('center');
                }

                $worksheet->getCell('F'.(192+$newBlockRow))->setValue($patternLoop+1 .". "." ")->getStyle()->getFont()->setSize(14);
                $worksheet->getStyle('F'.(192+$newBlockRow))->getAlignment()->setHorizontal('left');

                $worksheet->getCell('I'.(192+$newBlockRow))->setValue($pattern);
                $worksheet->getStyle('I'.(192+$newBlockRow))->getFont()->setSize(14)->setName('Arial');
                $worksheet->getStyle('I'.(192+$newBlockRow))->getAlignment()->setVertical('top');
                //$worksheet->getStyle('I'.(192+$newBlockRow))->getFont()->setName('Arial');

                $worksheet->getStyle('F'.(192+$newBlockRow))->getAlignment()->setVertical('top');

                $patternLoop++;
                if(strlen($pattern) < 230){
                    $newBlockRow = $newBlockRow + 3;
                }else if(strlen($pattern) < 350){
                    $newBlockRow = $newBlockRow + 4;
                }else if(strlen($pattern) < 470){
                    $newBlockRow = $newBlockRow + 5;
                }else if(strlen($pattern) < 600){
                    $newBlockRow = $newBlockRow + 6;
                }

            }
        }
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 8
    // ------------------------------------------------------------------
    static function article8Condition($worksheet, $spreadsheet, $article8Branch, $article8Branch1Condition, $article8Branch2Condition , $cArticle8ContractText){
        $article8Pattern1 = [
            '売主は、本物件引渡まで善良なる管理者の注意をもって本物件を保管しなければなりません。'
        ];
        $article8Pattern2 = [
            '売主は、その責任と負担において本物件引渡時までに本土地上の建物及び付属建物、工作物、樹木等を解体撤去し、滅失登記を完了させ引き渡すものとします。'
        ];
        $article8Pattern3 = [
            '売主は、本土地を現況のまま引き渡すものとしますが、本物件引渡後に買主は本土地上の建物を解体撤去するため、建物の所有権移転登記に代えて、売主名義による滅失登記申請手続きに無償で協力するものとします。ただし、建物の解体撤去および滅失登記については引渡し日から3ヶ月以内に完了させることとし、その手続きに要する費用は買主の負担とします。'
        ];
        $article8Pattern4 = [
            '売主は、その責任と負担において本物件引渡し時までに本物件内にある残置物を撤去するものとします。'
        ];
        $article8Pattern5 = [
            '売主は、本物件内にある残置物を撤去しないものとし、現状のまま引き渡すものとします。'
        ];
        $article8PatternA = [
            '売主は、その責任と負担において本物件の占有者を引渡時までに退去させるものとします。',
            '買主は引渡期日までに占有者が退去しなかった場合に、本契約を解除出来るものとします。その場合、売主は受領済の金員を全額無利息にて買主に返還することと致します。'
        ];
        $article8PatternB = [
            '買主は売主と占有者との契約を引き継ぐものとします。賃貸人としての地位継承に伴い、賃借人に対する保証金、保証金返還債務及びその他一切の預り金も売主から買主へ継承するものとします。また、保証金については決済時に売買代金にて相殺するものとします。',
            '売主は、家賃滞納等の告知事項に虚偽が無い事を誓い、借主情報と過去1年間の家賃支払状況について開示するものとします。',
            '引渡までに発覚した占有者からの補修要望については売主負担、引渡し後は買主負担とします。'
        ];
        $article8PatternC = [
            '売主は、本物件の占有者を引渡時までに退去させることを決済の条件としますが、立退き費用・手配は買主の負担とします。',
            '引渡時までに退去が出来なかった場合、本売買契約は自動更新されるものとするが、買主は売主に対して契約を解除、もしくは引渡日の延長を求める事が出来ます。',
            '本条項により契約を解除した場合、売主は受領済の金員を無利息にて買主に返還する事と致します。'
        ];

        $getArticle8ContractText = $cArticle8ContractText;
        $article8PatternD = [
            $getArticle8ContractText
        ];
        //dd($article8PatternD);
        $article8None = [
            ''
        ];

        if($article8Branch == 1){
            // 条件分岐１
            $contractBuildingKind = $article8Branch1Condition[0];
            $contractBuildingUnregKind = $article8Branch1Condition[1];
            $propertyDescDismantling = $article8Branch1Condition[2];
            $propertyDescRemovalByUser = $article8Branch1Condition[3];

            $purchaseContractKind = 0;
            if($contractBuildingKind == 2 || $contractBuildingUnregKind == 2){
                $purchaseContractKind = 1; //true
            }else{
                $purchaseContractKind = 0; //false
            }
            //dd($purchaseContractKind);
            if($purchaseContractKind == 1 && $propertyDescDismantling == 1){
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8Pattern1, $article8Pattern2), $article8Branch);
            }else if($purchaseContractKind == 1 && $propertyDescDismantling == 2 && $propertyDescRemovalByUser == 2){ // 2 UnCheck
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8Pattern1, $article8Pattern3, $article8Pattern4), $article8Branch);
            }else if($purchaseContractKind == 1 && $propertyDescDismantling == 2 && $propertyDescRemovalByUser == 1){ // 1 Check
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8Pattern1, $article8Pattern3, $article8Pattern5), $article8Branch);
            }else if($purchaseContractKind == 0){ // 1 Check
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8Pattern1), $article8Branch);
            }
        }
        else{
            // 条件分岐2
            $purchaseContractMediation = $article8Branch2Condition[0]; // 2 : Yes, 1 : No
            $cArticle8Contract = $article8Branch2Condition[1];

            if($purchaseContractMediation == 2 && $cArticle8Contract == 1){
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8PatternA), $article8Branch);
            }else if($purchaseContractMediation == 2 && $cArticle8Contract == 2){
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8PatternB), $article8Branch);
            }else if($purchaseContractMediation == 2 && $cArticle8Contract == 3){
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8PatternC), $article8Branch);
            }else if($purchaseContractMediation == 2 && $cArticle8Contract == 4){
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8PatternD), $article8Branch);
            }else{
                PurchaseContractCreateReport::article8Output($worksheet, $spreadsheet, array($article8PatternD), $article8Branch);
            }
        }
    }

    static function article8Output($worksheet, $spreadsheet, $arrPatternInput, $article8Branch){
        $patternLoop = 0;
        $branch1Null = 0; // false
        if($article8Branch == 2){
            $patternLoop = 3;
        }
        $newBlockRow = 0;
        foreach($arrPatternInput as $patternInput) {
            foreach($patternInput as $pattern){
                //if($patternLoop >= 1){
                if($article8Branch == 1){
                    $worksheet->getCell('B'.(212+$newBlockRow))->setValue('');
                }

                if(strlen($pattern) < 230){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 3; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(212+$newBlockRow, 3); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(212+$newBlockRow).':'.'H'.(213+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 350){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 4; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(212+$newBlockRow, 4); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+1)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+1))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(212+$newBlockRow).':'.'H'.(213+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 470){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 5; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(212+$newBlockRow, 5); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+2)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+2))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(212+$newBlockRow).':'.'H'.(213+$newBlockRow)); // Merge Text
                }else if(strlen($pattern) < 600){
                    $newBlockRow = $newBlockRow;
                    //for($i = 0; $i < 6; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(212+$newBlockRow, 6); // Create new row
                    //}
                    $spreadsheet->getActiveSheet()->mergeCells('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+3)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('I'.(212+$newBlockRow).':'.'AW'.(214+$newBlockRow+3))->getAlignment()->setWrapText(true); // Wrap Text
                    $spreadsheet->getActiveSheet()->mergeCells('F'.(212+$newBlockRow).':'.'H'.(213+$newBlockRow)); // Merge Text
                }
                //}

                if($patternLoop < 1){
                    if($article8Branch == 1){
                        $spreadsheet->getActiveSheet()->mergeCells('B'.(212+$newBlockRow).':'.'E'.(213+$newBlockRow)); // Merge Text
                        $worksheet->getCell('B'.(212+$newBlockRow))->setValue('第8条')->getStyle()->getFont()->setSize(14);
                        $worksheet->getStyle('B'.(212+$newBlockRow))->getAlignment()->setHorizontal('center');
                        //dd(static::$numberOfRow);
                    }
                }
                if($patternLoop < 4){
                    if($article8Branch == 2){
                        $spreadsheet->getActiveSheet()->mergeCells('B'.(212+$newBlockRow).':'.'E'.(213+$newBlockRow)); // Merge Text
                        $worksheet->getCell('B'.(212+$newBlockRow))->setValue('第8条')->getStyle()->getFont()->setSize(14);
                        $worksheet->getStyle('B'.(212+$newBlockRow))->getAlignment()->setHorizontal('center');
                        //static::$numberOfRow = 'B'.(212+$newBlockRow);
                    }
                }

                $worksheet->getCell('F'.(212+$newBlockRow))->setValue($patternLoop+1 .". "." ")->getStyle()->getFont()->setSize(14);
                $worksheet->getStyle('F'.(212+$newBlockRow))->getAlignment()->setHorizontal('left');

                $worksheet->getCell('I'.(212+$newBlockRow))->setValue($pattern);
                $worksheet->getStyle('I'.(212+$newBlockRow))->getFont()->setSize(14)->setName('Arial');
                $worksheet->getStyle('I'.(212+$newBlockRow))->getAlignment()->setVertical('top');
                //$worksheet->getStyle('I'.(212+$newBlockRow))->getFont()->setName('Arial');

                $worksheet->getStyle('F'.(212+$newBlockRow))->getAlignment()->setVertical('top');
                $worksheet->getStyle('I'.(212+$newBlockRow))->getAlignment()->setHorizontal('left');

                $patternLoop++;

                if(strlen($pattern) < 230){
                    $newBlockRow = $newBlockRow + 3;
                }else if(strlen($pattern) < 350){
                    $newBlockRow = $newBlockRow + 4;
                }else if(strlen($pattern) < 470){
                    $newBlockRow = $newBlockRow + 5;
                }else if(strlen($pattern) < 600){
                    $newBlockRow = $newBlockRow + 6;
                }
            }
        }
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 10
    // ------------------------------------------------------------------
    static function article10Condition($worksheet, $spreadsheet, $contractSeller, $existBuildingResidential){
        $article10Pattern1 = [
            '売主は、買主に対し、引渡された本物件が種類または品質に関して契約の内容に適合しないもの（以下「契約不適合」といいます。）であるときは、責任を負うものとし、買主は、売主に対し、次のとおり請求することができます。'
                => ['買主は、売主に対し、本物件の修補を請求することができます。ただし、売主は、買主に不相当な負担を課すものではないときは、買主が請求した方法と異なる方法による修補をすることができます。',
                    '前号の場合において、買主が、売主に対し、相当の期間を定めて修補の催告をし、その期間内に修補をしないときは、買主はその不適合の程度に応じて代金の減額を請求することができます。ただし、買主が売主に催告しても修補を受ける見込みがないことが明らかであるときは、催告をすることなく直ちに代金の減額を請求することができます。',
                    '第1項の契約不適合が買主の責めに帰すべき事由によるものであるときは、買主は、第1号の修補請求、第2号の代金減額請求のいずれもすることはできません。'
                ],
            '第1項の契約不適合が、本契約および社会通念に照らして売主の責めに帰すことができない事由によるものであるときを除き、買主は、売主に対し、損害賠償を請求することができます。' => [],
            '買主が、売主に対し、引渡完了日から2年以内に契約不適合の旨の通知をしないときは、売主は、買主に対し、前2項の責任は負いません。' => []
        ];
        $article10Pattern2 = [
            '売主は、買主に対し、引渡された土地および建物が品質に関して契約の内容に適合しないもの（以下「契約不適合」といいます。）であるときは、引渡完了日から3ヶ月以内に通知を受けたものにかぎり、契約不適合責任を負います。ただし、建物については次の場合のみ責任を負います。'
                => ['雨水の浸入を防止する部分の雨漏り',
                    '建物の構造耐力上主要な部分の腐食',
                    'シロアリの害',
                    '給排水管（敷地内埋設給排水管を含みます。）・排水桝の故障'
                ],
            '売主が、買主に対し負う前項の契約不適合責任の内容は、修補にかぎるものとし、買主は、売主に対し、前項の契約不適合について、修補の請求以外に、本契約の無効の主張、本契約の解除、売買代金の減額請求および損害賠償の請求をすることはできません。ただし、前項の土地の契約不適合により本契約を締結した目的が達せられないときは、買主は、売主に対し、本契約を解除することができます。' => [],
            '買主は、売主に対し、本物件について第1項の契約不適合を発見したとき、すみやかに通知して、修補に急を要する場合を除いて立会う機会を与えなければなりません。' => [],
            '売主は、買主に対し、本契約締結時に第1項の契約不適合を知らなくても、本条の責任を負いますが、買主が本契約締結時に第1項の契約不適合を知っていたときは、売主は本条の責任を負いません。' => [],
        ];
        $article10Pattern3 = [
            '売主は、買主に対し、引渡された土地が品質に関して契約の内容に適合しないもの（以下「契約不適合」といいます。）であるときは、引渡完了日から3ヶ月以内に通知を受けたものにかぎり、契約不適合責任を負います。' => [],
            '売主が、買主に対し負う前項の契約不適合責任の内容は、修補にかぎるものとし、買主は、売主に対し、前項の契約不適合について、修補の請求以外に、本契約の無効の主張、本契約の解除、売買代金の減額請求および損害賠償の請求をすることはできません。ただし、前項の土地の契約不適合により本契約を締結した目的が達せられないときは、買主は、売主に対し、本契約を解除することができます。'  => [],
            '買主は、売主に対し、本物件について第1項の契約不適合を発見したとき、すみやかに通知して、修補に急を要する場合を除いて立会う機会を与えなければなりません。'  => [],
            '売主は、買主に対し、本契約締結時に第1項の契約不適合を知らなくても、本条の責任を負いますが、買主が本契約締結時に第1項の契約不適合を知っていたときは、売主は本条の責任を負いません。' => []
        ];
        if($contractSeller == 2){
            PurchaseContractCreateReport::article10Output($worksheet, $spreadsheet, $article10Pattern1);
        }else if($contractSeller == 1 && $existBuildingResidential == 1){
            PurchaseContractCreateReport::article10Output($worksheet, $spreadsheet, $article10Pattern2);
        }else if($contractSeller == 1 && $existBuildingResidential == 0){
            PurchaseContractCreateReport::article10Output($worksheet, $spreadsheet, $article10Pattern3);
        }
    }

    static function article10Output($worksheet, $spreadsheet, $arrPatternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        $patternLoopChild = 0;
        $newBlockRowChild = 0;
        foreach($arrPatternInput as $name => $pattern){
            //dd($name);

            if(!empty($pattern)){
                //dd($pattern);
                $newBlockRowChild = $newBlockRowChild + $newBlockRow;
                foreach($pattern as $chlid){
                    //for($i = 0; $i < 3; $i++){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore(234+$newBlockRowChild, 3); // Create new row
                    //}

                    $spreadsheet->getActiveSheet()->mergeCells('K'.(234+$newBlockRowChild).':'.'AW'.(236+$newBlockRowChild)); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('K'.(234+$newBlockRowChild).':'.'AW'.(236+$newBlockRowChild))->getAlignment()->setWrapText(true); // Wrap Text
                    $worksheet->getStyle('K'.(234+$newBlockRowChild))->getAlignment()->setHorizontal('left');
                    $worksheet->getStyle('K'.(234+$newBlockRowChild))->getFont()->setSize(14)->setName('Arial');
                    //$worksheet->getStyle('K'.(234+$newBlockRowChild))->getFont()->setSize(14);
                    $worksheet->getStyle('K'.(234+$newBlockRowChild))->getAlignment()->setVertical('top');
                    $worksheet->getCell('K'.(234+$newBlockRowChild))->setValue($chlid);

                    $spreadsheet->getActiveSheet()->mergeCells('I'.(234+$newBlockRowChild).':'.'J'.(235+$newBlockRowChild)); // Merge Text
                    $worksheet->getStyle('I'.(234+$newBlockRowChild))->getAlignment()->setHorizontal('left');
                    $worksheet->getCell('I'.(234+$newBlockRowChild))->setValue('('.($patternLoopChild+1).').');
                    $worksheet->getStyle('I'.(234+$newBlockRowChild))->getFont()->setSize(14)->setName('Arial');
                    //$worksheet->getStyle('I'.(234+$newBlockRowChild))->getFont()->setSize(14);
                    $worksheet->getStyle('I'.(234+$newBlockRowChild))->getAlignment()->setVertical('top');

                    $newBlockRowChild += 3;
                    $patternLoopChild++;
                }
            }


            //if($patternLoop >= 1){
            if(strlen($name) < 230){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 3; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(234+$newBlockRow, 3); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(234+$newBlockRow).':'.'H'.(235+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 330){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 4; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(234+$newBlockRow, 4); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+1)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+1))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(234+$newBlockRow).':'.'H'.(235+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 470){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 5; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(234+$newBlockRow, 5); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+2)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+2))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(234+$newBlockRow).':'.'H'.(235+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 600){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 6; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(234+$newBlockRow, 6); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+3)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(234+$newBlockRow).':'.'AW'.(236+$newBlockRow+3))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(234+$newBlockRow).':'.'H'.(235+$newBlockRow)); // Merge Text
            }
            //}
            if($patternLoop < 1){
                $spreadsheet->getActiveSheet()->mergeCells('B'.(234+$newBlockRow).':'.'E'.(235+$newBlockRow)); // Merge Text
                $worksheet->getCell('B'.(234+$newBlockRow))->setValue('第10条')->getStyle()->getFont()->setSize(14);
                $worksheet->getStyle('B'.(234+$newBlockRow))->getAlignment()->setHorizontal('center');
            }

            $worksheet->getCell('F'.(234+$newBlockRow))->setValue($patternLoop+1 .". "." ")->getStyle()->getFont()->setSize(14);
            $worksheet->getStyle('F'.(234+$newBlockRow))->getAlignment()->setHorizontal('left');

            $worksheet->getCell('I'.(234+$newBlockRow))->setValue($name)->getStyle()->getFont()->setSize(14)->setName('Arial');
            //$worksheet->getStyle('I'.(234+$newBlockRow))->getFont()->setSize(14);
            $worksheet->getStyle('I'.(234+$newBlockRow))->getAlignment()->setVertical('top');
            //$worksheet->getStyle('I'.(234+$newBlockRow))->getFont()->setName('Arial');

            $worksheet->getStyle('F'.(234+$newBlockRow))->getAlignment()->setVertical('top');


            $patternLoop++;
            if(strlen($name) < 230){
                $newBlockRow = ($newBlockRow + 3) + $newBlockRowChild;
            }else if(strlen($name) < 350){
                $newBlockRow = ($newBlockRow + 4) + $newBlockRowChild;
            }else if(strlen($name) < 470){
                $newBlockRow = ($newBlockRow + 5) + $newBlockRowChild;
            }else if(strlen($name) < 600){
                $newBlockRow = ($newBlockRow + 6) + $newBlockRowChild;
            }

            $newBlockRowChild = 0;

        }
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 12
    // ------------------------------------------------------------------
    static function article12Condition($worksheet, $spreadsheet, $contractSeller){ // Already to fix feedback
        $article12Pattern1 = [
            '売主、買主は、その相手方が本契約にかかる債務の履行を遅滞したとき、その相手方に対し、相当の期間を定めて債務の履行を催告したうえで、その期間内に履行がないときは、本契約を解除することができます。なお、第13条第1項の契約不適合について売主が同条同項第1号の修補または同項第2号の代金減額を遅滞した場合を含めます。'
                => [],
            '前項の規定による契約解除において、売主、買主は、その相手方に表記違約金（以下「違約金」といいます。）の支払いを請求することができます。ただし、本契約および社会通念に照らして相手方の責めに帰すことができない事由によるものであるときは、違約金の請求はできません。なお、違約金に関し、現に生じた損害額の多寡を問わず、相手方に違約金の増減を請求することができません。'
                => [],
            '違約金の支払い、清算は次のとおり行います。'
                => ['売主が違約した場合、売主は、買主に対し、すみやかに受領済みの金員を無利息にて返還するとともに、違約金を支払います。',
                    '買主が違約した場合、違約金が支払い済みの金員を上回るときは、買主は、売主に対し、すみやかにその差額を支払い、支払い済みの金員が違約金を上回るときは、売主は、買主に対し、受領済みの金員から違約金相当額を控除して、すみやかに残額を無利息にて返還します。'
                ]
        ];
        $article12Pattern2 = [
            '売主、買主は、その相手方が本契約にかかる債務の履行を遅滞したとき、その相手方に対し、相当の期間を定めて債務の履行を催告したうえで、その期間内に履行がないときは、本契約を解除することができます。なお、第13条第1項の契約不適合について売主が同条第2項の修補を遅滞した場合を含めます。' => [],
            '前項の規定による契約解除において、売主、買主は、その相手方に表記違約金（以下「違約金」といいます。）の支払いを請求することができます。ただし、本契約および社会通念に照らして相手方の責めに帰すことができない事由によるものであるときは、違約金の請求はできません。なお、違約金に関し、現に生じた損害額の多寡を問わず、相手方に違約金の増減を請求することができません。' => [],
            '違約金の支払い、清算は次のとおり行います。'
                => ['売主が違約した場合、売主は、買主に対し、すみやかに受領済みの金員を無利息にて返還するとともに、違約金を支払います。',
                    '買主が違約した場合、違約金が支払い済みの金員を上回るときは、買主は、売主に対し、すみやかにその差額を支払い、支払い済みの金員が違約金を上回るときは、売主は、買主に対し、受領済みの金員から違約金相当額を控除して、すみやかに残額を無利息にて返還します。'
                ]
        ];

        if($contractSeller == 2){
            PurchaseContractCreateReport::article12Output($worksheet, $spreadsheet, $article12Pattern1);
        }else if($contractSeller == 1){
            PurchaseContractCreateReport::article12Output($worksheet, $spreadsheet, $article12Pattern2);
        }
    }

    static function article12Output($worksheet, $spreadsheet, $arrPatternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        $patternLoopChild = 0;
        $newBlockRowChild = 0;
        foreach($arrPatternInput as $name => $pattern){
            //dd($name);
            //if($patternLoop >= 1){
                if(!empty($pattern)){
                    //dd($newBlockRow);
                    $newBlockRowChild = $newBlockRowChild + $newBlockRow;
                    foreach($pattern as $chlid){
                        //for($i = 0; $i < 3; $i++){
                            $spreadsheet->getActiveSheet()->insertNewRowBefore(251+$newBlockRowChild, 3); // Create new row
                        //}

                        $spreadsheet->getActiveSheet()->mergeCells('K'.(251+$newBlockRowChild).':'.'AW'.(253+$newBlockRowChild)); // Merge cell
                        $spreadsheet->getActiveSheet()->getStyle('K'.(251+$newBlockRowChild).':'.'AW'.(253+$newBlockRowChild))->getAlignment()->setWrapText(true); // Wrap Text
                        $worksheet->getStyle('K'.(251+$newBlockRowChild))->getAlignment()->setHorizontal('left');
                        $worksheet->getStyle('K'.(251+$newBlockRowChild))->getFont()->setSize(14)->setName('Arial');
                        //$worksheet->getStyle('K'.(251+$newBlockRowChild))->getFont()->setSize(14);
                        $worksheet->getStyle('K'.(251+$newBlockRowChild))->getAlignment()->setVertical('top');
                        $worksheet->getCell('K'.(251+$newBlockRowChild))->setValue($chlid);

                        $spreadsheet->getActiveSheet()->mergeCells('I'.(251+$newBlockRowChild).':'.'J'.(252+$newBlockRowChild)); // Merge Text
                        $worksheet->getStyle('I'.(251+$newBlockRowChild))->getAlignment()->setHorizontal('left');
                        $worksheet->getCell('I'.(251+$newBlockRowChild))->setValue('('.($patternLoopChild+1).').')->getStyle()->getFont()->setSize(14)->setName('Arial');
                        //$worksheet->getStyle('I'.(251+$newBlockRowChild))->getFont()->setName('Arial');
                        //$worksheet->getStyle('I'.(251+$newBlockRowChild))->getFont()->setSize(14);
                        $worksheet->getStyle('I'.(251+$newBlockRowChild))->getAlignment()->setVertical('top');

                        $newBlockRowChild += 3;
                        $patternLoopChild++;
                    }
                }

            if(strlen($name) < 230){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 3; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(251+$newBlockRow, 3); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(251+$newBlockRow).':'.'H'.(252+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 350){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 4; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(251+$newBlockRow, 4); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+1)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+1))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(251+$newBlockRow).':'.'H'.(252+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 470){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 5; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(251+$newBlockRow, 5); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+2)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+2))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(251+$newBlockRow).':'.'H'.(252+$newBlockRow)); // Merge Text
            }else if(strlen($name) < 600){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 6; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(251+$newBlockRow, 6); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+3)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(251+$newBlockRow).':'.'AW'.(253+$newBlockRow+3))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(251+$newBlockRow).':'.'H'.(252+$newBlockRow)); // Merge Text
            }
            //}
            if($patternLoop < 1){
                $spreadsheet->getActiveSheet()->mergeCells('B'.(251+$newBlockRow).':'.'E'.(252+$newBlockRow)); // Merge Text
                $worksheet->getCell('B'.(251+$newBlockRow))->setValue('第12条')->getStyle()->getFont()->setSize(14);
                $worksheet->getStyle('B'.(251+$newBlockRow))->getAlignment()->setHorizontal('center');
            }
            $worksheet->getCell('F'.(251+$newBlockRow))->setValue($patternLoop+1 .". "." ")->getStyle()->getFont()->setSize(14);
            $worksheet->getStyle('F'.(251+$newBlockRow))->getAlignment()->setHorizontal('left');

            $worksheet->getCell('I'.(251+$newBlockRow))->setValue($name);
            $worksheet->getStyle('I'.(251+$newBlockRow))->getFont()->setSize(14);
            $worksheet->getStyle('I'.(251+$newBlockRow))->getAlignment()->setVertical('top');
            $worksheet->getStyle('I'.(251+$newBlockRow))->getFont()->setName('Arial');

            //

            $patternLoop++;
            if(strlen($name) < 230){
                $newBlockRow = ($newBlockRow + 3) + $newBlockRowChild;
            }else if(strlen($name) < 350){
                $newBlockRow = ($newBlockRow + 4) + $newBlockRowChild;
            }else if(strlen($name) < 470){
                $newBlockRow = ($newBlockRow + 5) + $newBlockRowChild;
            }else if(strlen($name) < 600){
                $newBlockRow = ($newBlockRow + 6) + $newBlockRowChild;
            }
            //$newBlockRow = ($newBlockRow + 6) + $newBlockRowChild;
        }
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 13
    // ------------------------------------------------------------------
    static function article13Condition($worksheet, $spreadsheet, $contractSeller){ // Already to fix feedback
        $article13Pattern1 = [
            '売主、買主は、その相手方が本契約の履行に着手するまでは、互いに書面により通知して、買主は、売主に対し、手付金を放棄して、売主は、買主に対し、手付金等受領済みの金員および手付金と同額の金員を買主に現実に提供することにより、本契約を解除することができます。'
        ];
        $article13Pattern2 = [
            '売主、買主は、本契約を表記手付解除期日までであれば、その相手方の本契約の履行着手の有無にかかわらず、互いに書面により通知して、解除することができます。',
            '売主が前項により本契約を解除するときは、売主は、買主に対し、手付金等受領済みの金員および手付金と同額の金員を現実に提供しなければなりません。買主が前項により本契約を解除するときは、買主は、売主に対し、支払い済みの手付金の返還請求を放棄します。'
        ];

        if($contractSeller == 2){
            PurchaseContractCreateReport::article13Output($worksheet, $spreadsheet, $article13Pattern1);
        }else if($contractSeller == 1){
            PurchaseContractCreateReport::article13Output($worksheet, $spreadsheet, $article13Pattern2);
        }
    }

    static function article13Output($worksheet, $spreadsheet, $patternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        foreach($patternInput as $pattern){
            if($patternLoop >= 1){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 4; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(260+$newBlockRow, 4); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(260+$newBlockRow).':'.'AW'.(263+$newBlockRow)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(260+$newBlockRow).':'.'AW'.(263+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(260+$newBlockRow).':'.'H'.(261+$newBlockRow)); // Merge Text
            }
            $worksheet->getCell('F'.(260+$newBlockRow))->setValue($patternLoop+1 .". "." ")->getStyle()->getFont()->setSize(14);;
            $worksheet->getStyle('F'.(260+$newBlockRow))->getAlignment()->setHorizontal('left');

            $worksheet->getCell('I'.(260+$newBlockRow))->setValue($pattern)->getStyle()->getFont()->setSize(14)->setName('Arial');
            //$worksheet->getStyle('I'.(260+$newBlockRow))->getFont()->setSize(14);
            $worksheet->getStyle('I'.(260+$newBlockRow))->getAlignment()->setVertical('top');
            //$worksheet->getStyle('I'.(260+$newBlockRow))->getFont()->setName('Arial');

            $worksheet->getStyle('F'.(260+$newBlockRow))->getAlignment()->setVertical('top');

            $patternLoop++;
            $newBlockRow = $newBlockRow + 4;
        }
    }
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Report function for Article 15 ->
    // ------------------------------------------------------------------
    static function article16Condition($worksheet, $spreadsheet, $cArticle15Contract, $cArticle15BurdenContract, $cArticle15BaseContract){
        $article15PatternA = [
            '本契約書に貼付する印紙については、原本を保有する売主が負担するものとします。'
        ];
        $article15PatternB = [
            '本契約書に貼付する印紙については、原本を保有する買主が負担するものとします。'
        ];
        $article15PatternC = [
            '本契約書に貼付する印紙については、売主・買主が折半して負担するものとします。'
        ];
        $article15PatternE = [
            '本契約書に貼付する印紙については、売主・買主がそれぞれ平等に負担するものとします。'
        ];

        if($cArticle15Contract == 1 && $cArticle15BurdenContract == 1){
            PurchaseContractCreateReport::article16Output($worksheet, $spreadsheet, $article15PatternA);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 2){
            PurchaseContractCreateReport::article16Output($worksheet, $spreadsheet, $article15PatternB);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 3 && $cArticle15BaseContract == 1){
            PurchaseContractCreateReport::article16Output($worksheet, $spreadsheet, $article15PatternC);
        }else if($cArticle15Contract == 1 && $cArticle15BurdenContract == 3 && $cArticle15BaseContract == 2){
            PurchaseContractCreateReport::article16Output($worksheet, $spreadsheet, $article15PatternC);
        }else if($cArticle15Contract == 2){
            PurchaseContractCreateReport::article16Output($worksheet, $spreadsheet, $article15PatternE);
        }
    }

    static function article16Output($worksheet, $spreadsheet, $patternInput){
        $patternLoop = 0;
        $newBlockRow = 0;
        foreach($patternInput as $pattern){
            if($patternLoop >= 1){
                $newBlockRow = $newBlockRow;
                //for($i = 0; $i < 6; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(341+$newBlockRow, 6); // Create new row
                //}
                $spreadsheet->getActiveSheet()->mergeCells('I'.(341+$newBlockRow).':'.'AW'.(346+$newBlockRow)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('I'.(341+$newBlockRow).':'.'AW'.(346+$newBlockRow))->getAlignment()->setWrapText(true); // Wrap Text
                $spreadsheet->getActiveSheet()->mergeCells('F'.(341+$newBlockRow).':'.'H'.(342+$newBlockRow)); // Merge Text
            }
            $worksheet->getCell('F'.(341+$newBlockRow))->setValue($patternLoop+1 .". "." ");
            $worksheet->getStyle('F'.(341+$newBlockRow))->getAlignment()->setHorizontal('left');

            $worksheet->getCell('I'.(341+$newBlockRow))->setValue($pattern);
            $worksheet->getStyle('I'.(341+$newBlockRow))->getFont()->setSize(14);
            $worksheet->getStyle('I'.(341+$newBlockRow))->getAlignment()->setVertical('top');
            $worksheet->getStyle('I'.(341+$newBlockRow))->getFont()->setName('Arial');

            $worksheet->getStyle('F'.(341+$newBlockRow))->getAlignment()->setVertical('top');

            $patternLoop++;
            $newBlockRow = $newBlockRow + 6;
        }
    }

    static function ownerShare($pjLotOwner)
    {
        // get owner share denom data
        // update null value to 1
        // ---------------------------------------------------------------------
        $share_denoms = $pjLotOwner->map(function ($value, $key) {
            if ($value->share_denom)
                return $value->share_denom;
            else
                return 1; // initial value
        })->filter();
        // ---------------------------------------------------------------------

        // get multiplication result of share denom
        // ---------------------------------------------------------------------
        $total_denom = 1; // initial data
        foreach ($share_denoms as $key => $denom) {
            $total_denom *= $denom;
        }
        // ---------------------------------------------------------------------

        // get owner share number and make calculation to get total share number
        // ---------------------------------------------------------------------
        $total_number = $pjLotOwner->map(function ($value, $key) use ($total_denom, $share_denoms) {
            return $total_denom / $share_denoms[$key] * $value->share_number;
        })->sum();
        // ---------------------------------------------------------------------

        // get gcf of total share number and total share denom
        // ---------------------------------------------------------------------
        $gcf = 1; // initial data
        for ($i=2; $i <= $total_number; $i++) {
            if ($total_number % $i == 0 && $total_denom % $i == 0) {
                $gcf = $i; // get biggest value
            }
        }
        $share_number = $total_number / $gcf;
        $share_denom  = $total_denom / $gcf;
        // ---------------------------------------------------------------------

        // assign data
        // ---------------------------------------------------------------------
        $data = new \stdClass;
        $data->share_number = $share_number;
        $data->share_denom  = $share_denom;
        // ---------------------------------------------------------------------

        return $data;
    }
    // ------------------------------------------------------------------
}
