@component("{$basic->components}.query")
    @php $choices = 'project.sheet.choices' @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A2.</span>
            <span>@lang('project.sheet.question.cultural_property')</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-cultural-property' @endphp

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.cultural_property" />
                    <label for="{{ "{$name}-yes" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.included")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-no" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.cultural_property" />
                    <label for="{{ "{$name}-no" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.not_included")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-unconfirmed" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="project.question.cultural_property" />
                    <label for="{{ "{$name}-unconfirmed" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.unconfirmed")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent