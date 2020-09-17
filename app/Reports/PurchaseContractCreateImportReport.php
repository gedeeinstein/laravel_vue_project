<?php

namespace App\Reports;

// --------------------------------------------------------------------------
use App\Models\PjProperty;
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialB;
use App\Models\PjLotRoadA;
use App\Models\PjLotBuildingA;
use App\Models\PjBuildingFloorSize;
use App\Models\PjLotResidentialOwner;
use App\Models\PjLotRoadOwner;
use App\Models\MasterRegion;
use App\Models\PjLotRoadParcelUseDistrict;
use App\Models\PjLotResidentialParcelUseDistrict;
use App\Models\PjLotRoadB;
use App\Models\PjPropertyRestriction;
// --------------------------------------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
// --------------------------------------------------------------------------
use App\Models\PjPurchaseSale;
use App\Models\Company;
use App\Models\CompanyOffice;
use App\Models\PjPurchaseTargetContractor;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseContractCreate;
use App\Models\PjPurchaseTargetBuilding;
use App\Models\MasterValue;
use App\Models\User;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use File;
use Auth;
// --------------------------------------------------------------------------

class PurchaseContractCreateImportReport
{
    public static function convert_to_nengou($year) {
        $nengou = '';
        if($year > 1867 && $year <= 1911) {
            $nengou = '明治'. ($year - 1867);
        } else
        if($year > 1911 && $year <= 1925) {
            $nengou = '大正'. ($year - 1911);
        } else
        if($year > 1925 && $year <= 1988) {
            $nengou = '昭和'. ($year - 1925);
        } else
        if($year > 1988 && $year <= 2018) {
            $nengou = '平成'. ($year - 1988);
        } else
        if($year > 2018) {
            $nengou = '令和'. ($year - 2018);
        }

        return $nengou;
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
        });
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

    public static function purchaseContractCreateImport($data, $projectId, $targetId){
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('reports/template/purchase-contract-create-import.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 重要事項説明書
        // ------------------------------------------------------------------

        $residentials = PjLotResidentialA::where('pj_property_id', $projectId ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios')->get();
        $roads = PjLotRoadA::where('pj_property_id', $projectId ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios')->get();

        $districtarr        = [];
        $districtarrsort    = [];
        foreach($residentials as $residential) {
            foreach($residential->use_districts as $use_district) {
                array_push($districtarr, $use_district->value);
            }
        }
        foreach($roads as $road) {
            foreach($road->use_districts as $use_district) {
                array_push($districtarr, $use_district->value);
            }
        }
        asort($districtarr);

        if(count($districtarr) < 3) {
            for($a=count($districtarr);$a<3;$a++) {
                array_push($districtarr, 0);
            }
        }

        foreach($districtarr as $district_data) {
            array_push($districtarrsort, $district_data);
        }

        $worksheet->getCell('U210')->setValue('■');
        if($districtarrsort[1] == 37 || $districtarrsort[1] == 38) {
            $worksheet->getCell('X211')->setValue('■');
        } else {
            $worksheet->getCell('U211')->setValue('■');
        }
        if($districtarrsort[2] == 37 || $districtarrsort[2] == 38 || $districtarrsort[2] == 39 || $districtarrsort[2] == 40) {
            $worksheet->getCell('U212')->setValue('■');
        } else {
            $worksheet->getCell('X212')->setValue('■');
        }

        $user                   = Auth::user();
        $purchase_sale          = PjPurchaseSale::where('project_id', $projectId)->first();
        $company_organizer      = Company::find($purchase_sale->company_id_organizer);
        $company_office         = CompanyOffice::where('company_id', $company_organizer->id)->first();
        if($company_office == null) {
            $company_office         = new CompanyOffice;
        }
        $target_users           = PjPurchaseTargetContractor::where('pj_purchase_target_id', $targetId)->get();
        $purchase_contract      = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();

        $target_user_list   = ',';
        $all_user           = '';
        foreach($target_users as $target_user) {
            if(strpos($target_user_list, ','.$target_user->user_id.',') === false) {
                $target_user_list .= $target_user->user_id.',';
                $current_user = User::find($target_user->user_id);
                $all_user .= $current_user->last_name.' '.$current_user->first_name.'、';
            }
        }
        $all_user           = mb_substr($all_user, 0, -1);

        $worksheet->getCell('E8')->setValue($company_organizer->name);
        $worksheet->getCell('AG8')->setValue($all_user);

        $license_arr = [];
        $license_arr[0] = '';
        $license_arr[73] = '宮城県知事';
        $license_arr[74] = '福島県知事';
        $license_arr[75] = '岩手県知事';
        $license_arr[76] = '山形県知事';
        $license_arr[77] = '国土交通省';

        if($company_organizer->license_authorizer_id != null) {
            $license = $license_arr[$company_organizer->license_authorizer_id];
        } else {
            $license = '';
        }

        if($user->real_estate_notary_prefecture_id != null) {
            $prefecture = MasterRegion::find($user->real_estate_notary_prefecture_id);
        } else {
            $prefecture = new MasterRegion;
        }

        $convertDate = Carbon::parse($company_organizer->license_date);

        $nYear = PurchaseContractCreateImportReport::convert_to_nengou($convertDate->year);
        $worksheet->getCell('P17')->setValue($license.'('.$company_organizer->license_update.')'.$company_organizer->license_number);
        $worksheet->getCell('P18')->setValue($nYear. '年'. $convertDate->month. '月'. $convertDate->day. '日');
        $worksheet->getCell('P19')->setValue($company_organizer->real_estate_agent_office_main_address);
        $worksheet->getCell('P20')->setValue($company_organizer->name);
        $worksheet->getCell('P21')->setValue($company_organizer->real_estate_agent_representative_name);

        $worksheet->getCell('P23')->setValue($prefecture->name.$user->real_estate_notary_number);
        $worksheet->getCell('P24')->setValue($user->last_name.' '.$user->first_name);
        $worksheet->getCell('P25')->setValue($company_office->name.' '.$company_office->address);
        $worksheet->getCell('P28')->setValue($company_office->number);

        $zennichi_msg =
"公益社団法人不動産保証協会
東京都千代田区紀尾井町3番30号 全日会館
公益社団法人不動産保証協会宮城県本部
宮城県仙台市青葉区上杉1‐4ｰ1　中野プラザビル4階
東京法務局
東京都千代田区九段南一丁目1番15号 九段第2合同庁舎"
        ;
        $zentaku_msg =
"公益社団法人全国宅地建物取引業保証協会
東京都千代田区岩本町2丁目6番3号
公益社団法人全国宅地建物取引業保証協会宮城本部
宮城県仙台市青葉区国分町3-4-18
東京法務局
東京都千代田区九段南1丁目1番15号 九段第2合同庁舎"
        ;

        if($company_organizer->real_estate_guarantee_association == 'other') {
            $worksheet->getCell('N29')->setValue('□');
            $worksheet->getCell('N30')->setValue('■');
            $worksheet->getCell('N31')->setValue('');
            $worksheet->getCell('N37')->setValue($company_organizer->real_estate_agent_depositary_name.' '.$company_organizer->real_estate_agent_depositary_name_address);
        } else
        if($company_organizer->real_estate_guarantee_association == 'zennichi') {
            $worksheet->getCell('N31')->setValue($zennichi_msg);
            $worksheet->getCell('N37')->setValue('');
        } else
        if($company_organizer->real_estate_guarantee_association == 'zentaku') {
            $worksheet->getCell('N31')->setValue($zentaku_msg);
            $worksheet->getCell('N37')->setValue('');
        }

        if($purchase_contract !== null) {
            $worksheet->getCell('AL41')->setValue(substr($purchase_contract->contract_payment_date, 0, 4));
        }

        // ------------------------------------------------------------------



        // ------------------------------------------------------------------
        // Report output for II 取引条件に関する事項
        // ------------------------------------------------------------------

        $purchase_contract_create = PjPurchaseContractCreate::where('pj_purchase_contract_id', $purchase_contract->id)->first();
        if($purchase_contract_create == null) {
            $purchase_contract_create = new PjPurchaseContractCreate;
        }

        $worksheet->getCell('B446')->setValue(number_format($purchase_contract->contract_price).'円');

        if($purchase_contract_create->building_for_merchandise != null || $purchase_contract_create->building_for_merchandise != '') {
            $ad444 = number_format($purchase_contract->contract_price-$purchase_contract->contract_price_building)."円";
            $ad445 = number_format($purchase_contract->contract_price_building)."円";
            $tax = MasterValue::where('type', 'general_tax_rate')->first()->value;
            if($purchase_contract->contract_price_building_no_tax == 0) {
                $ad446 = number_format($purchase_contract->contract_price_building*$tax/(100+$tax)).'円';
            } else {
                $ad446 = '- 円';
            }
            $worksheet->getCell('AD444')->setValue($ad444);
            $worksheet->getCell('AD445')->setValue($ad445);
            $worksheet->getCell('AD446')->setValue($ad446);
        } else {
            $worksheet->getCell('AD444')->setValue('-');
            $worksheet->getCell('AD445')->setValue('-');
            $worksheet->getCell('AD446')->setValue('-');
        }

        $worksheet->getCell('X450')->setValue(number_format($purchase_contract->contract_price).'円');
        $worksheet->getCell('X451')->setValue(''); // not have db yet

        $change_option_1 = '(1) 手付解除（ □ 有 ・ □ 無 ）';
        if($purchase_contract_create->manual_release != null) {
            if($purchase_contract_create->manual_release == 1) {
                $change_option_1 = '(1) 手付解除（ ■ 有 ・ □ 無 ）';
            } else {
                $change_option_1 = '(1) 手付解除（ □ 有 ・ ■ 無 ）';
            }
        }
        $worksheet->getCell('B457')->setValue($change_option_1);
        // $mrl_date_1 = $purchase_contract_create->manual_release_limitdate;
        // $worksheet->getCell('AM457')->setValue(substr($mrl_date_1, 0, 4).'年'.substr($mrl_date_1, 5, 2).'月'.substr($mrl_date_1, 8, 2).'日');

        $section_6       = false;
        $change_option_2 = '(3) 融資利用の特約による解除（ □ 有 ・ □ 無 ）';
        if($purchase_contract_create->c_article15_contract != null) {
            if($purchase_contract_create->c_article15_contract == 1) {
                $change_option_2 = '(3) 融資利用の特約による解除（ ■ 有 ・ □ 無 ）';
                // $change_option_3 = '（ ■ 有 ・ □ 無 ）';
                $section_6       = true;
            } else {
                $change_option_2 = '(3) 融資利用の特約による解除（ □ 有 ・ ■ 無 ）';
                // $change_option_3 = '（ □ 有 ・ ■ 無 ）';
            }
        }
        $worksheet->getCell('B477')->setValue($change_option_2);
        $mrl_date_2 = $purchase_contract_create->c_article15_loan_release_date_contract;
        $worksheet->getCell('AM477')->setValue(substr($mrl_date_2, 0, 4).'年'.substr($mrl_date_2, 5, 2).'月'.substr($mrl_date_2, 8, 2).'日');
        // $worksheet->getCell('R484')->setValue($change_option_3);
        // $worksheet->getCell('AM484')->setValue(substr($mrl_date_2, 0, 4).'年'.substr($mrl_date_2, 5, 2).'月'.substr($mrl_date_2, 8, 2).'日');

        // if($purchase_contract_create->c_article10_contract == 1) {
        //     $change_option_4 = '（ ■ 有 ・ □ 無 ）';
        // } else {
        //     $change_option_4 = '（ □ 有 ・ ■ 無 ）';
        // }
        // $worksheet->getCell('R496')->setValue($change_option_4);

        if($purchase_contract_create->deposit_conservation_measures == 1) {
            $worksheet->getCell('B547')->setValue('■ 1.講じない');
            $worksheet->getCell('Z547')->setValue('□ 2.講じる （□ 1.未完成物件 ・ □ 2.完成物件 ）');
            $change_option_5 =
"□ 1.講じる
■ 2.講じない";
        } else
        if($purchase_contract_create->deposit_conservation_measures == 2) {
            $worksheet->getCell('B547')->setValue('□ 1.講じない');
            $worksheet->getCell('Z547')->setValue('■ 2.講じる （■ 1.未完成物件 ・ □ 2.完成物件 ）');
            $change_option_5 =
"■ 1.講じる
□ 2.講じない";
        } else
        if($purchase_contract_create->deposit_conservation_measures == 3) {
            $worksheet->getCell('B547')->setValue('□ 1.講じない');
            $worksheet->getCell('Z547')->setValue('■ 2.講じる （□ 1.未完成物件 ・ ■ 2.完成物件 ）');
            $change_option_5 =
"■ 1.講じる
□ 2.講じない";
        } else {
            $worksheet->getCell('B547')->setValue('□ 1.講じない');
            $worksheet->getCell('Z547')->setValue('□ 2.講じる （□ 1.未完成物件 ・ □ 2.完成物件 ）');
            $change_option_5 =
"□ 1.講じる
□ 2.講じない";
        }

        if($purchase_contract_create->deposit_conservation_method == 1) {
            $dcm_msg =
"■ 1. 保証委託契約
□ 2. 補償保険契約
□ 3. 手付金等寄託契約および質権設定契約";
        } else
        if($purchase_contract_create->deposit_conservation_method == 2) {
            $dcm_msg =
"□ 1. 保証委託契約
■ 2. 補償保険契約
□ 3. 手付金等寄託契約および質権設定契約";
        } else
        if($purchase_contract_create->deposit_conservation_method == 3) {
            $dcm_msg =
"□ 1. 保証委託契約
□ 2. 補償保険契約
■ 3. 手付金等寄託契約および質権設定契約";
        } else {
            $dcm_msg =
"□ 1. 保証委託契約
□ 2. 補償保険契約
□ 3. 手付金等寄託契約および質権設定契約";
        }


        $worksheet->getCell('AA548')->setValue($dcm_msg);
        $worksheet->getCell('AA552')->setValue($purchase_contract_create->deposit_conservation_period);

        $worksheet->getCell('N558')->setValue($change_option_5);
        $worksheet->getCell('AL558')->setValue($purchase_contract_create->deposit_conservation_period);

        if($purchase_contract->seller != 2) {
            $worksheet->getCell('B546')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('B547')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z547')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('B548')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('H548')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z548')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AA548')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('B552')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('H552')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z552')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AA552')->getStyle()->getFont()->setStrikethrough(true);
        }

        if($section_6) {
            $worksheet->getCell('B565')->setValue($purchase_contract_create->c_article15_loan_contract_0);
            $worksheet->getCell('B568')->setValue($purchase_contract_create->c_article15_loan_contract_1);
            $worksheet->getCell('L565')->setValue(number_format($purchase_contract_create->c_article15_loan_amount_contract_0/1000).'万円');
            $worksheet->getCell('L568')->setValue(number_format($purchase_contract_create->c_article15_loan_amount_contract_1/1000).'万円');
            if($purchase_contract_create->c_article15_loan_issue_contract_0 == 1) {
                $worksheet->getCell('AH565')->setValue('■ 有 ・ □ 無');
            } else {
                $worksheet->getCell('AH565')->setValue('□ 有 ・ ■ 無');
            }
            if($purchase_contract_create->c_article15_loan_issue_contract_1 == 1) {
                $worksheet->getCell('AH570')->setValue('■ 有 ・ □ 無');
            } else {
                $worksheet->getCell('AH570')->setValue('□ 有 ・ ■ 無');
            }
        } else {
            $worksheet->getCell('L563')->setValue('□ 有 ・ ■ 無');
            $worksheet->getCell('B565')->setValue('');
            $worksheet->getCell('B568')->setValue('');
            $worksheet->getCell('L565')->setValue('');
            $worksheet->getCell('L568')->setValue('');
            $worksheet->getCell('AH565')->setValue('');
            $worksheet->getCell('AH570')->setValue('');
            $worksheet->getCell('B564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('L563')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('B564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('L564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('R564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AH564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AP564')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('R565')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z565')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('R568')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('Z568')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AH565')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AH568')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('B571')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AP567')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('AP570')->getStyle()->getFont()->setStrikethrough(true);
        }

        if($purchase_contract_create->liability_for_collateral_measures == 1) {
            $worksheet->getCell('P576')->setValue('■ 1.講じる ・ □ 2.講じない');
        } else
        if($purchase_contract_create->liability_for_collateral_measures == 2) {
            $worksheet->getCell('P576')->setValue('□ 1.講じる ・ ■ 2.講じない');
        }
        $worksheet->getCell('P578')->setValue($purchase_contract_create->liability_for_collateral_measures_text);

        if($purchase_contract_create->c_article4_contract == 2) {
            $worksheet->getCell('H587')->setValue('□ 3.'.$purchase_contract_create->c_article4_sub_text_contract);
            $worksheet->getCell('H588')->setValue(number_format($purchase_contract_create->c_article4_clearing_standard_area).'㎡');
            $worksheet->getCell('AA588')->setValue(number_format($purchase_contract_create->c_article4_clearing_standard_area_cost).'円');
            $worksheet->getCell('B591')->setValue($purchase_contract_create->c_article4_clearing_standard_area_remarks);
        } else {
            $worksheet->getCell('H587')->setValue('');
            $worksheet->getCell('H588')->setValue('');
            $worksheet->getCell('AA588')->setValue('');
            $worksheet->getCell('B591')->setValue('');
        }

        if($purchase_contract->seller != 1) {
            for($i = 536; $i <= 540; $i++) {
                $worksheet->getRowDimension($i)->setVisible(false);
            }
        }
        if($purchase_contract->seller != 2) {
            for($i = 595; $i <= 611; $i++) {
                $worksheet->getRowDimension($i)->setVisible(false);
            }
        }

        $note_msg = '';
        if($purchase_contract_create->front_road_a == 1) {
            $note_msg .= '○ 買主は、その責任と負担において、引渡日までに本物件において建築確認済み証を取得することをとします。買主の責に帰さない事由により、建築確認済み証を取得できない場合は、本契約を解除出来るものとします。その場合、売主は受領済みの金員を全額無利息にて返還する事と致します。
';
        }
        if($purchase_contract_create->front_road_b == 1) {
            $note_msg .= '○ 売主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、売主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。
';
        }
        if($purchase_contract_create->front_road_c == 1) {
            $note_msg .= '○ 買主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、買主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。
';
        }
        if($purchase_contract_create->front_road_d == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、引渡日までに道路部分と宅地部分の分筆登記を完了させる事とします。
';
        }
        if($purchase_contract_create->front_road_e == 1) {
            $note_msg .= '○ 売主は、引渡日までに道路部分と宅地部分の分筆登記を完了させる事としますが、その費用は買主の負担とします。
';
        }
        if($purchase_contract_create->front_road_f == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、引渡日までに狭隘協議を行った上で分筆登記を完了させる事とします。
';
        }
        if($purchase_contract_create->front_road_g == 1) {
            $note_msg .= '○ 売主は、引渡日までに狭隘協議を行った上で分筆登記を完了させる事としますが、その費用は買主の負担とします。
';
        }
        if($purchase_contract_create->front_road_h == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、役所が指導する道路後退距離を確保するよう、引渡日までに分筆登記を完了させることとします。
';
        }
        if($purchase_contract_create->front_road_i == 1) {
            $note_msg .= '○ 売主は、役所が指導する道路後退距離を確保するよう、引渡日までに分筆登記を完了させることとします。ただし、分筆登記にかかる費用は買主の負担とします。
';
        }
        if($purchase_contract_create->front_road_j == 1) {
            $note_msg .= '○ 本物件の引渡しは、本物件の使用収益開始日以降とします。したがって表記の引渡し日までに、使用収益開始が間に合わない場合は、使用収益開始日以降に引き渡しが延期になるものとします。
';
        }
        if($purchase_contract_create->front_road_k == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、引渡日までに本物件の前面道路の持分及び他共有者全員からの通行掘削同意書の署名・捺印を取得し、当該持分とその権限を買主に引き渡すものとします。尚、当該持分の価格は本物件の売買価格に含まれるものとします。万が一、売主が前面道路の持分及び通行掘削同意書の署名・捺印を取得できない場合は、本契約は白紙解除になります。
';
        }
        if($purchase_contract_create->front_road_l == 1) {
            $note_msg .= '○ 本物件の引渡日までに、買主がその責任と負担で、前面道路の持分及び他共有者全員からの通行掘削同意書の署名・捺印を取得することを本契約の停止条件とします。万が一、買主が前面道路の持分及び通行掘削同意書の署名・捺印を取得できない場合は、本契約は白紙解除になります。
';
        }
        if($purchase_contract_create->agricultural_section_a == 1) {
            $note_msg .= '○ 本契約締結後、売主・買主は互いに協力して速やかに農地転用の手続きを行い、受理通知書又は許可証を取得することを本契約の停止条件とします。尚、当該申請にかかる費用は売主の負担とします。万が一、受理通知書又は許可証が取得できない場合、本契約は白紙解除になります。
';
        }
        if($purchase_contract_create->agricultural_section_b == 1) {
            $note_msg .= '○ 本契約締結後、売主・買主は互いに協力して速やかに農地転用の手続きを行い、受理通知書又は許可証を取得することを本契約の停止条件とします。尚、当該申請にかかる費用は買主の負担とします。万が一、受理通知書又は許可証が取得できない場合、本契約は白紙解除になります。
';
        }
        if($purchase_contract_create->development_permission == 1) {
            $note_msg .= '○ 買主はその責任と負担において、本契約締結後速やかに本物件における開発工事の申請を役所等に行い、引渡日までに開発許可証を取得することとします。買主の責に帰さない事由により、当該許可証が取得できない場合、本契約は白紙解除になります。また、当該許可証の取得が遅れる場合については、売主・買主が協議し双方合意することで、引渡日を延期することができます。
';
        }
        if($purchase_contract_create->cross_border == 1) {
            $note_msg .= '○ 測量の結果、本物件隣地の構造物等が越境していることが判明した場合、売主の責任において当該越境状態の解消して引き渡すものとします。越境状態が解消できない場合には、買主は契約の解除する事が出来るものとします。その場合、売主は受領済の金員を全額無利息にて買主に返還することと致します。 ただし、越境に関する覚書で問題を解決できる場合は、覚書の署名捺印取得を本物件引渡日までに完了させる事を条件に引渡しが出来るものとします。また、引き渡し後に越境が判明した場合についても売主は同様の義務を負うものとします。
';
        }
        if($purchase_contract_create->trading_other_people == 1) {
            $note_msg .= '○ 売主はその責任と負担において引渡日までに、その所有者が自己の名義となるように所有権移転登記又は相続登記を完了させ、登記簿謄本を買主に提出するものとします。
';
        }
        if($purchase_contract_create->separate_with_pen_a == 1) {
            $note_msg .= '○ 売主は、別添分筆計画図の通り分筆登記を完了させた上で、該当範囲を買主に引き渡すものとします。尚、当該分筆登記にかかる費用は売主の負担とします。
';
        }
        if($purchase_contract_create->separate_with_pen_b == 1) {
            $note_msg .= '○ 売主は、別添分筆計画図の通り分筆登記を完了させた上で、該当範囲を買主に引き渡すものとします。尚、当該分筆登記にかかる費用は買主の負担とします。
';
        }
        if($purchase_contract_create->building_for_merchandise_a == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、決済日までに本物件の敷地内（建物内外問わず）に放置物等が有る場合、撤去するものとします。
';
        }
        if($purchase_contract_create->building_for_merchandise_b == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、決済日までにホームスペクションを実施し、成果物を買主に引渡しものとします。
';
        }
        if($purchase_contract_create->building_for_merchandise_c == 1) {
            $note_msg .= '○ 売主は、その責任と負担において、引渡しまでの間に発覚した不具合については補修するのものとします。
';
        }
        if($purchase_contract_create->profitable_property_a == 1) {
            $note_msg .= '○ 売主は賃貸借契約者との契約内容について買主に不利な条項かつ未申告内容があった場合、違約金などの対象になる事を了承するものとします。
';
        }
        if($purchase_contract_create->profitable_property_b == 1) {
            $note_msg .= '○ 売主は家賃の延滞等について未申告があった場合、違約金などの対象になる事を了承するものとします。
';
        }
        if($purchase_contract_create->remarks_other == 1) {
            $note_msg .= '○ 売主は、買主が再販売を目的として購入することを承諾し、契約日以降の販売活動を認めるものとします。
';
        }
        if($purchase_contract_create->original_contents_text_a != null || $purchase_contract_create->original_contents_text_a != '') {
            $note_msg .= '○ '.$purchase_contract_create->original_contents_text_a.'
';
        }
        if($purchase_contract_create->original_contents_text_b != null || $purchase_contract_create->original_contents_text_b != '') {
            $note_msg .= '○ '.$purchase_contract_create->original_contents_text_b.'
';
        }

        $note_msg .= '
1. 買主は、その責任と負担において、引渡日までに本物件において建築確認済み証を取得することをとします。買主の責に帰さない事由により、建築確認済み証を取得できない場合は、本契約を解除出来るものとします。その場合、売主は受領済みの金員を全額無利息にて返還する事と致します。
2. 売主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、売主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。
3. 売主は、その責任と負担において、引渡日までに道路部分と宅地部分の分筆登記を完了させる事とします。';

        $worksheet->getCell('B617')->setValue($note_msg);

        // ------------------------------------------------------------------



        // ------------------------------------------------------------------
        //Report output for Ⅰ 対象となる宅地に直接関係する事項
        // ------------------------------------------------------------------
        $getPropertiesId = PjProperty::where('project_id', $projectId)->first();
        $purchaseContract = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();
        $retriveDataPjLotResidentialA = PjLotResidentialA::where('pj_property_id', $getPropertiesId->id)->get();
        //$retriveDataPjLotResidentialB = PjLotResidentialB::where('pj_property_id', $getPropertiesId->id)->get();

        $getPjPropertyRestrictions = PjPropertyRestriction::where('pj_property_id', $getPropertiesId->id)->first();
        /*$getPjPropertyRestrictions = (object) array(
            'restriction_id' => 1,
        );*/

        $getPurchaseContractCreate = PjPurchaseContractCreate::where('pj_purchase_contract_id', $targetId)->first();
        /*$getPurchaseContractCreate = (object) array(
            'owner_address_a' => 'Test 123',
            'owner_a' => 1,
            'owner_name_a' => 'Robert Jr',
            'ownership_memo_a' => 'Memo A',
            'ownership_memo_b' => 'Memo B',
            'ownership_a' => 1,
            'ownership_b' => 1,
            'area_division' => 1,
            'residential_land_number' => 12345,
            'permission_number' => 123,
            'inspected_number' => 456,
            'completion_notice_number' => 789,
            'city_planning_facility' => 1,
            'city_planning_facility_possession' => 2,
            'city_planning_facility_possession_road' => 1,
            'city_planning_facility_possession_road_name' => 'Test',
            'city_planning_facility_possession_road_widht' => 123,
            'city_planning_facility_possession_memo' => 'Memo facility',
            'city_planning_facility' => 'Test',
            'urban_development_business' => 'Urban',
            'urban_development_business_memo' => 'Urban Memo',
            'registration_record_building_remarks' => 'Registration record building remarks',
            'use_district' => 1,
            'restricted_use_district' => 1,
            'restricted_use_district_text' => 'Resticted use district text',
            'use_district_text' => 'District Text',
            'building_coverage_ratio' => 50,
            'floor_area_ratio_text' => 30,
            'road_width' => 5,
            'fire_prevention_area' => 1,
            'wall_restrictions' => 1,
            'absolute_height_limit' => 2,
            'private_road_change_or_abolition_restrictions' => 1,
            'minimum_floor_area' => 1,
            'exterior_wall_receding' => 1,
            'building_agreement' => 1,
            'absolute_height_limit_text' => 5.5,

            'site_and_road' => '敷地等と道路との関係',
            'width' => 100,
            'length_of_roadway' => 50.5,
            'designated_date' => 8/5/2020,
            'number' => 12345,
            'setback' => 1,
            'setback_area' => 50,
            'restricted_ordinance' => 1,
            'alley_part_length' => 123,
            'alley_part_width' => 456,
            'restricted_ordinance_text' => '条例による制限（制限）',
            'road_type_text' => 'その他の入力',

            'provisional_land_change' => 1,
            'architectural_restrictions' => 1,
            'provisional_land_change_map' => 2,
            'liquidation' => 2,
            'liquidation_money' => 2,
            'liquidation_money_text' => 30,
            'levy' => 1,
            'levy_money' => 1,
            'levy_money_text' => 123,
            'other_legal_restrictions_text_a' => 'Duis sit amet odio faucibus, cursus turpis a, aliquam nulla. Nulla sit amet rhoncus ipsum',

            'road_private_burden_contract' => 1,
            'road_private_burden_area_contract' => 123,
            'road_private_burden_amount_contract' => 123,
            'road_private_burden_share_denom_contract' => 321,
            'road_private_burden_share_number_contract' => 543,

            'road_setback_area_size_contract' => 456,
            'remarks_contract' => 'Curabitur eu aliquam ipsum. Phasellus porttitor pretium lacinia. Proin sollicitudin velit non volutpat condimentum. Aenean ultricies pharetra viverra.',

            'shape_structure' => 1,

            'earth_and_sand_vigilance' => 2,
            'earth_and_sand_special_vigilance' => 1,

            'survey_status_results' => 'Nunc dictum, risus sed lacinia tincidunt, mauris lectus dapibus dui, at aliquam sem neque nec libero.',
            'performance_evaluation' => 1,

            'seismic_standard_certification' => 1,
            'seismic_diagnosis_performance_evaluation' => 1,
            'seismic_diagnosis_result' => 1,
            'seismic_diagnosis_presence' => 2,
            'seismic_diagnosis_remarks' => ' Curabitur tempus dignissim ante ac mattis. In vitae elit gravida, rutrum metus vel, dictum ex. In vel diam nunc.',
            'potable_water_facilities' => 2,
            'electrical_retail_company' => 1,
            'gas_facilities' => 3,
            'sewage_facilities' => 4,
            'miscellaneous_water_facilities' => 2,
            'rain_water_facilitie' => 1,
            'potable_water_front_road_piping' => 1,
            'potable_water_front_road_piping' => 123,
            'potable_water_on_site_service_pipe' => 1,
            'potable_water_on_site_service_pipe_text' => 10,
            'potable_water_private_pipe' => 2,
            'electrical_retail_company' => 2,
            'electrical_retail_company_name' => 'risus sed lacinia',
            'electrical_retail_company_address' => 'dignissim lacus vulputate nec',
            'electrical_retail_company_contact' => '0851234567',
            'gas_front_road_piping' => 2,
            'gas_on_site_service_pipe' => 1,
            'gas_on_site_service_pipe_text' => 15,
            'gas_private_pipe' => 1,
            'sewage_front_road_piping' => 2,
            'sewage_on_site_service_pipe' => 1,
            'sewage_on_site_service_pipe_text' => 12,
            'septic_tank_installation' => 2,
            'miscellaneous_water_front_road_piping' => 1,
            'miscellaneous_water_on_site_service_pipe' => 1,
            'miscellaneous_water_on_site_service_pipe_text' => 'sem neque nec libero',
            'rain_water_exclusion' => 1,
            'rain_water_facilities' => 2,
            'potable_water_schedule' => 1,
            'potable_water_schedule_year' => 2020,
            'potable_water_schedule_month' => 5,
            'potable_water_participation_fee' => 5,
            'electrical_schedule' => 1,
            'electrical_schedule_year' => 2020,
            'electrical_schedule_month' => 6,
            'electrical_charge' => 213,
            'gas_schedule' => 1,
            'gas_schedule_year' => 2020,
            'gas_schedule_month' => 7,
            'gas_charge' => 100,
            'sewage_schedule' => 1,
            'sewage_schedule_year' => 2020,
            'sewage_schedule_month' => 9,
            'sewage_charge' => 50,
            'miscellaneous_water_schedule' => 1,
            'miscellaneous_water_schedule_year' => 2020,
            'miscellaneous_water_schedule_month' => 2,
            'miscellaneous_water_charge' => 40,
            'rain_water_schedule' => 1,
            'rain_water_schedule_year' => 2020,
            'rain_water_schedule_month' => 9,
            'rain_water_charge' => 30,
            'water_supply_and_drainage_remarks' => 'Curabitur tempus dignissim ante ac mattis. In vitae elit gravida, rutrum metus vel, dictum ex. In vel diam nunc.',

            'maintenance_confirmed_certificat' => 1,
            'maintenance_inspection_certificate' => 2,
            'maintenance_renovation' => 1,
            'maintenance_renovation_confirmed_certificat' => 2,
            'maintenance_renovation_inspection_certificate' => 1,
            'maintenance_building_situation_survey' => 2,
            'maintenance_building_situation_survey_report' => 1,
            'maintenance_building_housing_performance_evaluation' => 1,
            'maintenance_building_housing_performance_evaluation_report' => 2,
            'maintenance_regular_survey_report' => 1,
            'maintenance_periodic_survey_report_a' => 1,
            'maintenance_periodic_survey_report_b' => 1,
            'maintenance_periodic_survey_report_c' => 2,
            'maintenance_periodic_survey_report_d' => 1,
            'maintenance_construction_started_before' => 1,
            'maintenance_construction_started_before_seismic_standard_certification' => 2,
            'maintenance_construction_started_before_sub' => 2,
            'maintenance_construction_started_before_sub_text' => 'Proin sollicitudin velit non volutpat condimentum. Aenean ultricies pharetra viverra',
            'maintenance_remarks' => 'Nunc dictum, risus sed lacinia tincidunt, mauris lectus dapibus dui, at aliquam sem neque nec libero.',

            'use_asbestos_Reference' => 1,
            'use_asbestos_Reference_text' => 'Proin molestie consectetur',
            'use_asbestos_record' => 2,

            'provisional_land_change_text' => 5,
            'minimum_floor_area_text' => 3,
        );*/
        // ------------------------------------------------------------------
        //Report output for 1. 登記記録に記録された事項※所有者の所有権取得日・原因等は添付する登記事項証明書（または登記簿謄本）に記載されています。
        // ------------------------------------------------------------------

        if($getPurchaseContractCreate->seller_and_occupancy_name != null) {
            $worksheet->getCell('F100')->setValue("（ □ 1.登記簿記載の所有者と同じ ・ ■ 2.登記簿記載の所有者と異なる）");
            $worksheet->getCell('P102')->setValue($getPurchaseContractCreate->seller_and_occupancy_name);
            $worksheet->getCell('P103')->setValue($getPurchaseContractCreate->seller_and_occupancy_address);
        } else {
            $worksheet->getCell('F100')->setValue("（ ■ 1.登記簿記載の所有者と同じ ・ □ 2.登記簿記載の所有者と異なる）");
        }
        $worksheet->getCell('P104')->setValue($getPurchaseContractCreate->seller_and_occupancy_remarks);

        $worksheet->getCell('P109')->setValue($getPurchaseContractCreate->seller_and_occupancy_occupation_name);
        $worksheet->getCell('P110')->setValue($getPurchaseContractCreate->seller_and_occupancy_occupation_address);
        $worksheet->getCell('P111')->setValue($getPurchaseContractCreate->seller_and_occupancy_occupation_matter);

        if(PjPurchaseTargetBuilding::where('pj_purchase_target_id', $targetId)->first() != null) {
            if(PjPurchaseTargetBuilding::where('pj_purchase_target_id', $targetId)->first()->purchase_third_person_occupied == 2) {
                $worksheet->getCell('P111')->getStyle()->getFont()->setStrikethrough(true);
                $worksheet->getCell('P111')->getStyle()->getFont()->setStrikethrough(true);
            }
        }

        // ------------------------------------------------------------------
        //Report output for (1) 土地
        // ------------------------------------------------------------------
        // Output for 住所
        // for land
        if($getPurchaseContractCreate->owner_a_land == 1){
            $worksheet->getCell('N121')->setValue("別添藤本の通り");
        }else{
            $worksheet->getCell('N121')->setValue($getPurchaseContractCreate->owner_address_a_land);
        }
        // Output for 氏名
        if($getPurchaseContractCreate->owner_a_land == 1){
            $worksheet->getCell('N123')->setValue("別添藤本の通り");
        }else{
            $worksheet->getCell('N123')->setValue($getPurchaseContractCreate->owner_name_a_land);
        }
        // Output for 所有権にかかる 権利に関する事項
        if($getPurchaseContractCreate->ownership_a_land == 1){
            $worksheet->getCell('F127')->setValue('■');
        }else{
            $worksheet->getCell('I127')->setValue('■');
        }
        if(!empty($getPurchaseContractCreate->ownership_memo_a_land)){
            $worksheet->getCell('N125')->setValue($getPurchaseContractCreate->ownership_memo_a_land);
        }else{
            $worksheet->getCell('N125')->setValue('');
        }
        // Output for 所有権にかかる 権利に関する事項
        if($getPurchaseContractCreate->ownership_b_land == 1){
            $worksheet->getCell('F131')->setValue('■');
        }else{
            $worksheet->getCell('I131')->setValue('■');
        }
        if(!empty($getPurchaseContractCreate->ownership_memo_b_land)){
            $worksheet->getCell('N129')->setValue($getPurchaseContractCreate->ownership_memo_b_land);
        }else{
            $worksheet->getCell('N129')->setValue('');
        }
        // for building
        if($getPurchaseContractCreate->owner_a_building == 1){
            $worksheet->getCell('N121')->setValue("別添藤本の通り");
        }else{
            $worksheet->getCell('N121')->setValue($getPurchaseContractCreate->owner_address_a_building);
        }
        // Output for 氏名
        if($getPurchaseContractCreate->owner_a_building == 1){
            $worksheet->getCell('N139')->setValue("別添藤本の通り");
        }else{
            $worksheet->getCell('N139')->setValue($getPurchaseContractCreate->owner_name_a_building);
        }
        // Output for 所有権にかかる 権利に関する事項
        if($getPurchaseContractCreate->ownership_a_building == 1){
            $worksheet->getCell('F145')->setValue('■');
        }else{
            $worksheet->getCell('I145')->setValue('■');
        }
        if(!empty($getPurchaseContractCreate->ownership_memo_a_building)){
            $worksheet->getCell('N141')->setValue($getPurchaseContractCreate->ownership_memo_a_building);
        }else{
            $worksheet->getCell('N141')->setValue('');
        }
        // Output for 所有権にかかる 権利に関する事項
        if($getPurchaseContractCreate->ownership_b_building == 1){
            $worksheet->getCell('F149')->setValue('■');
        }else{
            $worksheet->getCell('I149')->setValue('■');
        }
        if(!empty($getPurchaseContractCreate->ownership_memo_b_building)){
            $worksheet->getCell('N147')->setValue($getPurchaseContractCreate->ownership_memo_b_building);
        }else{
            $worksheet->getCell('N147')->setValue('');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        //Report output for 2.都市計画法、建築基準法等の法令に基づく制限の概要
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        //Report output for (1) 都市計画法に基づく制限
        // ------------------------------------------------------------------
        $purchaseSale = PjPurchaseSale::where('project_id', $projectId)->first();
        //dd($purchaseSale->project_urbanization_area);

        if($purchaseSale->project_urbanization_area != null) {
            if($purchaseSale->project_urbanization_area == 1 || $purchaseSale->project_urbanization_area == 3){
                $worksheet->getCell('L160')->setValue('■');
                $worksheet->getCell('M161')->setValue('■');
            }else if($purchaseSale->project_urbanization_area == 2){
                $worksheet->getCell('L160')->setValue('■');
                $worksheet->getCell('T161')->setValue('■');
            }else if($purchaseSale->project_urbanization_area == 4){
                $worksheet->getCell('L160')->setValue('■');
                $worksheet->getCell('AB161')->setValue('■');
            }else if($purchaseSale->project_urbanization_area == 5){
                $worksheet->getCell('L163')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->area_division != null){
            if($getPurchaseContractCreate->area_division == 1){
                $worksheet->getCell('U164')->setValue('■');
            }else{
                $worksheet->getCell('X164')->setValue('■');
            }
        }

        $worksheet->getCell('S166')->setValue($getPurchaseContractCreate->residential_land_number);
        $worksheet->getCell('S167')->setValue($getPurchaseContractCreate->permission_number);
        $worksheet->getCell('S168')->setValue($getPurchaseContractCreate->inspected_number);
        $worksheet->getCell('S169')->setValue($getPurchaseContractCreate->completion_notice_number);

        if($getPurchaseContractCreate->city_planning_facility != null){
            if($getPurchaseContractCreate->city_planning_facility == 1){
                $worksheet->getCell('E175')->setValue('■');
            }else{
                $worksheet->getCell('H175')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->city_planning_facility_possession != null) {
            if($getPurchaseContractCreate->city_planning_facility_possession == 1){
                $worksheet->getCell('L171')->setValue('■');
            }else{
                $worksheet->getCell('L175')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->city_planning_facility_possession_road != null) {
            if($getPurchaseContractCreate->city_planning_facility_possession_road == 1){
                $worksheet->getCell('M172')->setValue('■');
            }else{
                $worksheet->getCell('S172')->setValue('■');
            }
        }

        $worksheet->getCell('O174')->setValue($getPurchaseContractCreate->city_planning_facility_possession_road_name);
        $worksheet->getCell('Y174')->setValue(number_format($getPurchaseContractCreate->city_planning_facility_possession_road_widht) . 'm');
        $worksheet->getCell('Y175')->setValue($getPurchaseContractCreate->city_planning_facility_possession_memo);
        $worksheet->getCell('Q177')->setValue($getPurchaseContractCreate->urban_development_business_memo);
        $worksheet->getCell('B179')->setValue($getPurchaseContractCreate->registration_record_building_remarks);

        if($getPurchaseContractCreate->city_planning_facility != null) {
            if($getPurchaseContractCreate->city_planning_facility == 1){
                $worksheet->getCell('E175')->setValue('■');
            }else{
                $worksheet->getCell('H175')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->urban_development_business != null) {
            if($getPurchaseContractCreate->urban_development_business == 1){
                $worksheet->getCell('L177')->setValue('■');
            }else{
                $worksheet->getCell('O177')->setValue('■');
            }
        }

        // ------------------------------------------------------------------
        //Report output for (2) 建築基準法に基づく制限
        // ------------------------------------------------------------------
        $useDistrictMasterValue = MasterValue::where('type', 'usedistrict')->get();
        $pjLotRoadDistrict = PjLotRoadParcelUseDistrict::with('pj_lot_road_a')->first();
        $pjLotResidentDistrict = PjLotResidentialParcelUseDistrict::with('pj_lot_residential_a')->first();
        $pjProperty = PjProperty::where('project_id', $projectId)->first();

        $pjLotResidentB = PjLotResidentialB::with('residential_a')->first();
        $pjLotRoadB = PjLotRoadB::with('road_a')->first();

        foreach($useDistrictMasterValue as $district){
            if($district->id == $pjLotRoadDistrict->value){
                $worksheet->getCell('N185')->setValue($district->value);
            }
        }

        foreach($useDistrictMasterValue as $district){
            if($district->id == $pjLotResidentDistrict->value){
                $worksheet->getCell('Z185')->setValue($district->value);
            }
        }

        if($getPurchaseContractCreate->use_district == 1){
            $worksheet->getCell('D186')->setValue('■');
        }
        if($getPurchaseContractCreate->restricted_use_district == 1){
            $worksheet->getCell('D187')->setValue('■');
        }

        // Output for その他の地域地区等
        if($pjProperty->height_district != 6 && $pjProperty->height_district != null){
            $worksheet->getCell('N191')->setValue('■');
        }
        if(!empty($pjProperty->height_district_use)){
            $worksheet->getCell('N192')->setValue('■');
            $worksheet->getCell('O192')->setValue('高度利用地区'.'('. $pjProperty->height_district_use .')');
        }
        if(!empty($getPjPropertyRestrictions->restriction_id)){
            if($getPjPropertyRestrictions->restriction_id == 2){
                $worksheet->getCell('AF188')->setValue('■');
            }
        }

        if($pjLotResidentB->scenic_area == 1 || $pjLotRoadB->scenic_area == 1){
            $worksheet->getCell('AF190')->setValue('■');
        }

        if($pjLotResidentB->district_planning == 1 || $pjLotRoadB->district_planning == 1){
            $worksheet->getCell('AF190')->setValue('■');
        }
        if(!empty($getPjPropertyRestrictions->restriction_id)){
            if($getPjPropertyRestrictions->restriction_id == 3){
                $worksheet->getCell('AF191')->setValue('■');
            }
        }
        if(!empty($pjProperty->restriction_extra)){
            $worksheet->getCell('AF192')->setValue('■');
            $worksheet->getCell('AG192')->setValue('その他'.'('. $pjProperty->restriction_extra .')');
        }

        // ----------

        $worksheet->getCell('N186')->setValue($getPurchaseContractCreate->use_district_text);
        $worksheet->getCell('N187')->setValue($getPurchaseContractCreate->restricted_use_district_text);
        if($getPurchaseContractCreate->fire_prevention_area != 6){
            $worksheet->getCell('T193')->setValue($getPurchaseContractCreate->building_coverage_ratio*1 . '%');
        }
        if($getPurchaseContractCreate->fire_prevention_area == 1){
            $worksheet->getCell('N194')->setValue('■');
            $worksheet->getCell('AQ194')->setValue('10');
        }
        if($getPurchaseContractCreate->fire_prevention_area == 2){
            $worksheet->getCell('N195')->setValue('■');
            $worksheet->getCell('AG195')->setValue('10');
        }
        if($getPurchaseContractCreate->fire_prevention_area == 3){
            $worksheet->getCell('N196')->setValue('■');
            $worksheet->getCell('Z197')->setValue('20');
        }
        if($getPurchaseContractCreate->fire_prevention_area == 4){
            $worksheet->getCell('N198')->setValue('■');
        }
        if($getPurchaseContractCreate->fire_prevention_area == 5){
            $worksheet->getCell('N198')->setValue('■');
        }

        $worksheet->getCell('S201')->setValue($getPurchaseContractCreate->floor_area_ratio_text*1 . '%');
        $worksheet->getCell('T205')->setValue(number_format($getPurchaseContractCreate->road_width) . "m");

        $districtContainValue = 0;
        foreach($useDistrictMasterValue as $district){
            if($district->id == $pjLotRoadDistrict->value){
                if(strpos($district->value, '住居')){
                    $districtContainValue = 4;
                }else{
                    $districtContainValue = 6;
                }
            }
        }

        $worksheet->getCell('AE205')->setValue(($getPurchaseContractCreate->road_width * $districtContainValue / 10 * 100)*1 . "%");

        if($getPurchaseContractCreate->wall_restrictions != null) {
            if($getPurchaseContractCreate->wall_restrictions == 1){
                $worksheet->getCell('N206')->setValue('■');
            }else{
                $worksheet->getCell('Q206')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->minimum_floor_area != null) {
            if($getPurchaseContractCreate->minimum_floor_area == 1){
                $worksheet->getCell('N207')->setValue('■');
            }else{
                $worksheet->getCell('Q207')->setValue('■');
            }
        }

        $worksheet->getCell('T207')->setValue($getPurchaseContractCreate->minimum_floor_area_text);

        if($getPurchaseContractCreate->exterior_wall_receding == 1){
            $worksheet->getCell('AL206')->setValue('■');
        }else if($getPurchaseContractCreate->exterior_wall_receding == 2){
            $worksheet->getCell('AO206')->setValue('■');
        }else if($getPurchaseContractCreate->exterior_wall_receding == 3){
            $worksheet->getCell('AS206')->setValue('■');
        }

        if($getPurchaseContractCreate->building_agreement != null) {
            if($getPurchaseContractCreate->building_agreement == 1){
                $worksheet->getCell('AL207')->setValue('■');
            }else{
                $worksheet->getCell('AO207')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->absolute_height_limit != null) {
            if($getPurchaseContractCreate->absolute_height_limit == 1){
                $worksheet->getCell('P209')->setValue('■');
            }else if($getPurchaseContractCreate->absolute_height_limit == 2){
                $worksheet->getCell('T209')->setValue('■');
            }else{
                $worksheet->getCell('X209')->setValue('■');
            }
        }

        if(!empty($getPurchaseContractCreate->absolute_height_limit_text)){
            $worksheet->getCell('Z209')->setValue($getPurchaseContractCreate->absolute_height_limit_text);
        }

        if($getPurchaseContractCreate->private_road_change_or_abolition_restrictions != null) {
            if($getPurchaseContractCreate->private_road_change_or_abolition_restrictions == 1){
                $worksheet->getCell('AL213')->setValue('■');
            }else{
                $worksheet->getCell('AO213')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->private_road_change_or_abolition_restrictions == 1){
            $worksheet->getCell('B215')->setValue('"上記は【優先用途地域名】の制限です。
            用途地域：敷地が２以上の用途地域にわたるときは、用途地域の建築物の用途制限については建築基準法91条により敷地の過半が属する用途地域の制限を受けます。');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 敷地等と道路との関係
        // ------------------------------------------------------------------
        // select data value for site and road
        $side_and_road_main = [];
        $side_and_road_direction = [];
        $side_and_road_type = [];

        $side_and_road_direction[0] = '';
        $side_and_road_direction[1] = '東';
        $side_and_road_direction[2] = '西';
        $side_and_road_direction[3] = '南';
        $side_and_road_direction[4] = '北';
        $side_and_road_direction[5] = '北東';
        $side_and_road_direction[6] = '南東';
        $side_and_road_direction[7] = '北西';
        $side_and_road_direction[8] = '南西';

        $side_and_road_main[0] = '';
        $side_and_road_main[1] = '公道';
        $side_and_road_main[2] = '私道';

        // ------------------------------------------------------------------
        // data for site and road no 0
        if($getPurchaseContractCreate->create_site_and_road_direction_0 != null) $worksheet->getCell('E225')->setValue($side_and_road_direction[$getPurchaseContractCreate->create_site_and_road_direction_0]);
        if($getPurchaseContractCreate->create_site_and_road_0 != null) $worksheet->getCell('G225')->setValue($side_and_road_main[$getPurchaseContractCreate->create_site_and_road_0]);
        if($getPurchaseContractCreate->create_site_and_road_type_0 != null) $worksheet->getCell('O225')->setValue($getPurchaseContractCreate->create_site_and_road_type_0);
        $worksheet->getCell('X225')->setValue(number_format($getPurchaseContractCreate->width_0));
        $worksheet->getCell('AG225')->setValue(number_format($getPurchaseContractCreate->length_of_roadway_0));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // data for site and road no 1
        if($getPurchaseContractCreate->create_site_and_road_direction_1 != null) $worksheet->getCell('E226')->setValue($side_and_road_direction[$getPurchaseContractCreate->create_site_and_road_direction_1]);
        if($getPurchaseContractCreate->create_site_and_road_1 != null) $worksheet->getCell('G226')->setValue($side_and_road_main[$getPurchaseContractCreate->create_site_and_road_1]);
        if($getPurchaseContractCreate->create_site_and_road_type_1 != null) $worksheet->getCell('O226')->setValue($getPurchaseContractCreate->create_site_and_road_type_1);
        $worksheet->getCell('X226')->setValue(number_format($getPurchaseContractCreate->width_1));
        $worksheet->getCell('AG226')->setValue(number_format($getPurchaseContractCreate->length_of_roadway_1));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // data for site and road no 2
        if($getPurchaseContractCreate->create_site_and_road_direction_2 != null) $worksheet->getCell('E227')->setValue($side_and_road_direction[$getPurchaseContractCreate->create_site_and_road_direction_2]);
        if($getPurchaseContractCreate->create_site_and_road_2 != null) $worksheet->getCell('G227')->setValue($side_and_road_main[$getPurchaseContractCreate->create_site_and_road_2]);
        if($getPurchaseContractCreate->create_site_and_road_type_2 != null) $worksheet->getCell('O227')->setValue($getPurchaseContractCreate->create_site_and_road_type_2);
        $worksheet->getCell('X227')->setValue(number_format($getPurchaseContractCreate->width_2));
        $worksheet->getCell('AG227')->setValue(number_format($getPurchaseContractCreate->length_of_roadway_2));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // data for site and road no 3
        if($getPurchaseContractCreate->create_site_and_road_direction_3 != null) $worksheet->getCell('E228')->setValue($side_and_road_direction[$getPurchaseContractCreate->create_site_and_road_direction_3]);
        if($getPurchaseContractCreate->create_site_and_road_3 != null) $worksheet->getCell('G228')->setValue($side_and_road_main[$getPurchaseContractCreate->create_site_and_road_3]);
        if($getPurchaseContractCreate->create_site_and_road_type_3 != null) $worksheet->getCell('O228')->setValue($getPurchaseContractCreate->create_site_and_road_type_3);
        $worksheet->getCell('X228')->setValue(number_format($getPurchaseContractCreate->width_3));
        $worksheet->getCell('AG228')->setValue(number_format($getPurchaseContractCreate->length_of_roadway_3));
        // ------------------------------------------------------------------

        $worksheet->getCell('G230')->setValue($getPurchaseContractCreate->designated_date);
        $worksheet->getCell('Q230')->setValue($getPurchaseContractCreate->number . '号');

        if($getPurchaseContractCreate->setback != null) {
            $worksheet->getCell($getPurchaseContractCreate->setback == 1 ? 'D232' : 'G232')->setValue('■');
        }

        $worksheet->getCell('Y232')->setValue(number_format($getPurchaseContractCreate->setback_area) . '㎡');

        if($getPurchaseContractCreate->restricted_ordinance != null) {
            $worksheet->getCell($getPurchaseContractCreate->restricted_ordinance == 1 ? 'P234':'S234')->setValue('■');
        }

        $worksheet->getCell('Z234')->setValue($getPurchaseContractCreate->restricted_ordinance_text);
        $worksheet->getCell('V235')->setValue(number_format($getPurchaseContractCreate->alley_part_length) . 'm');
        $worksheet->getCell('AJ235')->setValue(number_format($getPurchaseContractCreate->alley_part_width) . 'm');

        $road_type_row = [];
        $road_type_row[1] = 238;
        $road_type_row[2] = 239;
        $road_type_row[3] = 241;
        $road_type_row[4] = 242;
        $road_type_row[5] = 244;
        $road_type_row[6] = 246;
        $road_type_row[7] = 247;
        $road_type_row[8] = 248;
        if($getPurchaseContractCreate->create_site_and_road_type_0 != null && $getPurchaseContractCreate->create_site_and_road_type_0 != 0) {
            $worksheet->getCell('C'.$road_type_row[$getPurchaseContractCreate->create_site_and_road_type_0])->setValue('■');
        }
        if($getPurchaseContractCreate->create_site_and_road_type_1 != null && $getPurchaseContractCreate->create_site_and_road_type_1 != 0) {
            $worksheet->getCell('C'.$road_type_row[$getPurchaseContractCreate->create_site_and_road_type_0])->setValue('■');
        }
        if($getPurchaseContractCreate->create_site_and_road_type_2 != null && $getPurchaseContractCreate->create_site_and_road_type_2 != 0) {
            $worksheet->getCell('C'.$road_type_row[$getPurchaseContractCreate->create_site_and_road_type_0])->setValue('■');
        }
        if($getPurchaseContractCreate->create_site_and_road_type_3 != null && $getPurchaseContractCreate->create_site_and_road_type_3 != 0) {
            $worksheet->getCell('C'.$road_type_row[$getPurchaseContractCreate->create_site_and_road_type_0])->setValue('■');
        }

        if(!empty($getPurchaseContractCreate->road_type_text)){
            $worksheet->getCell('E247')->setValue('('. $getPurchaseContractCreate->road_type_text .')');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for (3) 都市計画法、建築基準法以外の法令に基づく制限
        // ------------------------------------------------------------------
        $purchaseSale = PjPurchaseSale::where('project_id', $projectId)->first();

        if($purchaseSale->project_urbanization_area != null) {
            $worksheet->getCell($purchaseSale->project_urbanization_area == 3 ? 'O261' : 'R261')->setValue('■');
        }
        if($purchaseSale->project_urbanization_area_status != null) {
            $worksheet->getCell($purchaseSale->project_urbanization_area_status == 1 ? 'K262':'P262')->setValue('■');
        }

        if($purchaseSale->project_urbanization_area_sub != null) {
            if($purchaseSale->project_urbanization_area_sub == 1){
                $worksheet->getCell('W262')->setValue('■');
            }else if($purchaseSale->project_urbanization_area_sub == 2){
                $worksheet->getCell('AB262')->setValue('■');
            }else{
                $worksheet->getCell('AF262')->setValue('■');
            }
        }

        $worksheet->getCell('O263')->setValue('令和'. $getPurchaseContractCreate->provisional_land_change_text. '年'.
                                                $getPurchaseContractCreate->provisional_land_change_text . '月'.
                                                $getPurchaseContractCreate->provisional_land_change_text . '日');

        if($getPurchaseContractCreate->provisional_land_change != null) {
            $worksheet->getCell($getPurchaseContractCreate->provisional_land_change == 1 ? 'I263' : 'L263')->setValue('■');
        }
        if($getPurchaseContractCreate->provisional_land_change_map != null) {
            $worksheet->getCell($getPurchaseContractCreate->provisional_land_change_map == 1 ? 'AR265' : 'AU265')->setValue('■');
        }
        if($getPurchaseContractCreate->architectural_restrictions != null) {
            $worksheet->getCell($getPurchaseContractCreate->architectural_restrictions == 1 ? 'AR268' : 'AR268')->setValue('■');
        }

        if($getPurchaseContractCreate->liquidation != null) {
            if($getPurchaseContractCreate->liquidation == 1){
                $worksheet->getCell('M266')->setValue('■');
                $worksheet->getCell('AD266')->setValue('■');
            }else if($getPurchaseContractCreate->liquidation == 2){
                $worksheet->getCell('M266')->setValue('■');
                $worksheet->getCell('AH266')->setValue('■');
            }else if($getPurchaseContractCreate->liquidation == 3){
                $worksheet->getCell('P266')->setValue('■');
            }else{
                $worksheet->getCell('S266')->setValue('■');
            }
        }

        $worksheet->getCell('R267')->setValue($getPurchaseContractCreate->liquidation_money_text);

        if($getPurchaseContractCreate->liquidation_money != null) {
            $worksheet->getCell($getPurchaseContractCreate->liquidation_money == 1 ? 'J267' : 'N267')->setValue('■');
        }

        if($getPurchaseContractCreate->levy != null) {
            if($getPurchaseContractCreate->levy == 1){
                $worksheet->getCell('M268')->setValue('■');
                $worksheet->getCell('AD268')->setValue('■');
            }else if($getPurchaseContractCreate->levy == 2){
                $worksheet->getCell('M268')->setValue('■');
                $worksheet->getCell('AH268')->setValue('■');
            }else if($getPurchaseContractCreate->levy == 3){
                $worksheet->getCell('P268')->setValue('■');
            }else{
                $worksheet->getCell('S268')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->levy_money != null) {
            $worksheet->getCell($getPurchaseContractCreate->levy_money == 1 ? 'J269' : 'N269')->setValue('■');
        }

        $worksheet->getCell('R269')->setValue($getPurchaseContractCreate->levy_money_text);
        $worksheet->getCell('B271')->setValue($getPurchaseContractCreate->other_legal_restrictions_text_a);

        if
        (
            $getPurchaseContractCreate->restricted_law_9 == 1 ||
            $getPurchaseContractCreate->restricted_law_16 == 1 ||
            $getPurchaseContractCreate->restricted_law_21 == 1 ||
            $getPurchaseContractCreate->restricted_law_33 == 1 ||
            $getPurchaseContractCreate->restricted_law_35 == 1 ||
            $getPurchaseContractCreate->restricted_law_36 == 1 ||
            $getPurchaseContractCreate->restricted_law_42 == 1 ||
            $getPurchaseContractCreate->restricted_law_46 == 1 ||
            $getPurchaseContractCreate->restricted_law_47 == 1 ||
            $getPurchaseContractCreate->restricted_law_49 == 1 ||
            $getPurchaseContractCreate->restricted_law_50 == 1 ||
            $getPurchaseContractCreate->restricted_law_51 == 1 ||
            $getPurchaseContractCreate->restricted_law_54 == 1 ||
            $getPurchaseContractCreate->restricted_law_55 == 1
        ) {
            // do nothing
        } else {
            for($i = 274; $i <= 288; $i++) {
                $worksheet->getRowDimension($i)->setVisible(false);
            }
        }

        // Output for 制限対象法律


        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 3.私道に関する負担等に関する事項
        // ------------------------------------------------------------------
        $worksheet->getCell($getPurchaseContractCreate->road_private_burden_contract == 1 ? 'Q294' : 'T294')->setValue('■');
        $worksheet->getCell('L295')->setValue(number_format($getPurchaseContractCreate->road_private_burden_area_contract) . '㎡');
        $worksheet->getCell('AJ295')->setValue($getPurchaseContractCreate->road_private_burden_amount_contract . '円');
        $worksheet->getCell('O296')->setValue($getPurchaseContractCreate->road_private_burden_share_denom_contract . '分の'.
                                                $getPurchaseContractCreate->road_private_burden_share_number_contract);

        $worksheet->getCell('Z297')->setValue(number_format($getPurchaseContractCreate->road_setback_area_size_contract) . '㎡');
        $worksheet->getCell('B299')->setValue($getPurchaseContractCreate->remarks_contract);

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 4.飲用水・電気・ガスの供給施設および排水施設の整備状況
        // ------------------------------------------------------------------
        if($getPurchaseContractCreate->potable_water_facilities != null) {
            $worksheet->getCell($getPurchaseContractCreate->potable_water_facilities == 1 ? 'C305' : (
                $getPurchaseContractCreate->potable_water_facilities == 2 ? 'C306' : 'C307'))->setValue('■');
        }
        if($getPurchaseContractCreate->electrical_retail_company != null) {
            $worksheet->getCell($getPurchaseContractCreate->electrical_retail_company == 1 ? 'C311' : 'C312')->setValue('■');
        }
        if($getPurchaseContractCreate->gas_facilities != null && $getPurchaseContractCreate->potable_water_facilities != null) {
            $worksheet->getCell($getPurchaseContractCreate->gas_facilities == 1 ? 'C315' : (
                            $getPurchaseContractCreate->potable_water_facilities == 2 ? 'C316' : 'C317'))->setValue('■');
        }

        if($getPurchaseContractCreate->sewage_facilities != null) {
            if($getPurchaseContractCreate->sewage_facilities == 1){
                $worksheet->getCell('C320')->setValue('■');
            }else if($getPurchaseContractCreate->sewage_facilities == 2){
                $worksheet->getCell('C321')->setValue('■');
            }else if($getPurchaseContractCreate->sewage_facilities == 3){
                $worksheet->getCell('C322')->setValue('■');
            }else if($getPurchaseContractCreate->sewage_facilities == 4){
                $worksheet->getCell('C323')->setValue('■');
            }else{
                $worksheet->getCell('C324')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->miscellaneous_water_facilities != null) {
            if($getPurchaseContractCreate->miscellaneous_water_facilities == 1){
                $worksheet->getCell('C326')->setValue('■');
            }else if($getPurchaseContractCreate->miscellaneous_water_facilities == 2){
                $worksheet->getCell('C327')->setValue('■');
            }else if($getPurchaseContractCreate->miscellaneous_water_facilities == 3){
                $worksheet->getCell('C328')->setValue('■');
            }else if($getPurchaseContractCreate->miscellaneous_water_facilities == 4){
                $worksheet->getCell('C329')->setValue('■');
            }else{
                $worksheet->getCell('C330')->setValue('■');
            }
        }

        if($getPurchaseContractCreate->rain_water_facilities != null) {
            $worksheet->getCell($getPurchaseContractCreate->rain_water_facilities == 1 ? 'C332' : (
                                $getPurchaseContractCreate->rain_water_facilities == 2 ? 'C333' : 'C334'))->setValue('■');
        }
        if($getPurchaseContractCreate->potable_water_front_road_piping != null) {
            $worksheet->getCell($getPurchaseContractCreate->potable_water_front_road_piping == 1 ? 'R305' : 'U305')->setValue('■');
        }

        if($getPurchaseContractCreate->potable_water_front_road_piping == 1){
            $worksheet->getCell('R306')->setValue('1' . ' mm');
        }else{
            $worksheet->getCell('R306')->setValue('');
        }

        if($getPurchaseContractCreate->potable_water_on_site_service_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->potable_water_on_site_service_pipe == 1 ? 'R307' : 'U307')->setValue('■');
        }
        $worksheet->getCell('R308')->setValue(number_format($getPurchaseContractCreate->potable_water_on_site_service_pipe_text) . ' mm');
        if($getPurchaseContractCreate->potable_water_private_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->potable_water_private_pipe == 1 ? 'R309' : 'U309')->setValue('■');
        }
        if($getPurchaseContractCreate->electrical_retail_company == 2){
            $worksheet->getCell('L310')->setValue('■');
        }

        $worksheet->getCell('T312')->setValue($getPurchaseContractCreate->electrical_retail_company_name);
        $worksheet->getCell('T313')->setValue($getPurchaseContractCreate->electrical_retail_company_address);
        $worksheet->getCell('T314')->setValue($getPurchaseContractCreate->electrical_retail_company_contact);

        if($getPurchaseContractCreate->gas_front_road_piping != null) {
            $worksheet->getCell($getPurchaseContractCreate->gas_front_road_piping == 1 ? 'R315' : 'U315')->setValue('■');
        }
        if($getPurchaseContractCreate->gas_front_road_piping == 1){
            $worksheet->getCell('R316')->setValue('1' .' mm');
        }else{
            $worksheet->getCell('R316')->setValue('');
        }
        if($getPurchaseContractCreate->gas_on_site_service_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->gas_on_site_service_pipe == 1 ? 'R317' : 'U317')->setValue('■');
        }
        $worksheet->getCell('R318')->setValue($getPurchaseContractCreate->gas_on_site_service_pipe_text . ' mm');
        if($getPurchaseContractCreate->gas_private_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->gas_private_pipe == 1 ? 'R319' : 'U319')->setValue('■');
        }

        if($getPurchaseContractCreate->sewage_front_road_piping != null) {
            $worksheet->getCell($getPurchaseContractCreate->sewage_front_road_piping == 1 ? 'R320' : 'U320')->setValue('■');
        }
        if($getPurchaseContractCreate->sewage_front_road_piping == 1){
            $worksheet->getCell('R321')->setValue('1' .' mm');
        }else{
            $worksheet->getCell('R321')->setValue('');
        }

        if($getPurchaseContractCreate->sewage_on_site_service_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->sewage_on_site_service_pipe == 1 ? 'R322' : 'U322')->setValue('■');
        }
        $worksheet->getCell('R323')->setValue(number_format($getPurchaseContractCreate->sewage_on_site_service_pipe_text) .' mm');

        if($getPurchaseContractCreate->septic_tank_installation != null) {
            if($getPurchaseContractCreate->septic_tank_installation == 1){
                $worksheet->getCell('R324')->setValue('■');
            }else if($getPurchaseContractCreate->septic_tank_installation == 2){
                $worksheet->getCell('V324')->setValue('■');
            }else{
                $worksheet->getCell('Y324')->setValue('■');
            }
        }
        if($getPurchaseContractCreate->miscellaneous_water_front_road_piping != null) {
            $worksheet->getCell($getPurchaseContractCreate->miscellaneous_water_front_road_piping == 1 ? 'R326' : 'U326')->setValue('■');
        }
        if($getPurchaseContractCreate->miscellaneous_water_front_road_piping == 1){
            $worksheet->getCell('R327')->setValue('1 mm');
        }else{
            $worksheet->getCell('R327')->setValue('');
        }
        if($getPurchaseContractCreate->miscellaneous_water_on_site_service_pipe != null) {
            $worksheet->getCell($getPurchaseContractCreate->miscellaneous_water_on_site_service_pipe == 1 ? 'R328' : 'U328')->setValue('■');
        }
        $worksheet->getCell('R329')->setValue($getPurchaseContractCreate->miscellaneous_water_on_site_service_pipe_text);
        if($getPurchaseContractCreate->rain_water_exclusion != null) {
            $worksheet->getCell($getPurchaseContractCreate->rain_water_exclusion == 1 ? 'L333' : 'Q333')->setValue('■');
        }
        $worksheet->getCell($getPurchaseContractCreate->potable_water_schedule == 1 ? 'AM305' : 'AJ305')->setValue('■');
        $worksheet->getCell('AK306')->setValue($getPurchaseContractCreate->potable_water_schedule_year);
        $worksheet->getCell('AP306')->setValue($getPurchaseContractCreate->potable_water_schedule_month);
        $worksheet->getCell('AM308')->setValue($getPurchaseContractCreate->potable_water_participation_fee);

        $worksheet->getCell($getPurchaseContractCreate->electrical_schedule == 1 ? 'AM310' : 'AJ310')->setValue('■');
        $worksheet->getCell('AK311')->setValue($getPurchaseContractCreate->electrical_schedule_year);
        $worksheet->getCell('AP311')->setValue($getPurchaseContractCreate->electrical_schedule_month);
        $worksheet->getCell('AM313')->setValue($getPurchaseContractCreate->electrical_charge);

        $worksheet->getCell($getPurchaseContractCreate->gas_schedule == 1 ? 'AM315' : 'AJ315')->setValue('■');
        $worksheet->getCell('AK316')->setValue($getPurchaseContractCreate->gas_schedule_year);
        $worksheet->getCell('AP316')->setValue($getPurchaseContractCreate->gas_schedule_month);
        $worksheet->getCell('AM318')->setValue($getPurchaseContractCreate->sewage_charge);

        $worksheet->getCell($getPurchaseContractCreate->sewage_schedule == 1 ? 'AM320' : 'AJ320')->setValue('■');
        $worksheet->getCell('AK321')->setValue($getPurchaseContractCreate->sewage_schedule_year);
        $worksheet->getCell('AP321')->setValue($getPurchaseContractCreate->sewage_schedule_month);
        $worksheet->getCell('AM323')->setValue($getPurchaseContractCreate->gas_charge);

        $worksheet->getCell($getPurchaseContractCreate->miscellaneous_water_schedule == 1 ? 'AM326' : 'AJ326')->setValue('■');
        $worksheet->getCell('AK327')->setValue($getPurchaseContractCreate->miscellaneous_water_schedule_year);
        $worksheet->getCell('AP327')->setValue($getPurchaseContractCreate->miscellaneous_water_schedule_month);
        $worksheet->getCell('AM329')->setValue($getPurchaseContractCreate->miscellaneous_water_charge);

        $worksheet->getCell($getPurchaseContractCreate->rain_water_schedule == 1 ? 'AM332' : 'AJ332')->setValue('■');
        $worksheet->getCell('AK333')->setValue($getPurchaseContractCreate->rain_water_schedule_year);
        $worksheet->getCell('AP333')->setValue($getPurchaseContractCreate->rain_water_schedule_month);
        $worksheet->getCell('AM335')->setValue($getPurchaseContractCreate->rain_water_charge);

        if(!empty($getPurchaseContractCreate->water_supply_and_drainage_remarks)){
            $worksheet->getCell('B342')->setValue($getPurchaseContractCreate->water_supply_and_drainage_remarks);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 5. 宅地造成工事完了時における形状・構造等
        // ------------------------------------------------------------------
        if(!empty($getPurchaseContractCreate->shape_structure)){
            $worksheet->getCell($getPurchaseContractCreate->shape_structure == 1 ? 'B347' : 'L347')->setValue('■');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 7. 当該宅地が土砂災害警戒区域内か否か
        // ------------------------------------------------------------------
        if(!empty($getPurchaseContractCreate->earth_and_sand_vigilance)){
            $worksheet->getCell($getPurchaseContractCreate->earth_and_sand_vigilance == 1 ? 'V353' : 'Y353')->setValue('■');
        }
        if(!empty($getPurchaseContractCreate->earth_and_sand_special_vigilance)){
            $worksheet->getCell($getPurchaseContractCreate->earth_and_sand_special_vigilance == 1 ? 'V354' : 'Y354')->setValue('■');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 9. 建物状況調査の結果の概要（既存の住宅のとき）　（■ 該当する ・ □ 該当しないので説明を省略します）
        // ------------------------------------------------------------------
        //dd($purchaseContract->contract_building_kind);
        if($purchaseContract->contract_building_kind != 1){
            for($i = 0; $i < 7; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(360 + $i)->setVisible(false);
            }
        }

        if($purchaseContract->contract_building_kind == 1){
            $worksheet->getCell('M268')->setValue('9. 建物状況調査の結果の概要（既存の住宅のとき）　（■ 該当する ・ □ 該当しないので説明を省略します）');
        }else{
            $worksheet->getCell('M268')->setValue('9. 建物状況調査の結果の概要（既存の住宅のとき）　（□ 該当する ・ ■ 該当しないので説明を省略します）');
        }
        $worksheet->getCell($getPurchaseContractCreate->survey_status_implementation == 1 ? 'L362' : 'O362')->setValue('■');
        if(!empty($getPurchaseContractCreate->survey_status_results)){
            $worksheet->getCell('L364')->setValue($getPurchaseContractCreate->survey_status_results);
        }else{
            $worksheet->getCell('L364')->setValue('');
        }

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 10.建物の建築及び維持保全の状況
        // ------------------------------------------------------------------
        if($purchaseContract->contract_building_kind != 1){
            for($i = 0; $i < 26; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(367 + $i)->setVisible(false);
            }
        }

        if($getPurchaseContractCreate->maintenance_confirmed_certificat != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_confirmed_certificat == 1 ? 'AL371' : 'AO371')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_inspection_certificate != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_inspection_certificate == 1 ? 'AL372' : 'AO372')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_renovation != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_renovation == 1 ? 'AL373' : 'AR373')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_renovation_confirmed_certificat != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_renovation_confirmed_certificat == 1 ? 'AL374' : 'AO374')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_renovation_inspection_certificate != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_renovation_inspection_certificate == 1 ? 'AL375' : 'AO375')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_building_situation_survey != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_building_situation_survey == 1 ? 'AL376' : 'AR376')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_building_situation_survey_report != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_building_situation_survey_report == 1 ? 'AL377' : 'AO377')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_building_housing_performance_evaluation != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_building_housing_performance_evaluation == 1 ? 'AL378' : 'AR378')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_building_housing_performance_evaluation_report != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_building_housing_performance_evaluation_report == 1 ? 'AL379' : 'AO379')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_regular_survey_report != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_regular_survey_report == 1 ? 'AL380' : 'AR380')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_periodic_survey_report_a != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_periodic_survey_report_a == 1 ? 'AL381' : 'AO381')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_periodic_survey_report_b != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_periodic_survey_report_b == 1 ? 'AL382' : 'AO382')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_periodic_survey_report_c != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_periodic_survey_report_c == 1 ? 'AL383' : 'AO383')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_periodic_survey_report_d != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_periodic_survey_report_d == 1 ? 'AL384' : 'AO384')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_construction_started_before != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_construction_started_before == 1 ? 'AL385' : 'AR385')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_construction_started_before_seismic_standard_certification != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_construction_started_before_seismic_standard_certification == 1 ? 'AL386' : 'AO386')->setValue('■');
        }
        if($getPurchaseContractCreate->maintenance_construction_started_before_sub != null) {
            $worksheet->getCell($getPurchaseContractCreate->maintenance_construction_started_before_sub == 1 ? 'AL387' : 'AO387')->setValue('■');
        }
        $worksheet->getCell('G387')->setValue($getPurchaseContractCreate->maintenance_construction_started_before_sub_text);
        $worksheet->getCell('B389')->setValue($getPurchaseContractCreate->maintenance_remarks);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 11. 住宅性能評価を受けた新築住宅である場合
        // ------------------------------------------------------------------
        if($purchaseContract->contract_building_kind != 1){
            for($i = 0; $i < 5; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(393 + $i)->setVisible(false);
            }
        }

        if($purchaseContract->contract_building_kind != 1) {
            if($getPurchaseContractCreate->performance_evaluation == 1) {
                $worksheet->getCell('AJ394')->setValue('■');
            } else
            if($getPurchaseContractCreate->performance_evaluation == 2) {
                $worksheet->getCell('L394')->setValue('■');
                $worksheet->getCell('O394')->setValue('■');
            } else
            if($getPurchaseContractCreate->performance_evaluation == 3) {
                $worksheet->getCell('L394')->setValue('■');
                $worksheet->getCell('O394')->setValue('■');
                $worksheet->getCell('Y394')->setValue('■');
            }
        }

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 12. 建物についての石綿使用調査結果の記録に関する事項
        // ------------------------------------------------------------------
        if($purchaseContract->contract_building_kind != 1){
            for($i = 0; $i < 14; $i++){
                $spreadsheet->getActiveSheet()->getRowDimension(398 + $i)->setVisible(false);
            }
        }

        if($getPurchaseContractCreate->use_asbestos_Reference != null){
            if($getPurchaseContractCreate->use_asbestos_Reference == 1){
                $worksheet->getCell('L399')->setValue('■');
            }else if($getPurchaseContractCreate->use_asbestos_Reference == 2){
                $worksheet->getCell('Q399')->setValue('■');
            }else if($getPurchaseContractCreate->use_asbestos_Reference == 3){
                $worksheet->getCell('AG399')->setValue('■');
            }else{
                $worksheet->getCell('L400')->setValue('■');
            }
        }

        $worksheet->getCell('W399')->setValue($getPurchaseContractCreate->use_asbestos_Reference_text);
        $worksheet->getCell('AM399')->setValue($getPurchaseContractCreate->use_asbestos_Reference_text);
        $worksheet->getCell('AM400')->setValue($getPurchaseContractCreate->use_asbestos_Reference_text);

        $worksheet->getCell($getPurchaseContractCreate->use_asbestos_record == 1 ? 'L401' : 'L403')->setValue('■');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for 13. 建物の耐震診断に関する事項
        // ------------------------------------------------------------------
        /*if($purchaseContract->contract_building_kind == 1){
            $worksheet->getCell('B415')->setValue('■');
        }else{
            $worksheet->getCell('B416')->setValue('■');
        }*/

        if($getPurchaseContractCreate->seismic_diagnosis_presence == 2){
            $worksheet->getCell('B416')->setValue('■');
        }else{
            $worksheet->getCell('B415')->setValue('■');
            $worksheet->getCell('L414')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('M415')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('M416')->getStyle()->getFont()->setStrikethrough(true);
            $worksheet->getCell('M418')->getStyle()->getFont()->setStrikethrough(true);
        }

        if($getPurchaseContractCreate->seismic_standard_certification == 1){
            $worksheet->getCell('L415')->setValue('■');
        }
        if($getPurchaseContractCreate->seismic_diagnosis_performance_evaluation == 1){
            $worksheet->getCell('L416')->setValue('■');
        }
        if($getPurchaseContractCreate->seismic_diagnosis_result == 1){
            $worksheet->getCell('L418')->setValue('■');
        }

        if(!empty($getPurchaseContractCreate->seismic_diagnosis_remarks)){
            $worksheet->getCell('B420')->setValue($getPurchaseContractCreate->seismic_diagnosis_remarks);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for A 不動産の表示
        // ------------------------------------------------------------------
        $getPropertiesId = PjProperty::where('project_id', $projectId)->first();
        $masterRegion = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();

        // $getPurchaseContractCreate = (object) array(
        //     'c_article4_contract' => 2,
        //     'c_article5_fixed_survey_contract' => 1,
        //     'c_article5_fixed_survey_options_contractand' => 1,
        //     'c_article5_fixed_survey_options_contract' => 1,
        //     'c_article5_land_surveying' => 1,
        //     'c_article5_date_contract' => '2012-02-16 00:00:00',
        //     'project_buy_building_address' => 'Tokyo',
        //     'c_article5_creator_contract' => 'Robert Jr',
        // );
        // ------------------------------------------------------------------
        // Report output for 2.建物
        // ------------------------------------------------------------------
        $retriveDataPjLotBuildingA = PjLotBuildingA::where('pj_property_id', $getPropertiesId->id)->get();
        $retriveDataPjLotBuildingA = $retriveDataPjLotBuildingA->groupBy(function ($item, $key) {
                                          return $item['parcel_city'].$item['parcel_city_extra'].
                                                 $item['parcel_town'].$item['building_number_first'].
                                                 $item['building_number_second'].$item['building_number_third'];
                                      });

        $purchaseContract = PjPurchaseContract::where('pj_purchase_target_id', $targetId)->first();

        $buildingCounterLoop = 0;
        $buildingRowCounter = 0;
        foreach($retriveDataPjLotBuildingA as $buildingGrouped){
            $building = $buildingGrouped[0];

            if($buildingCounterLoop >= 1){
                for($i = 0; $i < 5; $i++){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore(85+($buildingRowCounter));
                }
                $arrTittle = ['所 在', '住居表示', '種類', '床面積', '建築時期'];
                for($i = 0; $i <= 4; $i++){
                    $spreadsheet->getActiveSheet()->mergeCells('B'.(85 + ($buildingRowCounter + $i)).':'.'F'.(85 + ($buildingRowCounter + $i))); // Merge cell
                    $worksheet->getCell('B'.(85 + ($buildingRowCounter + $i)))->setValue($arrTittle[$i]);
                    $spreadsheet->getActiveSheet()->getStyle('B'.(85 + ($buildingRowCounter + $i)).':'.'F'.(85 + ($buildingRowCounter + $i)))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                for($i = 0; $i < 3; $i++){
                    $spreadsheet->getActiveSheet()->mergeCells('G'.(85 + ($buildingRowCounter + $i)).':'.'T'.(85 + ($buildingRowCounter + $i))); // Merge cell
                    $spreadsheet->getActiveSheet()->getStyle('G'.(85 + ($buildingRowCounter + $i)).':'.'T'.(85 + ($buildingRowCounter + $i)))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }

                $arrTittle2 = ['家屋番号', '附属建物', '構造'];
                for($i = 0; $i <= 2; $i++){
                    $spreadsheet->getActiveSheet()->mergeCells('U'.(85 + ($buildingRowCounter + $i)).':'.'Y'.(85 + ($buildingRowCounter + $i))); // Merge cell
                    $worksheet->getCell('U'.(85 + ($buildingRowCounter + $i)))->setValue($arrTittle2[$i]);
                    $spreadsheet->getActiveSheet()->getStyle('U'.(85 + ($buildingRowCounter + $i)).':'.'Y'.(85 + ($buildingRowCounter + $i)))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                $spreadsheet->getActiveSheet()->mergeCells('Z'.(85 + $buildingRowCounter).':'.'AW'.(85 + $buildingRowCounter)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AC'.(86 + $buildingRowCounter).':'.'AE'.(86 + $buildingRowCounter)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('Z'.(87 + $buildingRowCounter).':'.'AW'.(87 + $buildingRowCounter)); // Merge cell

                $worksheet->getCell('Z'.(86 + $buildingRowCounter))->setValue('□');
                $worksheet->getCell('AA'.(86 + $buildingRowCounter))->setValue('有');
                $worksheet->getCell('AB'.(86 + $buildingRowCounter))->setValue('(');
                $worksheet->getCell('AF'.(86 + $buildingRowCounter))->setValue(')');
                $worksheet->getCell('AJ'.(86 + $buildingRowCounter))->setValue('・');
                $worksheet->getCell('AK'.(86 + $buildingRowCounter))->setValue('□');
                $worksheet->getCell('AL'.(86 + $buildingRowCounter))->setValue('無');

                $spreadsheet->getActiveSheet()->getStyle('Z'.(85 + $buildingRowCounter).':'.'AW'.(85 + $buildingRowCounter))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('Z'.(86 + $buildingRowCounter).':'.'AW'.(86 + $buildingRowCounter))
                                ->getBorders()
                                ->getBottom()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('Z'.(87 + $buildingRowCounter).':'.'AW'.(87 + $buildingRowCounter))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $spreadsheet->getActiveSheet()->mergeCells('G'.(88 + $buildingRowCounter).':'.'N'.(88 + $buildingRowCounter)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('O'.(88 + $buildingRowCounter).':'.'P'.(88 + $buildingRowCounter)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('Q'.(88 + $buildingRowCounter).':'.'AW'.(88 + $buildingRowCounter)); // Merge cell
                $spreadsheet->getActiveSheet()->getStyle('G'.(88 + $buildingRowCounter).':'.'AW'.(88 + $buildingRowCounter))
                                ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $worksheet->getCell('O'.(88 + $buildingRowCounter))->setValue('合計');

                $spreadsheet->getActiveSheet()->mergeCells('H'.(89 + $buildingRowCounter).':'.'N'.(89 + $buildingRowCounter)); // Merge cell

                $worksheet->getCell('G'.(89 + $buildingRowCounter))->setValue('□');
                $worksheet->getCell('P'.(89 + $buildingRowCounter))->setValue('・');
                $worksheet->getCell('R'.(89 + $buildingRowCounter))->setValue('□');
                $worksheet->getCell('S'.(89 + $buildingRowCounter))->setValue('不詳');

                $buildingRowCounter+=5;
            }
            $parcelCity = '';
            $parcelCityExtra = '';
            if($building->parcel_city == -1){
                $parcelCity = 'その他';
                $parcelCityExtra = $building->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $building->parcel_city){
                        $parcelCity = $region->name;
                    }
                }
            }
            $parcelTargetBuilding = $parcelCity. $building->parcel_city_extra. $building->parcel_town;
            $parcelNumber = $building->building_number_first. '番'. $building->building_number_second. 'の'. $building->building_number_third;
            $parcelTargetWithBuildingAddress = $parcelCity. $building->parcel_city_extra. $building->parcel_town. $getPurchaseContractCreate->project_buy_building_address;

            $worksheet->getCell('G'.(80 + $buildingRowCounter))->setValue($parcelTargetBuilding);
            $worksheet->getCell('Z'.(80 + $buildingRowCounter))->setValue($parcelNumber)->getStyle()->getAlignment()->setHorizontal('left');

            $worksheet->getCell('G'.(81 + $buildingRowCounter))->setValue($parcelTargetWithBuildingAddress);

            if($building->building_attached == 1){
                $worksheet->getCell('Z'.(81 + $buildingRowCounter))->setValue('■');
                if($building->building_attached_select == 1){
                    $worksheet->getCell('AC'.(81 + $buildingRowCounter))->setValue('車庫');
                }else if($building->building_attached_select == 2){
                    $worksheet->getCell('AC'.(81 + $buildingRowCounter))->setValue('倉庫');
                }else if($building->building_attached_select == 3){
                    $worksheet->getCell('AC'.(81 + $buildingRowCounter))->setValue('納屋');
                }else if($building->building_attached_select == 4){
                    $worksheet->getCell('AC'.(81 + $buildingRowCounter))->setValue('物置');
                }else{
                    $worksheet->getCell('AC'.(81 + $buildingRowCounter))->setValue('');
                }
            }else{
                $worksheet->getCell('AK'.(81 + $buildingRowCounter))->setValue('■');
            }

            if($building->building_usetype != null && $building->building_usetype != 0) {
                $build_usetype = MasterValue::find($building->building_usetype);
                $worksheet->getCell('G'.(82 + $buildingRowCounter))->setValue($build_usetype->value);
            }
            if($building->building_structure != null && $building->building_structure != 0) {
                $build_structure = MasterValue::find($building->building_structure);
                $worksheet->getCell('Z'.(82 + $buildingRowCounter))->setValue($build_structure->value)->getStyle()->getAlignment()->setHorizontal('left');
            }

            $getFloorSize = PjBuildingFloorSize::where('pj_lot_building_a_id', $building->id)->get();

            $sumFloorSize = 0;
            $allFloorSize = '';
            $floorSizeCounter = 0;
            foreach($getFloorSize as $floorSize){
                $sumFloorSize += $floorSize->floor_size;
                if($floorSizeCounter >= 1){
                    $allFloorSize .= '・'. number_format($floorSize->floor_size) . ' ㎡';
                }else{
                    $allFloorSize = number_format($floorSize->floor_size) . ' ㎡';
                }

                $floorSizeCounter++;

                $worksheet->getCell('Q'.(83 + $buildingRowCounter))->setValue($allFloorSize)->getStyle()->getAlignment()->setHorizontal('left');;
            }
            $worksheet->getCell('G'.(83 + $buildingRowCounter))->setValue($building->building_floor_count. ' 階 '. number_format($sumFloorSize). ' ㎡ ');
            $worksheet->getCell('H'.(84 + $buildingRowCounter))->setValue(PurchaseContractCreateImportReport::convert_to_nengou($building->building_date_year). '年' .$building->building_date_month. '月' .$building->building_date_day. '日');
            $worksheet->getCell('G'.(84 + $buildingRowCounter))->setValue('■');

            $buildingCounterLoop++;
        }
        if($purchaseContract->contract_building_unregistered == 1){
            $worksheet->getCell('G'.(90 + $buildingRowCounter))->setValue('■');
        }else{
            $worksheet->getCell('K'.(84 + $buildingRowCounter))->setValue('■');
        }

        for($i = 0; $i <= 4; $i++){
            $spreadsheet->getActiveSheet()->getRowDimension(85 + $buildingRowCounter + $i)->setVisible(false);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        //Report output for 1.宅地・道路
        // ------------------------------------------------------------------
        $retriveDataPjLotResidentialA = PjLotResidentialA::where('pj_property_id', $getPropertiesId->id)->get();
        $retriveDataPjLotRoadA = PjLotRoadA::where('pj_property_id', $getPropertiesId->id)->get();

        $retriveDataPjLotResidentialA = $retriveDataPjLotResidentialA->groupBy(function ($item, $key) {
                                            return $item['parcel_city'].$item['parcel_city_extra'].
                                                   $item['parcel_town'].$item['parcel_number_first'].
                                                   $item['parcel_number_second'];
                                        });
        $retriveDataPjLotRoadA = $retriveDataPjLotRoadA->groupBy(function ($item, $key) {
                                    return $item['parcel_city'].$item['parcel_city_extra'].
                                           $item['parcel_town'].$item['parcel_number_first'].
                                           $item['parcel_number_second'];
                                 });

        $totalParcelSize = 0;

        $residentialCounterLoop = 0;
        foreach($retriveDataPjLotResidentialA as $residentialGrouped){
            $residential = $residentialGrouped[0];

            if($residentialCounterLoop >= 1){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(58+$residentialCounterLoop);
                $spreadsheet->getActiveSheet()->mergeCells('C'.(58+$residentialCounterLoop).':'.'AC'.(58+$residentialCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AD'.(58+$residentialCounterLoop).':'.'AF'.(58+$residentialCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AG'.(58+$residentialCounterLoop).':'.'AI'.(58+$residentialCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AJ'.(58+$residentialCounterLoop).':'.'AN'.(58+$residentialCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AO'.(58+$residentialCounterLoop).':'.'AS'.(58+$residentialCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AT'.(58+$residentialCounterLoop).':'.'AW'.(58+$residentialCounterLoop)); // Merge cell

                $worksheet->getCell('B'.(58+$residentialCounterLoop))->setValue(($residentialCounterLoop + 1).'.'); // Merge cell
            }
            $parcelCity = '';
            $parcelCityExtra = '';
            if($building->parcel_city == -1){
                $parcelCity = 'その他';
                $parcelCityExtra = $building->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $building->parcel_city){
                        $parcelCity = $region->name;
                    }
                }
            }
            $parcelTargetResidential = $parcelCity . $residential->parcel_city_extra . $residential->parcel_town;
            $parcelNumber = $residential->parcel_number_first . '番' . $residential->parcel_number_second;

            $worksheet->getCell('C'.(58 + $residentialCounterLoop))->setValue($parcelTargetResidential);
            $worksheet->getCell('AD'.(58 + $residentialCounterLoop))->setValue($parcelNumber)->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AG'.(58 + $residentialCounterLoop))->setValue('宅地')->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AJ'.(58 + $residentialCounterLoop))->setValue($residential->parcel_land_category)->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AO'.(58 + $residentialCounterLoop))->setValue(number_format($residential->parcel_size) . '㎡')->getStyle()->getAlignment()->setWrapText(true);
            $totalParcelSize += $residential->parcel_size;

            $getResidentialOwner = PjLotResidentialOwner::where('pj_lot_residential_a_id', $residential->id)->get();

            $owner = PurchaseContractCreateImportReport::ownerShare($getResidentialOwner);
            $worksheet->getCell('AT'.(58 + $residentialCounterLoop))->setValue($owner->share_denom . '分の' . $owner->share_number)->getStyle()->getAlignment()->setWrapText(true);

            $residentialCounterLoop++;
        }

        $roadCounterLoop = $residentialCounterLoop;
        foreach($retriveDataPjLotRoadA as $roadGrouped){
            $road = $roadGrouped[0];

            if($roadCounterLoop > 0){
                $spreadsheet->getActiveSheet()->insertNewRowBefore(58+$roadCounterLoop);
                $spreadsheet->getActiveSheet()->mergeCells('C'.(58+$roadCounterLoop).':'.'AC'.(58+$roadCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AD'.(58+$roadCounterLoop).':'.'AF'.(58+$roadCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AG'.(58+$roadCounterLoop).':'.'AI'.(58+$roadCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AJ'.(58+$roadCounterLoop).':'.'AN'.(58+$roadCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AO'.(58+$roadCounterLoop).':'.'AS'.(58+$roadCounterLoop)); // Merge cell
                $spreadsheet->getActiveSheet()->mergeCells('AT'.(58+$roadCounterLoop).':'.'AW'.(58+$roadCounterLoop)); // Merge cell

                $worksheet->getCell('B'.(58+$roadCounterLoop))->setValue(($roadCounterLoop + 1).'.'); // Merge cell
            }

            $parcelCity = '';
            $parcelCityExtra = '';
            if($building->parcel_city == -1){
                $parcelCity = 'その他';
                $parcelCityExtra = $building->parcel_city_extra;
            }else{
                foreach($masterRegion as $region){
                    if($region->id == $building->parcel_city){
                        $parcelCity = $region->name;
                    }
                }
            }

            $parcelTargetRoad = $parcelCity . $road->parcel_city_extra . $road->parcel_town;
            $parcelNumber = $road->parcel_number_first . '番' . $road->parcel_number_second;

            $worksheet->getCell('C'.(58 + $roadCounterLoop))->setValue($parcelTargetRoad);
            $worksheet->getCell('AD'.(58 + $roadCounterLoop))->setValue($parcelNumber)->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AG'.(58 + $roadCounterLoop))->setValue('道路')->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AJ'.(58 + $roadCounterLoop))->setValue($road->parcel_land_category)->getStyle()->getAlignment()->setWrapText(true);
            $worksheet->getCell('AO'.(58 + $roadCounterLoop))->setValue(number_format($road->parcel_size) . '㎡')->getStyle()->getAlignment()->setWrapText(true);
            $totalParcelSize += $road->parcel_size;

            $getRoadOwner = PjLotRoadOwner::where('pj_lot_road_a_id', $road->id)->get();

            $owner = PurchaseContractCreateImportReport::ownerShare($getRoadOwner);
            $worksheet->getCell('AT'.(58 + $roadCounterLoop))->setValue($owner->share_denom . '分の' . $owner->share_number)->getStyle()->getAlignment()->setWrapText(true);

            $roadCounterLoop++;
        }

        $worksheet->getCell('B'.(60 + ($roadCounterLoop-1)))->setValue('合計 （ '.(count($retriveDataPjLotResidentialA) + count($retriveDataPjLotRoadA)) . '筆 ）');
        $worksheet->getCell('Z'.(60 + ($roadCounterLoop-1)))->setValue(number_format($totalParcelSize) . '㎡')->getStyle()->getAlignment()->setHorizontal('center');

        if($getPurchaseContractCreate->c_article4_contract == 1){
            $worksheet->getCell('Z'.(61 + ($roadCounterLoop-1)))->setValue('■');
        }elseif($getPurchaseContractCreate->c_article4_contract == 2){
            $worksheet->getCell('AL'.(61 + ($roadCounterLoop-1)))->setValue('■');
        }elseif($getPurchaseContractCreate->c_article4_contract == 3){
            $worksheet->getCell('AT'.(61 + ($roadCounterLoop-1)))->setValue('■');
        }

        if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 1
            || $getPurchaseContractCreate->c_article5_fixed_survey_contract == 2
            || $getPurchaseContractCreate->c_article5_fixed_survey_options_contractand == 2
            && $getPurchaseContractCreate->c_article5_land_surveying == 1
            || $getPurchaseContractCreate->c_article5_fixed_survey_options_contractand == 3
            || $getPurchaseContractCreate->c_article5_fixed_survey_options_contractand == 4){
                $worksheet->getCell('C'.(62 + ($roadCounterLoop-1)))->setValue('■');
        }
        if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 3
            && $getPurchaseContractCreate->c_article5_fixed_survey_options_contract == 1){
                $worksheet->getCell('C'.(65 + ($roadCounterLoop-1)))->setValue('■');
        }
        if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 3
            && $getPurchaseContractCreate->c_article5_fixed_survey_options_contract == 2){
                $worksheet->getCell('C'.(68 + ($roadCounterLoop-1)))->setValue('■');
        }
        if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 3
            && $getPurchaseContractCreate->c_article5_fixed_survey_options_contract == 5){
                $worksheet->getCell('C'.(71 + ($roadCounterLoop-1)))->setValue('■');
        }

        if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 1){
            $worksheet->getCell('AJ'.(62 + ($roadCounterLoop-1)))->setValue('■');
        }
        else if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 3
            && $getPurchaseContractCreate->c_article5_fixed_survey_options_contract == 2){
                $worksheet->getCell('AJ'.(64 + ($roadCounterLoop-1)))->setValue('■');
        }
        else{
            $worksheet->getCell('AJ'.(65 + ($roadCounterLoop-1)))->setValue('■');
        }

        if($getPurchaseContractCreate->c_article5_date_contract != null && $getPurchaseContractCreate->c_article5_creator_contract != null) {
            if($getPurchaseContractCreate->c_article5_fixed_survey_contract == 3
                && $getPurchaseContractCreate->c_article5_fixed_survey_options_contract == 2){
                    $worksheet->getCell('AJ'.(68 + ($roadCounterLoop-1)))->setValue('■');
            }else{
                $worksheet->getCell('AJ'.(70 + ($roadCounterLoop-1)))->setValue('■');
            }
            $convertDate = Carbon::parse($getPurchaseContractCreate->c_article5_date_contract);
            $nYear = PurchaseContractCreateImportReport::convert_to_nengou($convertDate->year);
            $worksheet->getCell('AN'.(68 + ($roadCounterLoop-1)))->setValue($nYear. '年'. $convertDate->month. '月'. $convertDate->day. '日');
            $worksheet->getCell('AN'.(69 + ($roadCounterLoop-1)))->setValue($getPurchaseContractCreate->c_article5_creator_contract);
        }

        $spreadsheet->getActiveSheet()->getRowDimension(59 + ($roadCounterLoop-1))->setVisible(false);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for B. 売主の表示と占有に関する事項
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for Ⅰ 対象となる宅地に直接関係する事項
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Report output for II 取引条件に関する事項
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------

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
        $filepath = "{$directory}/重要事項説明書-{$targetID}.xlsx";
        // ------------------------------------------------------------------
        File::makeDirectory( "{$public}/{$directory}", 0777, true, true );
        $writer->save( "{$public}/{$filepath}" );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return url( $filepath );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create Output
        // ------------------------------------------------------------------
        /*$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $projectId = $projectId;
        $targetId = $targetId;
        $path = public_path().'/reports/output/' . 'project_'. $projectId. '/'. 'contract_'. $targetId;
        File::makeDirectory($path, $mode = 0777, true, true);
        $filepath = '/reports/output/' . 'project_'. $projectId. '/'. 'contract_'. $targetId. '/重要事項説明書.xlsx';
        $writer->save(public_path().$filepath);
        return url( $filepath );*/
        // ------------------------------------------------------------------
    }
}
