@php
    $basic = (object) array(
        'fields' => 'backend.project.sheet.basic.fields',
        'queries' => 'backend.project.sheet.basic.queries',
        'includes' => 'backend.project.sheet.basic.includes',
        'components' => 'backend.project.sheet.components.basic'
    );
@endphp

<div class="accordion">
    <div class="card mb-0">
        <div class="card-header p-2" id="basic-info">
            <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#accordion-basic-info" aria-expanded="true"
                aria-controls="collapse-basic-info">
                <span>@lang('project.sheet.tabs.basic.info')</span>
            </button>
        </div>

        <div id="accordion-basic-info" class="collapse show" aria-labelledby="basic-info">
            <div class="card-body">
                @include("{$include->basic}.info")
            </div>
        </div>
    </div>
    
    <div class="card mb-0">
        <div class="card-header p-2" id="basic-qna">
            <button type="button" class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#accordion-basic-qna"
                aria-expanded="false" aria-controls="collapse-basic-qna">
            <span>@lang('project.sheet.tabs.basic.qna')</span>
        </button>
    </div>
        <template v-if="project.question">
            <div id="accordion-basic-qna" class="collapse" aria-labelledby="basic-qna">
                <div class="card-body p-2 p-md-3">
                    @include("{$include->basic}.qna")
                </div>
            </div>
        </template>
    </div>
</div>