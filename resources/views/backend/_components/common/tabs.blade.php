@php
    $tabs_id = $tabs_id ?? 'tabs-default';
    $tabs = empty($tabs) ? [] : json_decode(json_encode($tabs));
@endphp
<div id="{{ $tabs_id }}" class="card card-default card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="{{ $tabs_id }}-tab" role="tablist">
            @forelse ($tabs as $tab)
            @php $tab->dropdown  = $tab->dropdown ?? false @endphp
                <li class="nav-item {{ $tab->dropdown ? 'dropdown' : '' }}">
                    <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="{{ $tab->tabs_name }}-tab" data-toggle="pill" href="#{{ $tab->tabs_name }}-section" role="tab" aria-controls="{{ $tab->tabs_name }}-section" aria-selected="true">
                        {{ $tab->tabs_name }}
                    </a>
                    @if ($tab->dropdown)
                        <span class="dropdown-toggle" id="dropdown-{{ $tab->tabs_name }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                        <ul aria-labelledby="dropdown-{{ $tab->tabs_name }}" class="dropdown-menu border-0 shadow">
                            <li><a href="#" class="dropdown-item">シートを複製</a></li>
                            <li><a href="#" class="dropdown-item text-red">シートを削除</a></li>
                        </ul>
                    @endif
                </li>
                @if ($loop->last)
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-tabs-{{ $tabs_id }}">追加 <i class="fas fa-plus-circle"></i></a>
                    </li>
                @endif
            @empty
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-tabs-{{ $tabs_id }}">追加 <i class="fas fa-plus-circle"></i></a>
                </li>
            @endforelse
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="{{ $tabs_id }}-tabContent">
            @foreach ($tabs as $tab)
                <div class="tab-pane fade {{ $loop->first ? 'active show' : ''}}" id="{{ $tab->tabs_name }}-section" role="tabpanel" aria-labelledby="{{ $tab->tabs_name }}-tab">
                    {{ $tab->tabs_name }}
                    {{ $slot }}
                </div>
            @endforeach
        </div>
    </div>
    <!-- /.card -->
</div>

<!-- modal-dialog -->
<div class="modal fade" id="modal-tabs-{{ $tabs_id }}" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">追加</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form role="form">
                <div class="form-group">
                    <label for="input-tabs">タブを追加</label>
                    <input type="text" class="form-control" id="input-tabs-{{ $tabs_id }}" placeholder="タブを追加">
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">キャンセル</button>
            <button type="button" @click="addRow" class="btn btn-sm btn-primary">追加 <i class="fas fa-plus-circle"></i></button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal-dialog -->