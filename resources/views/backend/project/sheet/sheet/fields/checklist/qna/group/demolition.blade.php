<div class="qna-group">
    <div class="fw-b mb-3 mb-md-2">
        <span>Q&A</span>
        <span>@lang('project.sheet.checklist.group.demolition')</span>
    </div>
    
    <!-- Building type - Start -->
    <div class="row my-2">
        @include("{$checklist->demolition}.building-type")
    </div>
    <!-- Building type - End -->

    <!-- Asbestos - Start -->
    <div class="row my-2">
        @include("{$checklist->demolition}.asbestos")
    </div>
    <!-- Asbestos - End -->

    <!-- Cost factor - Start -->
    <div class="row my-2">
        @include("{$checklist->demolition}.cost-factor")
    </div>
    <!-- Cost factor - End -->

</div>

