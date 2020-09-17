<?php return [
    // ----------------------------------------------------------------------
    // Project Language Lines - Japanese
    // ----------------------------------------------------------------------
    'manager'                          => [
        'page'                         => [
            'title'                    => 'PJシート管理'
        ],
        'form'                         => [
            'phrase'                   => [
                'delete'               => [
                    'confirm'          => '続行しますか？'
                ],
                'update'               => [
                    'success'          => '変更が保存されました！',
                    'error'            => '編集した内容を保存できませんでした。'
                ]
            ]
        ]
    ],
    'base'                             => [
        'tabs'                         => [
            'sheet'                    => 'PJシート',
            'assist'                   => [
                'a'                    => 'アシストA',
                'b'                    => 'アシストB',
            ],
            'purchase'                 => [
                'index'                => '仕入買付',
                'sales'                => '仕入営業',
                'contract'             => '仕入契約'
            ],
            'expense'                  => '支出の部',
            'ledger'                   => '取引台帳'
        ]
    ],
    'sheet'                            => [
        'title'                        => 'PJシート＆チェックリスト',
        'status'                       => [
            'loading'                  => 'ローディング中'
        ],
        'tabs'                         => [
            'basic'                    => [
                'info'                 => '基本情報',
                'qna'                  => '基本Q&A'
            ],
            'group'                    => [
                'checklist'            => 'チェックリスト',
                'expense'              => '仕入の部',
                'sale'                 => '販売の部'
            ],
            'options'                  => [
                'duplicate'            => '複写',
                'delete'               => '削除',
                'new'                  => '追加'
            ]
        ],
        'label'                        => [
            'overall_area'             => '全体面積',
            'asset_tax'                => '固定資産税路線価/m<sup>2</sup>',
            'building_area'            => '建物面積',
            'area_type'                => '用途地域',
            'building_ratio'           => '建ぺい率',
            'floor_ratio'              => '容積率',
            'closing_date'             => '決済予定日',
            'building_restriction'     => '建物の用途制限',
            'minimum_area'             => '最低面積の制限',
            'retaining_wall'           => [
                'location'             => '擁壁（場所）',
                'breakage'             => '擁壁（破損）',
            ],
            'water'                    => [
                'supply'               => '引き込み',
                'meter'                => 'メーター',
                'unit'                 => 'ミリ'
            ],
            'other'                    => 'その他'
        ],
        'question'                     => [
            'soil_contamination'       => '土壌汚染の可能性',
            'cultural_property'        => '文化財埋蔵エリアに入っていますか？',
            'district_planning'        => '地区計画はありますか？',
            'building_restriction'     => '(※ある場合)建物の用途制限、最低面積の制限',
            'elevation'                => '隣地との高低差はどのくらいありますか？',
            'retaining_wall'           => '隣地または当該地に擁壁はありますか？',
            'water'                    => '水道は宅地内に引き込みがありますか？',
            'water_capacity'           => '(※ある場合)',
            'sewage'                   => '下水は宅地内に引き込みがありますか？',
            'water_extended'           => '上下水について該当する項目にチェックを付けてください。',
            'obstructive_pole'         => '電柱・支線が敷地内または敷地外の邪魔な位置にあるか？',
            'obstructive_pole_any'     => '(※ある場合)',
            'road'                     => '前面道路の幅員',
            'positive'                 => 'プラス要因',
            'negative'                 => 'マイナス要因',
            'survey'                   => '確定測量および地積更正登記の予定',
            'survey_season'            => '※実施時期',
            'survey_reason'            => '※未実施理由',
            'requirement'              => '全ての区画について、接道要件を満たしていますか？'
        ],
        'choices'                      => [
            'yes'                      => 'ある',
            'no'                       => 'なし',
            'almost_none'              => 'ほぼなし',
            'unknown'                  => '不明',
            'included'                 => '入っている',
            'not_included'             => '入っていない',
            'unconfirmed'              => '未確認',
            'elevation'                => [
                'under_half'           => '0~0.5m未満',
                'half_one'             => '0.5~1m未満',
                'one_two'              => '1~2m未満',
                'above_two'            => '2m以上'
            ],
            'retaining_wall'           => [
                'current_area'         => '当該地',
                'neighbouring_land'    => '隣地',
                'damaged'              => '破損あり',
                'no_damage'            => '破損なし'
            ],
            'water'                    => [
                'private_pipe'         => '私設管',
                'crossing_others'      => '第三者の土地をまたぐ',
                'insufficient'         => '容量不足の可能性あり'
            ],
            'pole'                     => [
                'movable'              => '移動可能',
                'not_movable'          => '移動不可',
                'unknown'              => '移動の可否不明',
                'expensive'            => '多額費用可能性あり'
            ],
            'road'                     => [
                'under_four'           => '4m未満',
                'four_six'             => '4m以上6m未満',
                'above_six'            => '6m以上'
            ],
            'positive'                 => [
                'popular'              => '人気のエリア',
                'value'                => '近隣で高値売却の好事例あり'
            ],
            'negative'                 => [
                'sale'                 => '近隣で販売苦戦事例あり',
                'landslide'            => '地盤軟弱・地滑り等',
                'defect'               => '心理的瑕疵あり'
            ],
            'survey'                   => [
                'sellers'              => '売主負担',
                'buyers'               => '買主負担',
                'completed'            => '完了済',
                'not_implemented'      => '実施しない',
                'season'               => [
                    'after_earthquake' => '震災後に実施',
                    'before_heisei'    => '震災前（平成）に実施',
                    'before_showa'     => '震災前（昭和）に実施',
                    'unconfirmed'      => '実施時期未確認'
                ],
                'reason'               => [
                    'restoration'      => '境界杭復元のみ',
                    'not_required'     => '区画整理地等のため不要'
                ]
            ],
            'requirement'              => [
                'yes'                  => '満たしている',
                'no'                   => '満たしていない'
            ]
        ],
        'checklist'                    => [
            'group'                    => [
                'division'             => '区割り',
                'demolition'           => '建物解体工事伴う場合',
                'construction'         => [
                    'non_development'  => [
                        'heading'      => '造成ある場合（開発にかからない工事）',
                        'subtitle'     => '造成工事に関する以下の設問について回答してください。'
                    ],
                    'development'      => '造成ある場合（開発工事）'
                ],
                'driveway'             => '私道がからむ場合'
            ],
            'label'                    => [
                'breakthrough'         => '減歩率',
                'effective_area'       => '有効面積',
                'loan'                 => '融資借入額',
                'brokerage_fee'        => '仕入仲介手数料',
                'resale_fee'           => '再販仲介手数料',
                'realistic_division'   => '区割りは建物が入る現実的な割り方になっていますか？',
                'building_type'        => '建物の種類は？',
                'asbestos'             => 'アスベストの有無',
                'cost_factor'          => '費用が高くなる要素は？',
                'water_access'         => '水道は何箇所引き込む予定か？',
                'location'             => '箇所',
                'road'                 => [
                    'type'             => '新たにつくる道路の種別は？',
                    'dimension'        => '新たにつくる道路範囲の面積は？',
                    'gutter'           => '側溝',
                    'gutter_length'    => '側溝の長さは？',
                    'embankment'       => '盛り土の土量はどのくらい見込んでいるか？'
                ],
                'retaining_wall'       => [
                    'construction'     => '擁壁工事',
                    'height'           => '擁壁高さ(m)',
                    'length'           => '擁壁長さ'
                ],
                'development'          => [
                    'cost'             => '造成費の目安'
                ],
                'driveway'             => [
                    'road_sharing'     => '道路持分は持っているか？',
                    'road_consent'     => '通行掘削同意書は取得できるか？'
                ]
            ],
            'option'                   => [
                'fee'                  => [
                    'expense'          => '支出',
                    'revenue'          => '収入',
                    'none'             => '無し'
                ],
                'sales_area'           => [ 
                    'single'           => '区画で売る予定の物件', 
                    'multi'            => '区画以上で売る予定の物件' 
                ],
                'demolition'           => [
                    'building'         => '建物解体工事を伴う',
                    'retaining_wall'   => '擁壁の解体工事を伴う'
                ],
                'construction'         => [
                    'none'             => '造成工事なし',
                    'development'      => '造成工事（開発工事）',
                    'non_development'  => '造成工事（非開発）',
                    'cost'             => [
                        'flat'         => '平坦地且つ特筆すべき点なし',
                        'one'          => '高低差が〜１m程度ある',
                        'two'          => '高低差が〜２m程度ある',
                        'above_two'    => '高低差が２m以上ある'
                    ],
                    'distant'          => 'インフラ本管が遠い'
                ],
                'driveway'             => '私道がからんでいる',
                'realistic_division'   => [
                    'yes'              => 'なっている',
                    'no'               => 'なっていない'
                ],
                'building_type'        => [
                    'wood'             => '木造',
                    'steel'            => '鉄骨'
                ],
                'asbestos'             => [
                    'yes'              => 'ある',
                    'no'               => 'なし'
                ],
                'cost_factor'          => [
                    'obstacles'        => '木や石、塀など多い',
                    'storeroom'        => '大きな物置',
                    'accessibility'    => '土地狭く重機入りにくい'
                ],
                'road'                 => [
                    'designated'       => '位置指定道路',
                    'private'          => '専用道路',
                    'none'             => '道路なし',
                    'width'            => '幅員',
                    'length'           => '長さ',
                    'gutter'           => [
                        'one_side'     => '片側',
                        'both_sides'   => '両側',
                        'none'         => 'なし'
                    ],
                    'embankment'       => [
                        'none'         => '盛土なし'
                    ],
                    'sharing'          => [
                        'yes'          => '持っている',
                        'no'           => '持っていない'
                    ],
                    'consent'          => [
                        'yes'          => 'できる',
                        'no'           => 'できない'
                    ]
                ],
                'retaining_wall'       => [
                    'construction'     => [
                        'yes'          => '擁壁を新設する',
                        'no'           => '擁壁を新設しない'
                    ],
                    'more'             => 'それ以上'
                ]
            ]
        ],
        'expense'                      => [
            'heading'                  => [
                'budget'               => '予算',
                'total'                => '決定総額',
                'registration'         => '登記関連費用',
                'finance'              => '融資関連費用',
                'tax'                  => '税金等',
                'construction'         => '工事関連費用',
                'survey'               => '測量関連費用',
                'other'                => 'その他'
            ],
            'label'                    => [
                'additional'           => '行追加',
                'auto'                 => '自',
                'loan'                 => [
                    'amount'           => '融資額',
                    'expected'         => '融資予定額'
                ],
                'tsubo_price'          => '坪単価',
                'settlement_date'      => '予定決済日',
                'settlement'           => [
                    'date'             => '決済日',
                    'schedule'         => '予定決済日'
                ],
                'purchase'             => [
                    'price'            => '仕入予定価格(物件原価予算)',
                    'commission'        => '仕入時仲介手数料'
                ],
                'registration'         => [
                    'total'            => '登記関連費用',
                    'ownership'        => '所有権移転登記',
                    'mortage'          => '抵当権設定登記',
                    'asset_tax'        => '固都税日割分',
                    'loss'             => '滅失登記'
                ],
                'finance'              => [
                    'total'            => '融資関連費用',
                    'interest'         => '金利負担',
                    'banking'          => '銀行手数料',
                    'stamp'            => '印紙(手形)',
                    'rate'             => '予定金利',
                    'rate_alt'         => '金利'
                ],
                'tax'                  => [
                    'total'            => '税金等',
                    'acquisition'      => '不動産取得税',
                    'acquisition_note' => '※不動産取得税は支出の部に反映されません',
                    'annual'           => '翌年固都税'
                ],
                'construction'         => [
                    'total'            => '工事関連費用',
                    'demolition'       => [
                        'building'     => '建物解体工事',
                        'wall'         => '擁壁解体工事'
                    ],
                    'pole_relocation'  => '電柱移設・撤去',
                    'plumbing'         => '水道・下水工事',
                    'embankment'       => '盛り土工事',
                    'retaining_wall'   => '擁壁工事',
                    'road'             => '道路工事',
                    'gutter'           => '側溝工事',
                    'workset'          => '造成工事',
                    'location'         => '位置指定申請費',
                    'commission'       => '開発委託費',
                    'cultural'         => '文化財調査費'
                ],
                'survey'               => [
                    'total'            => '測量関連費用',
                    'fixed'            => '確定測量',
                    'divisional'       => '分筆登記',
                    'boundry'          => '境界杭復元'
                ],
                'other'                => [
                    'total'            => 'その他',
                    'referral'         => '紹介料',
                    'eviction'         => '立ち退き料',
                    'water'            => '前払い水道加入金'
                ],
                'total'                => [
                    'budget'           => '総支出（予算）',
                    'amount'           => '総支出（決定総額）',
                    'tsubo'            => '坪単価'
                ]
            ],
            'option'                   => [
                'purchase'             => [
                    'commission'        => [
                        'income'       => '収',
                        'expense'      => '支',
                        'none'         => '無'
                    ]
                ]
            ]
        ],
        'sale'                         => [

        ]
    ]
    // ----------------------------------------------------------------------
];
