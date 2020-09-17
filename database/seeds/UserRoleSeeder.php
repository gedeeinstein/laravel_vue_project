<?php

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(){
        $admin = new UserRole();
        $admin->insert([
            [ 'name' => 'no_access',            'label' => 'ログインを許可しない' ],
            [ 'name' => 'general',              'label' => '一般' ],
            [ 'name' => 'agent',                'label' => 'エージェント' ],
            [ 'name' => 'ledger_editor',        'label' => '台帳編集者' ],
            [ 'name' => 'registration_manager', 'label' => '登記管理責任者' ],
            [ 'name' => 'global_admin',         'label' => '全体管理者' ],
            [ 'name' => 'accounting_firm',      'label' => '会計事務所' ],
            [ 'name' => 'accountant',           'label' => '経理担当者' ]
        ]);
    }
}
