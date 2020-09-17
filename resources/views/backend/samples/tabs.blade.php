@extends('backend._base.content_default')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">Sample Page</li>
    </ol>
@endsection

@section('content')
    <!-- Start - Vue Common Tabs -->
    <div :id="tabs.name" class="card card-default card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" :id="tabs.name+'-tab'" role="tablist">
                <li v-for="(item, index) in tabs.items" class="nav-item" :class="{ dropdown:item.dropdown }">
                    <a class="nav-link" :class="[ (index == 0) ? 'active' : '' ]" :id="item.name + '-tab'" data-toggle="pill" :href="'#' + item.name + '-section'" role="tab" :aria-controls="item.name + '-section'" aria-selected="true">
                        @{{ item.name }}
                    </a>
                    <span v-show="item.dropdown" class="dropdown-toggle" :id="'dropdown-' + item.name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                    <ul v-show="item.dropdown" :aria-labelledby="'dropdown-' + item.name" class="dropdown-menu border-0 shadow">
                        <li><a href="javascript:void(0)" @click="duplicateTabs(item)" class="dropdown-item">シートを複製</a></li>
                        <li><a href="javascript:void(0)" @click="removeTabs(index)" class="dropdown-item text-red">シートを削除</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" data-toggle="modal" :data-target="'#modal-tabs-' + tabs.name">追加 <i class="fas fa-plus-circle"></i></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" :id="tabs.name + '-tabContent'">
                <div v-for="(item, index) in tabs.items" class="tab-pane fade" :class="[ (index == 0) ? 'active show' : '' ]" :id="item.name + '-section'" role="tabpanel" :aria-labelledby="item.name + '-tab'">
                    <!-- Start - Tabs Content -->
                    
                    @{{ item.name }}

                    <!-- End - Tabs Content -->
                </div>
            </div>
        </div>
    </div>
    <!-- End - Vue Common Tabs -->

    <!-- Start - Modal Tabs -->
    <div class="modal fade" :id="'modal-tabs-' + tabs.name" aria-hidden="true" style="display: none;">
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
                        <input type="text" v-model="tabs.modal.input" class="form-control" placeholder="タブを追加">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">キャンセル</button>
                <button type="button" @click="addTabs" class="btn btn-sm btn-primary">追加 <i class="fas fa-plus-circle"></i></button>
            </div>
            </div>
        </div>
    </div>
    <!-- End - Modal Tabs -->


    <!-- Using Laravel Component -->
    {{-- @component('backend._components.common.tabs')
        @slot('tabs_id', 'tabs-1')
        @slot('tabs', array(
            array(  'tabs_name' => '仲介あり', 'dropdown'=> true),
            array(  'tabs_name' => '工事あり', 'dropdown'=> true)
        ))
    @endcomponent --}}
    
@endsection

@push('vue-scripts')
<script>
mixin = {
  data: function () {
    return {
        tabs: {
            name : 'tabs-default',
            items : [
                {
                    name : '仲介あり',
                    dropdown : true,
                },
                {
                    name : '工事あり',
                    dropdown : true,
                },
            ],
            modal: {
                input : ''
            }
        },
        form: {
            
        }
    }
  },
  methods: {
    addTabs: function() {
        if(this.tabs.modal.input != '')
        {
            this.tabs.items.push({
                name : this.tabs.modal.input,
                dropdown : true,
            })
            this.tabs.modal.input = '';
            $('#modal-tabs-'+this.tabs.name).modal('toggle');
        }
    },
    removeTabs: function(index) {
        this.tabs.items.splice(index, 1);
    },
    duplicateTabs: function(item) {
        this.tabs.items.push(item);
    }
  }
}
</script>
@endpush