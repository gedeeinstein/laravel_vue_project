@php
    $role = (object) array(
        'admin'      => 'global_admin',
        'general'    => 'general',
        'ledger'     => 'ledger_editor',
        'manager'    => 'registration_manager',
        'accountant' => 'accountant',
        'accounting' => 'accounting_firm',
        'agent'      => 'agent',
        'restricted' => 'no_access'
    );

    $username = auth()->user()->full_kana_name;
    $login    = auth()->user()->user_role->name;
@endphp
<nav class="main-header navbar navbar-expand-md navbar-light navbar-dark">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a class=" mr1-0 mr1-md-2" href="{{ url('/') }}" aria-label="Port">
                <i class="fas fa-anchor"></i>
            </a>
        </div>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <!-- A. Purchasing -->
            <li class="nav-item dropdown">
                <a id="dropdown-仕入" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                    <i class="fas fa-hand-holding-usd"></i> 仕入
                </a>
                <ul aria-labelledby="dropdown-仕入" class="dropdown-menu border-0 shadow">
                    <li><a href="{{ route('project.list.index') }}" class="dropdown-item">仕入(一覧)</a></li>
                    @isset($project->url)
                        <li class="dropdown-divider"></li>
                        <li><a href="{{ $project->url->sheet }}" class="dropdown-item">PJシート＆チェックリスト</a></li>
                        <li><a href="{{ $project->url->assistA }}" class="dropdown-item">アシストA</a></li>
                        <li><a href="{{ $project->url->assistB }}" class="dropdown-item">アシストB</a></li>
                        <li><a href="{{ $project->url->purchaseSale }}" class="dropdown-item">仕入営業</a></li>
                        <li><a href="{{ $project->url->purchase }}" class="dropdown-item">仕入買付</a></li>
                        @isset($purchase_target->id)
                            <li><a href="{{ route( 'project.purchase.create', ['project' => $project->id, 'purchase_target' => $purchase_target->id] ) }}" class="dropdown-item">仕入買付作成</a></li>
                            <li><a href="{{ route( 'project.purchase.response', ['project' => $project->id, 'purchase_target' => $purchase_target->id] ) }}" class="dropdown-item">仕入買付応否入力</a></li>
                        @endisset
                        <li><a href="{{ $project->url->purchaseContract }}" class="dropdown-item">仕入契約</a></li>
                        <li><a href="{{ $project->url->contractCreate }}" class="dropdown-item">仕入契約作成</a></li>
                        <li><a href="{{ $project->url->expense }}" class="dropdown-item">支出の部</a></li>
                        <li><a href="{{ $project->url->ledger }}" class="dropdown-item">取引台帳</a></li>
                    @endisset
                </ul>
            </li>
            <!-- End - A. Purchasing -->

            <!-- B. Master -->
            @if( $login == $role->admin )
                <li class="nav-item dropdown">
                    <a id="dropdown-マスター" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="fas fa-key"></i> マスター
                    </a>
                    <ul aria-labelledby="dropdown-マスター" class="dropdown-menu border-0 shadow">
                        <li><a href="#" class="dropdown-item">マスター(一覧)</a></li>
                        <li class="dropdown-divider"></li>
                        @isset($project->id)
                            <li><a href="#" class="dropdown-item">マスター設定</a></li>
                            <li><a href="{{ route('master.finance', $project->id) }}" class="dropdown-item">融資・入出金</a></li>
                            <li><a href="#" class="dropdown-item">基本データ</a></li>
                            <li><a href="#" class="dropdown-item">事業清算(区画一覧)</a></li>
                            <li><a href="#" class="dropdown-item">区画精算</a></li>
                        @endisset
                    </ul>
                </li>
            @endif
            <!-- End - B. Master -->

            <!-- C.Sales (display of each section) -->
            <li class="nav-item dropdown">
                <a id="dropdown-販売（各区画表示）" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                    <i class="fas fa-cash-register"></i> 販売（各区画表示）
                </a>
                <ul aria-labelledby="dropdown-販売（各区画表示）" class="dropdown-menu border-0 shadow">
                    <li><a href="#" class="dropdown-item">販売（各区画一覧）</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="#" class="dropdown-item">販売活動</a></li>
                    <li><a href="#" class="dropdown-item">販売契約</a></li>
                    <li><a href="#" class="dropdown-item">販売契約作成</a></li>
                </ul>
            </li>
            <!-- End - C.Sales (display of each section) -->

            <!-- D.Request Review -->
            @if( $login == $role->admin )
                <li class="nav-item dropdown">
                    <a id="dropdown-リクエスト審査" href="{{ route('project.inspection.index') }}" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">

                        <i class="fas fa-pen-square"></i> 
                        <span>リクエスト審査</span>
                        
                        @if( !empty( $preset->inspection->unapproved ))
                            @php $count = $preset->inspection->unapproved->count() @endphp
                            @if( $count )
                                <span class="badge badge-pill badge-warning">{{ $count }}</span>
                            @endif
                        @endif

                    </a>
                    <ul aria-labelledby="dropdown-リクエスト審査" class="dropdown-menu border-0 shadow">
                    <li><a href="{{ route('project.inspection.index') }}" class="dropdown-item">リクエスト審査</a></li>
                    </ul>
                </li>
            @endif
            <!-- End - D.Request Review -->

            <!-- Data Management -->
            @php $allowed = collect( $role )->except([ 'accounting', 'agent', 'restricted' ]) @endphp
            @if( $allowed->contains( $login ))
                <li class="nav-item dropdown">
                    <a id="dropdown-データ管理" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="fas fa-database"></i> データ管理
                    </a>
                    <ul aria-labelledby="dropdown-データ管理" class="dropdown-menu border-0 shadow">

                        <li><a href="{{ route('company.index') }}" class="dropdown-item">会社・個人管理</a></li>
                        <li><a href="{{ route('qamanager.index') }}" class="dropdown-item">チェックリスト管理</a></li>
                        <li><a href="{{ route('qamanager-category.index') }}" class="dropdown-item">チェックリストカテゴリ管理</a></li>

                        @if( $login == $role->admin )
                            <li><a href="{{ route('project.sheet-manager') }}" class="dropdown-item">PJシート管理</a></li>
                            <li class="dropdown-submenu dropdown-hover">
                                <a id="masters" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">マスターデータ</a>
                                <ul aria-labelledby="masters" class="dropdown-menu border-0 shadow">
                                    <li>
                                        <a tabindex="0" href="{{ route('master.values.index') }}" class="dropdown-item">@lang('label.master_values')</a>
                                        <a tabindex="1" href="{{ route('master.area.index', '01') }}" class="dropdown-item">@lang('label.master_region')</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="{{ route('logs.index') }}" class="dropdown-item">ログ閲覧</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            <!-- End - Data Management -->
        </ul>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto" style="float:right;">
            <li>
                <a class="nav-link" href="{{ route('logout') }}"><span>{{ $username }} <i class="fas fa-sign-out-alt mx-1"></i> @lang('label.$nav.logout')</span></a>
            </li>
        </ul>
        <!-- End - Right navbar links -->
    </div>
</nav>
