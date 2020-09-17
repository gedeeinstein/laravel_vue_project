<?php

namespace App\Reports;

// --------------------------------------------------------------------------
use App\Models\OtherAdditionalQaCheck;
use App\Models\OtherAdditionalQaCategory;
use App\Models\PjChecklist;
// --------------------------------------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
// --------------------------------------------------------------------------
use File;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CheckListReport
{
    // ----------------------------------------------------------------------
    // Create Report Output Of CheckList
    // ----------------------------------------------------------------------
    public static function reportCheckList( $data ){
        // ------------------------------------------------------------------
        // Update data cell to excel template
        // ------------------------------------------------------------------
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( 'reports/template/sheet-checklist.xlsx' );
        $worksheet = $spreadsheet->getActiveSheet();
        // ------------------------------------------------------------------
        
        // ------------------------------------------------------------------
        // Parameter for retrive sheet and project data
        // ------------------------------------------------------------------
        $retriveDataSheet = $data->sheet;
        $retriveDataChecklist = $data->sheet['checklist'];
        $retriveDataProject = $data->project;
        $retriveDataProjectQuestion = $data->project['question'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Data
        // ------------------------------------------------------------------
        $worksheet->getCell('A3')->setValue($retriveDataProject['title']);
        $worksheet->getCell('B7')->setValue($retriveDataProject['overall_area']);
        $worksheet->getCell('B8')->setValue($retriveDataProject['building_area']);
        $worksheet->getCell('B9')->setValue($retriveDataProject['building_coverage_ratio']);
        $worksheet->getCell('H7')->setValue($retriveDataProject['fixed_asset_tax_route_value']);
        
        $arrUsageArea = ['第一種低層住居専用地域', '第二種低層住居専用地域', '第一種中高層住居専用地域', '第二種中高層住居専用地域',
                         '第一種住居地域', '第二種住居地域', '準住居地域', '田園住居地域', '近隣商業地域', '商業地域', '準工業地域',
                         '工業地域', '工業専用地域'];
        $getIndexUsageArea = ($retriveDataProject['usage_area'] - 37);
        $worksheet->getCell('H8')->setValue($arrUsageArea[$getIndexUsageArea]);
        //$worksheet->getCell('K7')->setValue($retriveDataProject['fixed_asset_tax_route_value'] * 100);
        $worksheet->getCell('H9')->setValue($retriveDataProject['floor_area_ratio']);
        $worksheet->getCell('B10')->setValue($retriveDataProject['estimated_closing_date']);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for 基本Q&A (A1 - Z18)
        // ------------------------------------------------------------------

        $getDifferenceInHeight = $retriveDataProjectQuestion['difference_in_height'];

        $worksheet->getCell('H14')->setValue($retriveDataProjectQuestion['soil_contamination'] == 1 ? 'ある':
                                            ($retriveDataProjectQuestion['soil_contamination'] == 2 ? 'ほぼなし':'不明')); // A1 Output
        $worksheet->getCell('H15')->setValue($retriveDataProjectQuestion['cultural_property'] == 1 ? '入っている':
                                            ($retriveDataProjectQuestion['cultural_property'] == 2 ? '入っていない':'未確認')); // A2 Output
        $worksheet->getCell('H16')->setValue($retriveDataProjectQuestion['district_planning'] == 1 ? 'ある':'なし'); // A3 Output
        if($retriveDataProjectQuestion['district_planning'] == 1){ // A3 Condition Output
            $worksheet->getCell('H17')->setValue($retriveDataProjectQuestion['building_use_restrictions']);
            $worksheet->getCell('H18')->setValue((float)$retriveDataProjectQuestion['minimum_area']. ' ㎡');
            if(empty($retriveDataProjectQuestion['building_use_restrictions'])){
                $worksheet->getCell('H17')->setValue('-');
            }
            if(empty($retriveDataProjectQuestion['minimum_area'])){
                $worksheet->getCell('H18')->setValue('-');
            }
        }else{
            $worksheet->getCell('H17')->setValue('-');
            $worksheet->getCell('H18')->setValue('-');
        }

        if($getDifferenceInHeight == 1){ // A4 Output
            $worksheet->getCell('H19')->setValue('0~0.5m未満');
        }elseif($getDifferenceInHeight == 2){
            $worksheet->getCell('H19')->setValue('0.5~1m未満');
        }elseif($getDifferenceInHeight == 3){
            $worksheet->getCell('H19')->setValue('1~2m未満');
        }elseif($getDifferenceInHeight == 4){
            $worksheet->getCell('H19')->setValue('2m以上');
        }elseif($getDifferenceInHeight == 5){
            $worksheet->getCell('H19')->setValue('未確認');
        }
        $worksheet->getCell('H20')->setValue($retriveDataProjectQuestion['retaining_wall'] == 1 ? 'ある':'なし'); // A5 Output
        if($retriveDataProjectQuestion['retaining_wall'] == 1){
            $worksheet->getCell('H21')->setValue($retriveDataProjectQuestion['retaining_wall_location'] == 1 ? '隣地':'当該地');
            $worksheet->getCell('H22')->setValue($retriveDataProjectQuestion['retaining_wall_breakage'] == 1 ? '破損あり':
                                                ($retriveDataProjectQuestion['retaining_wall_breakage'] == 2 ? '破損なし':'未確認'));
            if(empty($retriveDataProjectQuestion['retaining_wall_location'])){
                $worksheet->getCell('H21')->setValue('-');
            }
            if(empty($retriveDataProjectQuestion['retaining_wall_breakage'])){
                $worksheet->getCell('H22')->setValue('-');
            }
        }else{
            $worksheet->getCell('H21')->setValue('-');
            $worksheet->getCell('H22')->setValue('-');
        }
        $worksheet->getCell('H23')->setValue($retriveDataProjectQuestion['water'] == 1 ? 'ある':'なし'); // A6 Output
        if($retriveDataProjectQuestion['water'] == 1){
            $worksheet->getCell('H24')->setValue((float)$retriveDataProjectQuestion['water_supply_pipe'].' ミリ');
            $worksheet->getCell('H25')->setValue((float)$retriveDataProjectQuestion['water_meter'].' ミリ');
        }else{
            $worksheet->getCell('H24')->setValue('-');
            $worksheet->getCell('H25')->setValue('-');
        }
        $worksheet->getCell('H26')->setValue($retriveDataProjectQuestion['sewage'] == 1 ? 'ある':'なし'); // A7 Output

        $a8OutputCollection = ''; // A8 Collection Answer
        if($retriveDataProjectQuestion['private_pipe'] == 1){ 
            $a8OutputCollection = '/ 私設管 ';
        }
        if($retriveDataProjectQuestion['cross_other_land'] == 1){
            $a8OutputCollection = $a8OutputCollection. '/ 第三者の土地をまたぐ ';
        }
        if($retriveDataProjectQuestion['insufficient_capacity'] == 1){
            $a8OutputCollection = $a8OutputCollection. '/ 容量不足の可能性あり';
        }
        $a8OutputCollection = substr($a8OutputCollection,1);
        $worksheet->getCell('H27')->setValue($a8OutputCollection); // A8 Output

        if(!$a8OutputCollection){
            $worksheet->getCell('H27')->setValue('-');
        }
        
        $worksheet->getCell('H28')->setValue($retriveDataProjectQuestion['telegraph_pole_hindrance'] == 1 ? 'ある':'なし'); // A9 Output
        if($retriveDataProjectQuestion['telegraph_pole_hindrance'] == 1){

            if(empty($retriveDataProjectQuestion['telegraph_pole_high_cost'])){
                $worksheet->getCell('H29')->setValue('-');
            }

            $collectPoleHindrance = '';
            if($retriveDataProjectQuestion['telegraph_pole_move'] == 1){
                $collectPoleHindrance = '移動可能';
            }else if($retriveDataProjectQuestion['telegraph_pole_move'] == 2){
                $collectPoleHindrance = '移動不可';
            }else if($retriveDataProjectQuestion['telegraph_pole_move'] == 3){
                $collectPoleHindrance = '移動の可否不明';
            }else{
                $collectPoleHindrance = '-';
            }
            $worksheet->getCell('H29')->setValue($collectPoleHindrance);
            if($retriveDataProjectQuestion['telegraph_pole_high_cost'] == 1){
                $worksheet->getCell('H29')->setValue($collectPoleHindrance.' / '.'多額費用可能性あり');
                if(empty($retriveDataProjectQuestion['telegraph_pole_move'])){
                    $worksheet->getCell('H29')->setValue('多額費用可能性あり');
                }
            }

        }else{
            $worksheet->getCell('H29')->setValue('-');
        }
        $worksheet->getCell('H30')->setValue($retriveDataProjectQuestion['width_of_front_road'] == 1 ? '4m未満':
                                            ($retriveDataProjectQuestion['width_of_front_road'] == 2 ? '4m以上6m未満':'6m以上')); // A10 Output
        $worksheet->getCell('H31')->setValue($retriveDataProjectQuestion['plus_popular'] == 1 ? '人気のエリア':'-'); // A11 Output
        $worksheet->getCell('H31')->setValue($retriveDataProjectQuestion['plus_high_price_sale'] == 1 ? '近隣で高値売却の好事例あり':'-'); // A11 Output
        if($retriveDataProjectQuestion['plus_popular'] == 1){
            $worksheet->getCell('H31')->setValue('人気のエリア'); // A11 Output
        }
        if($retriveDataProjectQuestion['plus_popular'] == 1 && $retriveDataProjectQuestion['plus_high_price_sale'] == 1){
            $worksheet->getCell('H31')->setValue('人気のエリア / 近隣で高値売却の好事例あり'); // A11 Output
        }
        $worksheet->getCell('H32')->setValue(!empty($retriveDataProjectQuestion['plus_others']) ? $retriveDataProjectQuestion['plus_others']:''); // A11 Output

        $collectNegativeFactor = '';
        if($retriveDataProjectQuestion['plus_low_price_sale'] == 1){
            $collectNegativeFactor = '/ 近隣で販売苦戦事例あり ';
        }
        if($retriveDataProjectQuestion['minus_landslide_etc'] == 1){
            $collectNegativeFactor = $collectNegativeFactor. '/ 地盤軟弱・地滑り等 ';
        }
        if($retriveDataProjectQuestion['minus_psychological_defect'] == 1){
            $collectNegativeFactor = $collectNegativeFactor. '/ 心理的瑕疵あり';
        }
        $collectNegativeFactor = substr($collectNegativeFactor,1);
        $worksheet->getCell('H33')->setValue($collectNegativeFactor); // A12 Output
        if(!$collectNegativeFactor){
            $worksheet->getCell('H33')->setValue('-');
        }

        $worksheet->getCell('H34')->setValue(!empty($retriveDataProjectQuestion['minus_others']) ? $retriveDataProjectQuestion['minus_others']:'-');

        if($retriveDataProjectQuestion['fixed_survey'] == 1){ // A13 Output
            $worksheet->getCell('H35')->setValue('売主負担');
            $worksheet->getCell('H36')->setValue('-');
            $worksheet->getCell('H37')->setValue('-');
        }elseif($retriveDataProjectQuestion['fixed_survey'] == 2){
            $worksheet->getCell('H35')->setValue('買主負担');
            $worksheet->getCell('H36')->setValue('-');
            $worksheet->getCell('H37')->setValue('-');
        }elseif($retriveDataProjectQuestion['fixed_survey'] == 3){
            $worksheet->getCell('H35')->setValue('完了済');
        }elseif($retriveDataProjectQuestion['fixed_survey'] == 4){
            $worksheet->getCell('H35')->setValue('実施しない');
        }

        if($retriveDataProjectQuestion['fixed_survey'] == 3){
            if($retriveDataProjectQuestion['fixed_survey_season'] == 1){
                $worksheet->getCell('H36')->setValue('震災後に実施');
            }elseif($retriveDataProjectQuestion['fixed_survey_season'] == 2){
                $worksheet->getCell('H36')->setValue('震災前（平成）に実施');
            }elseif($retriveDataProjectQuestion['fixed_survey_season'] == 3){
                $worksheet->getCell('H36')->setValue('震災前（昭和）に実施');
            }elseif($retriveDataProjectQuestion['fixed_survey_season'] == 4){
                $worksheet->getCell('H36')->setValue('実施時期未確認');
            }else{
                $worksheet->getCell('H36')->setValue('-');
            }
            $worksheet->getCell('H37')->setValue('-');
        }elseif($retriveDataProjectQuestion['fixed_survey'] == 4){
            $worksheet->getCell('H37')->setValue($retriveDataProjectQuestion['fixed_survey_reason'] == 1 ? '境界杭復元のみ':'区画整理地等のため不要');
            $worksheet->getCell('H36')->setValue('-');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Output Report for Sheet Selected
        // ------------------------------------------------------------------
        $convertDateTime = explode(' ', $retriveDataSheet['created_at']);
        $convertDate = $convertDateTime[0];
        $convertedDate = explode('-', $convertDate);
        $convertTime = $convertDateTime[1];
        $convertedTime = explode(':', $convertTime);
         
        $worksheet->getCell('A46')->setValue($retriveDataSheet['name'].'（'. 
                                                $convertedDate[0]. '年'. 
                                                $convertedDate[1]. '月'.
                                                $convertedDate[2] .'日 '. 
                                                $convertedTime[0].':'.$convertedTime[1]. ' '. 
                                                $retriveDataSheet['creator_name'] .')');
        $worksheet->getCell('A47')->setValue($retriveDataSheet['memo']);

        // チェックリスト
        $getProcurementBrokerageFee = $retriveDataChecklist['procurement_brokerage_fee'];
        $getResaleBrokerageFee =  $retriveDataChecklist['resale_brokerage_fee'];
        $getConstructionWork =  $retriveDataChecklist['construction_work'];

        $worksheet->getCell('B51')->setValue($retriveDataChecklist['breakthrough_rate']);
        $worksheet->getCell('B52')->setValue($retriveDataChecklist['loan_borrowing_amount']);
        $worksheet->getCell('H51')->setValue($retriveDataChecklist['effective_area']);
        $worksheet->getCell('B53')->setValue($getProcurementBrokerageFee == 1 ? '支出':($getProcurementBrokerageFee == 2 ? '収入':'無し'));
        $worksheet->getCell('H53')->setValue($getResaleBrokerageFee == 1 ? '支出':($getResaleBrokerageFee == 2 ? '収入':'無し'));

        $worksheet->getCell('A55')->setValue($retriveDataChecklist['sales_area'] == 1 ? '1区画で売る予定の物件':'2区画以上で売る予定の物件');
        $worksheet->getCell('A56')->setValue($retriveDataChecklist['building_demolition_work'] == 1 ? '建物解体工事を伴う': '-');
        $worksheet->getCell('A57')->setValue($retriveDataChecklist['demolition_work_of_retaining_wall'] == 1 ? '擁壁の解体工事を伴う': '-');
        $worksheet->getCell('A58')->setValue($getConstructionWork == 1 ? '造成工事なし':($getConstructionWork == 2 ? '造成工事（非開発）':'造成工事（開発工事）'));
        $worksheet->getCell('A59')->setValue($retriveDataChecklist['driveway'] == 1 ? '私道がからんでいる':'-');

        // Q&A 区割り
        $worksheet->getCell('H62')->setValue($retriveDataChecklist['realistic_division'] == 1 ? 'なっている':'なっていない'); //A14 Output
        
        // Q&A 建物解体工事伴う場合
        if($retriveDataChecklist['building_demolition_work'] == 1){
            $getTypeOfBuilding = $retriveDataChecklist['type_of_building']; //B1 Variable

            $worksheet->getCell('H64')->setValue($getTypeOfBuilding == 1 ? '木造':($getTypeOfBuilding == 2 ? '鉄骨':'RC')); //B1 Output
            $worksheet->getCell('H65')->setValue($retriveDataChecklist['asbestos'] == 1 ? 'ある':'なし'); //B2 Output

            $costFactor = '';
            if($retriveDataChecklist['many_trees_and_stones'] == 1){
                $costFactor = '/ 木や石、塀など多い ';
            }
            if($retriveDataChecklist['big_storeroom'] == 1){
                $costFactor = $costFactor. '/ 大きな物置 ';
            }
            if($retriveDataChecklist['hard_to_enter'] == 1){
                $costFactor = $costFactor. '/ 土地狭く重機入りにくい';
            }
            $costFactor = substr($costFactor,1);
            $worksheet->getCell('H66')->setValue($costFactor); //B3 Output
            if(empty($costFactor)){
                $worksheet->getCell('H66')->setValue('-');
            }

        }else{
            $worksheet->getCell('H64')->setValue('-'); //B1 Output
            $worksheet->getCell('H65')->setValue('-'); //B2 Output
            $worksheet->getCell('H66')->setValue('-'); //B3 Output
        }

        // Q&A 造成ある場合（開発にかからない工事）
        $getNewRoadType = $retriveDataChecklist['new_road_type']; //D2 variable
        $getSideGrove = $retriveDataChecklist['side_groove']; //D4 variable
        $getNoFill = $retriveDataChecklist['no_fill']; //D5 variable
        $getRetainingWall = $retriveDataChecklist['retaining_wall']; //D6 variable
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        if( $getConstructionWork != 1 ){
            // --------------------------------------------------------------
            // D1. Water draw count
            // --------------------------------------------------------------
            $waterDraw = $retriveDataChecklist['water_draw_count'];
            $worksheet->getCell('H68')->setValue( !empty( $waterDraw ) ? $waterDraw.' 箇所': '-' );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // D2. Road type
            // --------------------------------------------------------------
            $roadType = '-';
            $options = collect([ 1 => '位置指定道路', 2 => '専用道路', 3 => '道路なし' ]);
            if( $options->has( $getNewRoadType )) $roadType = $options->get( $getNewRoadType );
            $worksheet->getCell('H69')->setValue( $roadType );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // D3. Road dimension
            // --------------------------------------------------------------
            $dimension = collect();
            // --------------------------------------------------------------
            if( 3 !== $getNewRoadType ){
                // ----------------------------------------------------------
                $roadWidth = $retriveDataChecklist['new_road_width'];
                $roadLength = $retriveDataChecklist['new_road_length'];
                // ----------------------------------------------------------
                if( !empty( $roadWidth )) $dimension->push( '幅員 '.floatval( $roadWidth ).'m' );
                if( !empty( $roadLength )) $dimension->push( '長さ '.floatval( $roadLength ).'m' );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            $dimension = !$dimension->isEmpty() ? $dimension->join(' x ') : '-';
            $worksheet->getCell('H70')->setValue( $dimension );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // D4. Side grove / gutter
            // --------------------------------------------------------------
            $gutter = '-';
            $options = collect([ 1 => '片側', 2 => '両側', 3 => 'なし' ]);
            if( $options->has( $getSideGrove )) $gutter = $options->get( $getSideGrove );
            $worksheet->getCell('H71')->setValue( $gutter );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $gutterLength = '-'; $gutterFill = '-';
            if( 3 !== $getSideGrove ){
                // ----------------------------------------------------------
                // D5. Side grove / gutter length
                // ----------------------------------------------------------
                $length = $retriveDataChecklist['side_groove_length'];
                if( !empty( $length )) $gutterLength = floatval( $length ).'m';
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // D6. Side grove / gutter fill
                // ----------------------------------------------------------
                $fill = $retriveDataChecklist['fill'];
                if( $getNoFill ) $gutterFill = '盛土なし';
                elseif( !empty( $fill )) $gutterFill = floatval( $fill ).'m3';
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            $worksheet->getCell('H72')->setValue( $gutterLength );
            $worksheet->getCell('H73')->setValue( $gutterFill );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // D7. Retaining wall
            // --------------------------------------------------------------
            $retainingWall = '-';
            $options = collect([ 1 => '擁壁を新設する', 2 => '擁壁を新設しない' ]);
            if( $options->has( $getRetainingWall )) $retainingWall = $options->get( $getRetainingWall );
            $worksheet->getCell('H74')->setValue( $retainingWall );
            // --------------------------------------------------------------
            $height = '-'; $length = '-';
            if( 1 === $getRetainingWall ){
                // ----------------------------------------------------------
                // Retaining wall height
                // ----------------------------------------------------------
                $wallHeight = (int) $retriveDataChecklist['retaining_wall_height'];
                $options = collect([ 1 => 0.5, 2 => 0.75, 3 => 1, 4 => 1.5, 5 => 1.75, 6 => 1.95, 7 => 'それ以上' ]);
                if( $options->has( $wallHeight )) $height = $options->get( $wallHeight );
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // Retaining wall length 
                // ----------------------------------------------------------
                $wallLength = $retriveDataChecklist['retaining_wall_length'];
                if( !empty( $wallLength )) $length = floatval( $wallLength );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            $worksheet->getCell('H75')->setValue( $height );
            $worksheet->getCell('H76')->setValue( $length );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        } else {
            $worksheet->getCell('H68')->setValue('-'); //D1 Output
            $worksheet->getCell('H69')->setValue('-'); //D2 Output
            $worksheet->getCell('H70')->setValue('-'); //D3 Output
            $worksheet->getCell('H71')->setValue('-'); //D4 Output
            $worksheet->getCell('H72')->setValue('-'); //D4 Output
            $worksheet->getCell('H73')->setValue('-'); //D5 Output
            $worksheet->getCell('H74')->setValue('-'); //D6 Output
        }

        // Q&A 造成ある場合（開発工事）
        $getDevelopmentCost = $retriveDataChecklist['development_cost']; //E1 Variable

        //E1 Output
        if($getDevelopmentCost == 1){ 
            $worksheet->getCell('H78')->setValue('平坦地且つ特筆すべき点なし');
        }elseif($getDevelopmentCost == 2){
            $worksheet->getCell('H78')->setValue('高低差が〜１m程度ある');
        }elseif($getDevelopmentCost == 3){
            $worksheet->getCell('H78')->setValue('高低差が〜２m程度ある');
        }else{
            $worksheet->getCell('H78')->setValue('高低差が２m以上ある');
        }
        if(empty($getDevelopmentCost)){
            $worksheet->getCell('H78')->setValue('-');
        }
        $worksheet->getCell('H79')->setValue($retriveDataChecklist['main_pipe_is_distant'] == 1 ? 'インフラ本管が遠い':'-');

        // Q&A 私道がからむ場合
        if($retriveDataChecklist['driveway'] == 1){
            $worksheet->getCell('H81')->setValue($retriveDataChecklist['road_sharing'] == 1 ? '持っている':'持っていない'); //F1 Output
            $worksheet->getCell('H82')->setValue($retriveDataChecklist['traffic_excavation_consent'] == 1 ? 'できる':'できない'); //F2 Output
            if(empty($retriveDataChecklist['road_sharing'])){
                $worksheet->getCell('H81')->setValue('-');
            }
            if(empty($retriveDataChecklist['traffic_excavation_consent'])){
                $worksheet->getCell('H82')->setValue('-');
            }
        }else{
            $worksheet->getCell('H81')->setValue('-'); //F1 Output
            $worksheet->getCell('H82')->setValue('-'); //F2 Output
        }
        
        // Q&A Z1 - Zx
        // ------------------------------------------------------------------
        // Create dynamic cell, it's loaded from additional question and answer
        // ------------------------------------------------------------------

        $arrByCategory = array();
        $categoryBefore = 0;

        $newCell = 1;
        $newCellTitle = 1;
        $titleLoop = 2; // New title for Q&A Z start in 2
        //$zCounter = 1;
        $maxLoop = sizeof($data->map); // Get max of array length
        foreach($data->map as $question){ // get question
            if(!empty($question->question)){
                $getQuestion = OtherAdditionalQaCheck::find($question->question->id);
                if($categoryBefore != $getQuestion->category_id){
                    if($newCellTitle == 1){
                        $getCategoryName = OtherAdditionalQaCategory::find($getQuestion->category_id);
                        $worksheet->getCell('A40')->setValue($getCategoryName->name); // Create new Tittle for dynamic Q&A Z
                        $categoryBefore = $getQuestion->category_id;
                    }
                    else{
                        //dd($loopCell);
                        //dd($zCounter);
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($loopCell+1); // Create new row
                        $spreadsheet->getActiveSheet()->mergeCells('A'.($loopCell+1).':'.'G'.($loopCell+1)); // Merge cell
                        $spreadsheet->getActiveSheet()->mergeCells('H'.($loopCell+1).':'.'L'.($loopCell+1));
                        $getCategoryName = OtherAdditionalQaCategory::find($getQuestion->category_id);
                        $worksheet->getCell('A'.($loopCell+1))->setValue($getCategoryName->name); // Create new Tititle for dynamic Q&A Z
                        $worksheet->getCell('A'.($loopCell+1))->getStyle()->getFont()->setBold(true);
                        $titleLoop++;
                        $newCell++;
                        array_push($arrByCategory, $newCell);
                        $categoryBefore = $getQuestion->category_id;
                    }
                }
                $loopCell = (40+($newCell++));
                $cellA = 'A'. $loopCell;
                $cellG = 'G'. $loopCell; 
                $cellH = 'H'. $loopCell;
                $cellL = 'L'. $loopCell;

                if($loopCell > 44){
                    $spreadsheet->getActiveSheet()->insertNewRowBefore($loopCell);
                    $spreadsheet->getActiveSheet()->mergeCells($cellA.':'.$cellG);
                    $spreadsheet->getActiveSheet()->mergeCells($cellH.':'.$cellL);
                    
                }

                $worksheet->getCell($cellA)->setValue('Z'.$getQuestion->id.'.'.$getQuestion->question); //set question in output
                $worksheet->getCell($cellA)->getStyle()->getFont()->setBold(false);
                
                $newCellTitle++;
                //$zCounter++;
            }
        }

        $newCell = 1;
        $newCellTitle = 1;
        foreach($data->map as $answer){ // get answer
            $loopCell = (40+($newCell++));
            $cellH = 'H'. $loopCell;
            if(!empty($answer->answer)){
                //array_push($arrByCategory, $newCell);
                if(in_array(($newCell+1), $arrByCategory)){
                    $worksheet->getCell($cellH)->setValue('');
                    $newCell++;
                }
                if(!empty($answer->answer[0]->label)){ // if have multiple answer for checkbox
                    $collectAnswer = '';
                    foreach($answer->answer as $m_answer){ //m_answer -> multiple answer
                        if($m_answer->checked){
                            $collectAnswer = $collectAnswer. '/ '.$m_answer->label;
                        }
                    }
                    if(!$collectAnswer){
                        $collectAnswer = '-';
                    }else{
                        $collectAnswer = substr($collectAnswer,1);
                    }
                    $worksheet->getCell($cellH)->setValue($collectAnswer);
                }else{ // if no have multiple answer
                    if(is_array($answer->answer) && sizeof($answer->answer) >= 1){
                        $collectMixAnswer = '';
                        foreach($answer->answer as $mix_answer){ //mix_answer -> single answer or multiple answer for textbox
                            $collectMixAnswer = $collectMixAnswer. ','. $mix_answer;
                        }
                        $collectMixAnswer = substr($collectMixAnswer,1);
                        $worksheet->getCell($cellH)->setValue($collectMixAnswer);
                    }else{
                        $worksheet->getCell($cellH)->setValue($answer->answer); //single answer only
                    }
                }
                $checkQuestionOptions = OtherAdditionalQaCheck::find($answer->question->id);
                if(empty($checkQuestionOptions['choices']) && $checkQuestionOptions['input_type'] == 2){
                    $worksheet->getCell($cellH)->setValue('-'); //single answer only
                }
                $newCellTitle++;
            }else{
                //array_push($arrByCategory, $newCell);
                if(in_array(($newCell+1), $arrByCategory)){
                    $worksheet->getCell($cellH)->setValue('');
                    $newCell++;
                }
                $worksheet->getCell($cellH)->setValue('-');
                $newCellTitle++;
            }
            
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create Output
        // ------------------------------------------------------------------
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter( $spreadsheet, 'Xlsx' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $public = public_path();
        $project = $data->project; $sheet = $data->sheet;
        $projectID = $project->id; $sheetID = $sheet->id;
        $projectHash = sha1( "port-project-$projectID" ); 
        $sheetHash = sha1( "port-sheet-$sheetID" );
        // ------------------------------------------------------------------
        $directory = "reports/output/{$projectHash}/{$sheetHash}";
        $filepath = "{$directory}/チェックリスト-{$sheetID}.xlsx";
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