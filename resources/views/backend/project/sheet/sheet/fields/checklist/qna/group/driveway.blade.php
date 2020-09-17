<div class="qna-group">
    <div class="fw-b mb-3 mb-md-2">
        <span>Q&A</span>
        <strong>@lang('project.sheet.checklist.group.driveway')</strong>
    </div>
    
    <!-- Road Sharing - Start -->
    <div class="row my-2">
        @include("{$checklist->driveway}.road-sharing")
    </div>
    <!-- Road Sharing - End -->

    <!-- Road Consent - Start -->
    <div class="row my-2">
        @include("{$checklist->driveway}.road-consent")
    </div>
    <!-- Road Consent - End -->

</div>      