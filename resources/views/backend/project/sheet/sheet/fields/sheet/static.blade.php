<div class="row flex-md-nowrap mx-n1">
    <div class="px-1 col-auto" v-if="sheet.created_at">
        <span v-if="'pending' == sheet.created_at">
            <i class="fas fa-spinner fa-spin"></i>
            <span>@lang('project.sheet.status.loading')</span>
        </span>
        <span v-else>@{{ sheet.created_at | moment( config.created.format ) }}</span>
    </div>
    <div class="px-1 col-auto" v-if="sheet.creator_name">
        <span>@{{ sheet.creator_name }}</span>
    </div>
</div>