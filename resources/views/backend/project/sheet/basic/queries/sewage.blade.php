@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A7.</span>
            <span>@lang("{$question}.sewage")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-sewage' @endphp

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.sewage" />
                    <label for="{{ "{$name}-yes" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-no" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.sewage" />
                    <label for="{{ "{$name}-no" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.no")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent