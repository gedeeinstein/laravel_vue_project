<div class="row mb-3">

    <!-- Plan title - Start -->
    <div class="col-md-5">
        <div class="form-group">
            <template v-for="name in [ prefix + 'name' ]">
                <label :for="name">プラン名 作成者</label>

                <!-- Plan name - Start -->
                @component("{$component->preset}.text")
                    @slot( 'disabled', 'isDisabled' )
                    @slot( 'name', 'name' )
                    @slot( 'model', 'entry.plan_name' )
                @endcomponent
                <!-- Plan name - End -->
        
                <!-- Time stamp & creator information - Start -->
                <div class="timestamp mt-2">
                    <div class="row flex-md-nowrap mx-n1">

                        <!-- Plan created time - Start -->
                        <template v-if="entry.created_at">
                            <div class="px-1 col-auto">
                                <span v-if="'pending' == entry.created_at">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>@lang('project.sheet.status.loading')</span>
                                </span>
                                <span v-else>@{{ entry.created_at | moment( format.created ) }}</span>
                            </div>
                        </template>
                        <!-- Plan created time - End -->

                        <!-- Plan creator - Start -->
                        <template v-if="entry.plan_creator">
                            <div class="px-1 col-auto">
                                <span>@{{ entry.plan_creator }}</span>
                            </div>
                        </template>
                        <!-- Plan creator - End -->

                    </div>
                </div>
                <!-- Time stamp & creator information - End -->
        
            </template>
        </div>
    </div>
    <!-- Plan title - End -->

    <!-- Plan memo - Start -->
    <div class="col-md-5">
        <template v-for="name in [ prefix + 'memo' ]">
            <div class="form-group">
                <label :for="name">メモ</label>
        
                @component("{$component->common}.textarea")
                    @slot( 'rows', 2 )
                    @slot( 'model', 'entry.plan_memo' )
                    @slot( 'name', 'name' )
                @endcomponent
        
            </div>
        </template>
    </div>
    <!-- Plan memo - End -->

    <!-- Export checkbox - Start -->
    <div class="col-md-2 pt-md-4">
        @component("{$component->preset}.checkbox")
            @slot( 'label', '出力' ))
            @slot( 'model', 'entry.export' )
            @slot( 'name', "prefix + 'export'" )
            @slot( 'change', 'markExport')
            @slot( 'disabled', 'isDisabled' ))
        @endcomponent
    </div>
    <!-- Export checkbox - End -->

</div>