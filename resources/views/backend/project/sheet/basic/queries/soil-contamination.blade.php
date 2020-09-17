@component("{$basic->components}.query")
    @php $choices = 'project.sheet.choices' @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A1.</span>
            <span>@lang('project.sheet.question.soil_contamination')</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-soil-contamination' @endphp

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.soil_contamination" />
                    <label for="{{ "{$name}-yes" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-almost-none" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.soil_contamination" />
                    <label for="{{ "{$name}-almost-none" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.almost_none")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-unknown" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="project.question.soil_contamination" />
                    <label for="{{ "{$name}-unknown" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.unknown")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent