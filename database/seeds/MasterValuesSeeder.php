<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use App\Models\MasterValue;
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasterValuesSeeder extends Seeder {
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Static dataset
    // ----------------------------------------------------------------------
    public function dataset(){
        return array(
            array(
                'type'    => 'building_usetype', // 1-10
                'entries' => array(
                    array( 'key' => 1,  'sort' => 1,  'value' => '住宅' ),
                    array( 'key' => 2,  'sort' => 2,  'value' => '店舗' ),
                    array( 'key' => 3,  'sort' => 3,  'value' => '事務所' ),
                    array( 'key' => 4,  'sort' => 4,  'value' => '倉庫' ),
                    array( 'key' => 5,  'sort' => 5,  'value' => '工場' ),
                    array( 'key' => 6,  'sort' => 6,  'value' => '事務所倉庫' ),
                    array( 'key' => 7,  'sort' => 7,  'value' => 'その他' ),
                    array( 'key' => 8,  'sort' => 8,  'value' => '賃貸住宅' ),
                    array( 'key' => 9,  'sort' => 9,  'value' => '賃貸店舗併用住宅' ),
                    array( 'key' => 10, 'sort' => 10, 'value' => '駐車場' )
                )
            ),
            array(
                'type'    => 'building_structure', // 11-16
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '木造' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '鉄骨造' ),
                    array( 'key' => 3, 'sort' => 3, 'value' => '鉄筋コンクリート造' ),
                    array( 'key' => 4, 'sort' => 4, 'value' => '鉄骨鉄筋コンクリート造' ),
                    array( 'key' => 5, 'sort' => 5, 'value' => '軽量鉄骨造' ),
                    array( 'key' => 6, 'sort' => 6, 'value' => 'その他' )
                )
            ),
            array(
                'type'    => 'restriction', // 17-18
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '駐車場附置義務条例' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '屋外広告物条例 第二種許可地域' )
                )
            ),
            array(
                'type'    => 'company_partner_type', // 19-23
                'entries' => array(
                    array( 'key' => 'realastate',   'sort' => 1,   'value' => '不動産情報' ),
                    array( 'key' => 'registration', 'sort' => 2,   'value' => '登記' ),
                    array( 'key' => 'survey',       'sort' => 3,   'value' => '測量' ),
                    array( 'key' => 'clothes',      'sort' => 4,   'value' => '衣服' ),
                    array( 'key' => 'other',        'sort' => 999, 'value' => 'その他' ),
                )
            ),
            array(
                'type'    => 'user_type', // 24
                'entries' => array(
                    array( 'key' => '', 'sort' => 0, 'value' => 'ログインを許可しない' )
                )
            ),
            array(
                'type'    => 'reserve', // 25
                'entries' => array(
                    array( 'key' => 7, 'sort' => 7, 'value' => '予備' )
                )
            ),
            array(
                'type'    => 'identification', // 26-31
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '運転免許証' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => 'パスポート' ),
                    array( 'key' => 3, 'sort' => 3, 'value' => '健康保険証' ),
                    array( 'key' => 4, 'sort' => 4, 'value' => '年金手帳' ),
                    array( 'key' => 5, 'sort' => 5, 'value' => '謄本' ),
                    array( 'key' => 6, 'sort' => 6, 'value' => '不要' )
                )
            ),
            array(
                'type'    => 'dealtype', // 32-34
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '土地' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '建売' ),
                    array( 'key' => 3, 'sort' => 3, 'value' => '中古住宅' )
                )
            ),
            array(
                'type'    => 'currentstate', // 35-36
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '登記簿と同じ' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '登記簿と異なる' )
                )
            ),
            array(
                'type'    => 'usedistrict', // 37-49
                'entries' => array(
                    array( 'key' => 1,  'sort' => 1,  'value' => '第一種低層住居専用地域' ),
                    array( 'key' => 2,  'sort' => 2,  'value' => '第二種低層住居専用地域' ),
                    array( 'key' => 3,  'sort' => 3,  'value' => '第一種中高層住居専用地域' ),
                    array( 'key' => 4,  'sort' => 4,  'value' => '第二種中高層住居専用地域' ),
                    array( 'key' => 5,  'sort' => 5,  'value' => '第一種住居地域' ),
                    array( 'key' => 6,  'sort' => 6,  'value' => '第二種住居地域' ),
                    array( 'key' => 7,  'sort' => 7,  'value' => '準住居地域' ),
                    array( 'key' => 8,  'sort' => 8,  'value' => '田園住居地域' ),
                    array( 'key' => 9,  'sort' => 9,  'value' => '近隣商業地域' ),
                    array( 'key' => 10, 'sort' => 10, 'value' => '商業地域' ),
                    array( 'key' => 11, 'sort' => 11, 'value' => '準工業地域' ),
                    array( 'key' => 12, 'sort' => 12, 'value' => '工業地域' ),
                    array( 'key' => 13, 'sort' => 13, 'value' => '工業専用地域' )
                )
            ),
            array(
                'type'    => 'land_category', // 50-62
                'entries' => array(
                    array( 'key' => 1,  'sort' => 1,   'value' => '宅地' ),
                    array( 'key' => 2,  'sort' => 2,   'value' => '田' ),
                    array( 'key' => 3,  'sort' => 3,   'value' => '畑' ),
                    array( 'key' => 8,  'sort' => 5,   'value' => '原野' ),
                    array( 'key' => 5,  'sort' => 5,   'value' => '山林' ),
                    array( 'key' => 9,  'sort' => 6,   'value' => '境内地' ),
                    array( 'key' => 12, 'sort' => 7,   'value' => '堤' ),
                    array( 'key' => 6,  'sort' => 8,   'value' => '公衆用道路' ),
                    array( 'key' => 13, 'sort' => 9,   'value' => '公園' ),
                    array( 'key' => 14, 'sort' => 10,  'value' => '鉄道用地' ),
                    array( 'key' => 7,  'sort' => 11,  'value' => '学校用地' ),
                    array( 'key' => 4,  'sort' => 12,  'value' => '雑用地' ),
                    array( 'key' => 99, 'sort' => 999, 'value' => 'その他' )
                )
            ),
            array(
                'type'    => 'building_roof', // 63-72
                'entries' => array(
                    array( 'key' => 1,  'sort' => 1,  'value' => '瓦ぶき' ),
                    array( 'key' => 2,  'sort' => 2,  'value' => 'スレートぶき' ),
                    array( 'key' => 3,  'sort' => 3,  'value' => '亜鉛メッキ鋼板ぶき' ),
                    array( 'key' => 4,  'sort' => 4,  'value' => '陸屋根' ),
                    array( 'key' => 5,  'sort' => 5,  'value' => 'セメント瓦ぶき' ),
                    array( 'key' => 6,  'sort' => 6,  'value' => 'アルミニウムぶき' ),
                    array( 'key' => 7,  'sort' => 7,  'value' => '鋼板ぶき' ),
                    array( 'key' => 8,  'sort' => 8,  'value' => 'ルーフィングぶき' ),
                    array( 'key' => 9,  'sort' => 9,  'value' => 'ビニール板ぶき' ),
                    array( 'key' => 10, 'sort' => 10, 'value' => '合金メッキ鋼板ぶき' )
                )
            ),
            array(
                'type'    => 'realestate_license_organ', // 73-77
                'entries' => array(
                    array( 'key' => 4,   'sort' => 1,   'value' => '宮城県知事' ),
                    array( 'key' => 7,   'sort' => 2,   'value' => '福島県知事' ),
                    array( 'key' => 3,   'sort' => 3,   'value' => '岩手県知事' ),
                    array( 'key' => 5,   'sort' => 5,   'value' => '山形県知事' ),
                    array( 'key' => 999, 'sort' => 999, 'value' => '国土交通省' )
                )
            ),
            array(
                'type'    => 'realestate_explainer_number_place', // 78-81
                'entries' => array(
                    array( 'key' => 'miyagi',    'sort' => 1, 'value' => '宮城' ),
                    array( 'key' => 'fukushima', 'sort' => 2, 'value' => '福島' ),
                    array( 'key' => 'aomori',    'sort' => 3, 'value' => '青森' ),
                    array( 'key' => 'ishikari',  'sort' => 3, 'value' => '石狩' )
                )
            ),
            array(
                'type'    => 'realestate_guarantee_association', // 82-84
                'entries' => array(
                    array( 'key' => 'zennichi', 'sort' => 1,   'value' => '全日' ),
                    array( 'key' => 'zentaku',  'sort' => 2,   'value' => '全宅' ),
                    array( 'key' => 'other',    'sort' => 999, 'value' => 'その他' )
                )
            ),
            array(
                'type'    => 'land_category', // 86
                'entries' => array(
                    array( 'key' => 15, 'sort' => 4, 'value' => '牧場' )
                )
            ),
            array(
                'type'    => 'user_type', // 87-92
                'entries' => array(
                    array( 'key' => 'general',              'sort' => 1, 'value' => '一般' ),
                    array( 'key' => 'agent',                'sort' => 2, 'value' => 'エージェント' ),
                    array( 'key' => 'editor',               'sort' => 3, 'value' => '台帳編集者' ),
                    array( 'key' => 'registration_manager', 'sort' => 4, 'value' => '登記管理責任者' ),
                    array( 'key' => 'administrator',        'sort' => 5, 'value' => '全体管理者' ),
                    array( 'key' => 'accountant',           'sort' => 6, 'value' => '会計事務所' )
                )
            ),
            array(
                'type'    => 'multiplication_rate', // 93-94
                'entries' => array(
                    array( 'key' => 1, 'sort' => 0, 'value' => 4 ),
                    array( 'key' => 2, 'sort' => 0, 'value' => 6 )
                )
            ),
            array(
                'type'    => 'real_estate_guarantee_company', // 95-99
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => '(株)日本住宅保証検査機構' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '(株)住宅あんしん保証' ),
                    array( 'key' => 3, 'sort' => 3, 'value' => '住宅保証機構(株)' ),
                    array( 'key' => 4, 'sort' => 4, 'value' => '(株)ハウスジーメン' ),
                    array( 'key' => 5, 'sort' => 5, 'value' => 'ハウスプラス住宅保証(株)' )
                )
            ),
            array(
                'type'    => 'pj_sheet_calculate_group', // 100+
                'entries' => array(
                    array( 'key' => 1,  'sort' => 1, 'value' => '建物解体工事' ),
                    array( 'key' => 2,  'sort' => 2, 'value' => '水道・下水工事' ),
                    array( 'key' => 3,  'sort' => 3, 'value' => '盛り土工事' ),
                    array( 'key' => 4,  'sort' => 4, 'value' => '擁壁工事' ),
                    array( 'key' => 5,  'sort' => 5, 'value' => '道路工事' ),
                    array( 'key' => 6,  'sort' => 6, 'value' => '側溝工事' ),
                    array( 'key' => 7,  'sort' => 7, 'value' => '造成工事一式' ),
                    array( 'key' => 8,  'sort' => 8, 'value' => '造成工事一式(インフラが遠い場合)' ),
                    array( 'key' => 9,  'sort' => 9, 'value' => '造成工事一式(文化財調査費用)' ),
                    array( 'key' => 10, 'sort' => 10, 'value' => '位置指定申請費' ),
                    array( 'key' => 11, 'sort' => 11, 'value' => '開発委託費' )
                )
            ),
            array(
                'type'    => 'pj_sheet_calculate_field', // 111+
                'entries' => array(
                    array( 'key' => 1001,  'sort' => 1,  'value' => '木造' ),
                    array( 'key' => 1002,  'sort' => 2,  'value' => '鉄筋' ),
                    array( 'key' => 1003,  'sort' => 3,  'value' => 'RC' ),
                    array( 'key' => 2001,  'sort' => 1,  'value' => '引き込み個所数' ),
                    array( 'key' => 3001,  'sort' => 1,  'value' => '立米数' ),
                    array( 'key' => 4001,  'sort' => 1,  'value' => '高さ0.5m' ),
                    array( 'key' => 4002,  'sort' => 2,  'value' => '高さ0.5m：調整額' ),
                    array( 'key' => 4003,  'sort' => 3,  'value' => '高さ0.75m' ),
                    array( 'key' => 4004,  'sort' => 4,  'value' => '高さ0.75m：調整額' ),
                    array( 'key' => 4005,  'sort' => 5,  'value' => '高さ1m' ),
                    array( 'key' => 4006,  'sort' => 6,  'value' => '高さ1m：調整額' ),
                    array( 'key' => 4007,  'sort' => 7,  'value' => '高さ1.5m' ),
                    array( 'key' => 4008,  'sort' => 8,  'value' => '高さ1.5m：調整額' ),
                    array( 'key' => 4009,  'sort' => 9,  'value' => '高さ1.75m' ),
                    array( 'key' => 4010,  'sort' => 10, 'value' => '高さ1.75m：調整額' ),
                    array( 'key' => 4011,  'sort' => 11, 'value' => '高さ1.95m' ),
                    array( 'key' => 4012,  'sort' => 12, 'value' => '高さ1.95m：調整額' ),
                    array( 'key' => 5001,  'sort' => 1,  'value' => '道路工事' ),
                    array( 'key' => 5002,  'sort' => 2,  'value' => '道路工事：調整額' ),
                    array( 'key' => 6001,  'sort' => 1,  'value' => '片側' ),
                    array( 'key' => 6002,  'sort' => 2,  'value' => '片側：調整額' ),
                    array( 'key' => 6003,  'sort' => 3,  'value' => '両側' ),
                    array( 'key' => 6004,  'sort' => 4,  'value' => '両側：調整額' ),
                    array( 'key' => 7001,  'sort' => 1,  'value' => '平坦' ),
                    array( 'key' => 7002,  'sort' => 2,  'value' => '～1m' ),
                    array( 'key' => 7003,  'sort' => 3,  'value' => '～2m' ),
                    array( 'key' => 7004,  'sort' => 4,  'value' => '2m以上' ),
                    array( 'key' => 8001,  'sort' => 1,  'value' => '平坦' ),
                    array( 'key' => 8002,  'sort' => 2,  'value' => '～1m' ),
                    array( 'key' => 8003,  'sort' => 3,  'value' => '～2m' ),
                    array( 'key' => 8004,  'sort' => 4,  'value' => '2m以上' ),
                    array( 'key' => 9001,  'sort' => 1,  'value' => '造成工事一式(文化財調査費用)' ),
                    array( 'key' => 10001, 'sort' => 1,  'value' => '位置指定申請費' ),
                    array( 'key' => 11001, 'sort' => 1,  'value' => '開発委託費' )
                )
            ),
            array(
                'type'    => 'request_inspection_kind', // 144+
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => 'PJシート' ),
                    array( 'key' => 2, 'sort' => 2, 'value' => '仕入買付証明書' ),
                    array( 'key' => 3, 'sort' => 3, 'value' => '仕入契約書・重説' ),
                    array( 'key' => 4, 'sort' => 4, 'value' => '販売買付' ),
                    array( 'key' => 5, 'sort' => 5, 'value' => '販売契約書・重説' )
                )
            ),
            array(
                'type'    => 'general_tax_rate', // 148
                'entries' => array(
                    array( 'key' => 1, 'sort' => 1, 'value' => 10 )
                )
            )
        );
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Insert the dataset
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        $dataset = $this->dataset();
        // ------------------------------------------------------------------
        if( !empty( $dataset )) foreach( $dataset as $group ){
            // --------------------------------------------------------------
            $group = (object) $group;
            if( isset( $group->type )) $type = $group->type;
            if( isset( $group->entries ) && !empty( $group->entries )){
                // ----------------------------------------------------------
                foreach( $group->entries as $entry ){
                   // ------------------------------------------------------
                   $model = new MasterValue;
                   $model->type = $type;
                   // ------------------------------------------------------
                   foreach( $entry as $field => $value ){
                       $model->{ $field } = $value;
                   }
                   // ------------------------------------------------------
                   $model->created_at = Carbon::now();
                   $model->updated_at = Carbon::now();
                   // ------------------------------------------------------
                   $model->save();
                   // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
