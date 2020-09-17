<?php

return [

    /*
    |--------------------------------------------------------------------------
    | language Label for dashboard
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during dashboard for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    // common words
    /** Login                    = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
    'enterEmailAddress'          => 'メールアドレス',
    'enterPassword'              => 'パスワード',
    'remember'                   => 'ログイン状態を保持する',
    'login'                      => 'ログイン',
    'dashboard'                  => 'ダッシュボード',
    'superAdmin'                 => '特権管理者',
    'admin'                      => '管理者',
    'user'                       => 'ユーザー',
    'company'                    => '会社',
    'createNew'                  => '新規作成',
    'list'                       => '一覧',
    'add'                        => '新規作成',
    'edit'                       => '編集',
    'required'                   => '必須',
    'optional'                   => '任意',
    'update'                     => '更新',
    'password'                   => 'パスワード',
    'showPassword'               => 'パスワードを表示',
    'change'                     => '変更',
    'register'                   => '登録',
    'search'                     => '検索',
    'all'                        => '全部',
    'delete'                     => '削除',
    'adminloginscreen'           => '管理者ログイン画面',
    'newPassword'                => '新しいパスワードを入力してください',
    'choosePasswordLength'       => '最小8文字のパスワードを作成してください。',
    'updatePasswordSentence'     => 'パスワードを更新したい場合は、変更ボタンを選択してください。',
    'jsConfirmDeleteData'        => 'このデータを削除しますか？',
    'jsInfoDeletedData'          => 'データが削除されました！',
    'jsSorry'                    => 'データの削除は失敗しました。',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'success_create_message'     => '入力した内容を保存しました。',
    'failed_create_message'      => '入力した内容を保存できませんでした。',
    'success_update_message'     => '編集した内容を保存しました。',
    'failed_update_message'      => '編集した内容を保存できませんでした。',
    'success_delete_message'     => '対象のデータを削除しました。',
    'failed_delete_message'      => '対象のデータを削除できませんでした。',
    'common_error_message'       => 'エラーが発生しました',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'confirm_common'             => 'このまま続けますか？',
    'confirm_create'             => '追加します。よろしいですか？',
    'confirm_edit'               => '変更を反映します。よろしいですか？',
    'confirm_delete'             => 'データを削除します。よろしいですか？',
    'confirm_remove_control'     => '項目を削除します。入力されている内容は元に戻りませんが、よろしいですか？',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'report_downloaded'          => 'レポートファイルのダウンロードは完了しました。',
    'report_failed'              => 'レポートのダウンロードは失敗しました。',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'approval_updated'           => 'プロジェクトの承認が更新されました',
    'approval_requested'         => 'プロジェクトの承認が要求されました',
    'approval_failed'            => 'プロジェクトの承認を更新できませんでした',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'name'                       => 'お名前',
    'name_kana'                  => 'お名前（カナ）',
    'email'                      => 'メールアドレス',
    'admin_role_id'              => '管理者ロールID',
    'email_verified_at'          => 'メール確認済み日',
    'post_code'                  => '郵便番号',
    'address'                    => '住所',
    'phone'                      => '電話番号',
    'company_name'               => '会社名',
    'company_name_kana'          => '会社名 （カナ）',
    'post_code'                  => '郵便番号',
    /**                          = = = = = = = = = = = = = = = = = = = = = = = = = = = = ======= */
    'created_at'                 => '作成日',
    'update_at'                  => '更新日',
    'action'                     => '編集',
    /** Login History            = = = = = = = = = = = = = = = = = = = = = = */
    'historyLog'                 => '管理者ログイン履歴',
    'admin_id'                   => '管理者ID',
    'activity'                   => 'アクティビティ',
    'detail'                     => '詳細',
    'ip'                         => 'IPアドレス',
    'last_access'                => '最終アクセス',
    'IForgotMyPassword'          => 'パスワードをお忘れの方',
    'language'                   => '言語',
    /** News                     = = = = = = = = = = = = = = = = = = = = = = */
    'news'                       => 'ニュース',
    'title'                      => 'タイトル',
    'body'                       => '内容',
    'image'                      => '画像',
    'publish_date'               => '発行日',
    'status'                     => 'ステータス',
    'last_update'                => '最終更新日',
    /** Log Acitivity            = = = = = = = = = = = = = = = = = = = = = = */
    'log'                        => 'ログ',
    'log_activity'               => 'ログイン履歴',
    'access_time'                => '実行日時',
    'userloginscreen'            => 'ユーザーログイン画面',
    'master_values'              => 'マスタデータ',
    'master_region'              => '地域マスタ',
    'success'                    => '成功',
    'error'                      => '失敗',
    '$nav'                       => [
        'logout'                 => 'ログアウト'
    ],

    '$login'                     => [
        'admin'                  => [
            'meta'               => [
                'title'          => '管理者ログインページ'
            ]
        ],
        'user'                   => [
            'meta'               => [
                'title'          => 'ユーザーログインページ'
            ]
        ],
        'reset'                  => [
            'title'              => 'パスワードのリセット',
            'forgot'             => 'パスワードをお忘れの方',
            'request'            => 'リセットリンクを送信',
            'password'           => '新しいパスワードをリクエスト',
            'new'                => '新規会員登録',
        ],
        'confirm'                => [
            'title'              => 'パスワードを認証する',
            'message'            => '続行する前にパスワードを確認してください。',
            'submit'             => 'パスワードを認証する',
            'forgot'             => 'パスワードを忘れましたか？'
        ],
        'label'                  => [
            'name'               => '名前',
            'email'              => 'メールアドレス',
            'password'           => 'パスワード',
            'confirm'            => 'パスワードを認証する'
        ]
    ],

    '$log'                       => [
        'user'                   => 'ログインユーザー',
        'email'                  => 'メールアドレス',
        'activity'               => 'ステータス',
        'ip'                     => 'IPアドレス',
        'time'                   => '日時'
    ],

    'key'                        => 'キー',
    'type'                       => 'タイプ',
    'value'                      => '値',
    'sort'                       => 'ソート',

    'prefecture'                 => '都道府県',
    'prefecture_code'            => '都道府県コード',
    'group_code'                 => '団体コード',
    'name_alt'                   => '名前',
    'name_kana'                  => 'フリガナ',
    'order'                      => '並び順',
    'designated_city'            => '政令指定都市データ',
    'enabled'                    => '有効化',

    'individual'                 => '個人',
    '$company'                   => [
        'individual'             => '個人',
        'edit'                   => '会社情報編集',
        'list'                   => '会社一覧',
        'tab'                    => [
            'about'              => '会社情報',
            'users'              => 'ユーザー'
        ],
        'type'                   => [
            'label'              => '種類',
            'group'              => '自社グループ',
            'agent'              => '不動産業者',
            'contractor'         => '工事業者',
            'builder'            => 'ハウスメーカー',
            'profession'         => '士業',
            'advisor'            => '顧問会計事務所',
            'bank'               => '銀行',
            'other'              => 'その他'
        ],
        'form'                   => [
            'header'             => [
                'bank'           => '銀行情報',
                'agent'          => '不動産業者情報',
                'group'          => '自社グループ情報',
                'account'        => '銀行口座情報',
                'borrower'       => '借入先銀行情報'
            ],
            'label'              => [
                'name'           => '名称',
                'name_kana'      => '名称（カナ）',
                'admin'          => '管理者',
                'type'           => '種別',
                'name_abbr'      => '名称（略称）',
                'store'          => '店名',
                'abbr'           => '略称',
                'main'           => [
                    'address'    => '主たる事務所（所在地）',
                    'phone'      => '主たる事務所（TEL）'
                ],
                'representative' => '代表者名',
                'license'        => [
                    'number'     => '免許番号',
                    'date'       => '免許年月日',
                    'no'         => '第'
                ],
                'office'         => [
                    'label'      => '事務所情報',
                    'name'       => '事務所情報（事務所名）',
                    'address'    => '事務所情報（所在）',
                    'number'     => '事務所情報（電話）'
                ],
                'association'    => '保証協会',
                'depositary'     => [
                    'name'       => '供託所名',
                    'address'    => '供託所所在'
                ],
                'account'        => [
                    'bank'       => '銀行名',
                    'type'       => '口座種別',
                    'number'     => '口座番号'
                ],
                'borrower'        => [
                    'bank'       => '銀行名',
                    'loan'       => '融資金額枠'
                ]
            ],
            'button'             => [
                'delete'         => '削除',
                'add'            => '追加',
                'copy'           => 'コピー'
            ],
            'phrase'             => [
                'delete'         => [
                    'confirm'    => '続行しますか？'
                ],
                'update'         => [
                    'success'    => '変更が保存されました！'
                ]
            ],
            'account'            => [
                'express'        => '当座',
                'regular'        => '普通'
            ]
        ]
    ],
    '$user'                      => [
        'list'                   => 'ユーザー',
        'add'                    => 'ユーザーを追加'
    ],
    'qamanager'                  => [
        'list'                   => '追加基本Q&A管理(一覧）',
        'category_index'         => '追加基本Q&A管理(カテゴリ）',
        'index'                  => '追加基本Q&A管理',
        'category'               => 'カテゴリ',
        'categorymm'             => 'カテゴリ管理',
        'question'               => '設問',
        'input_type'             => '入力タイプ',
        'choices'                => '選択肢',
        'status'                 => '状態',
        'order'                  => '注文',
        'name'                   => '名称'
    ],
];
