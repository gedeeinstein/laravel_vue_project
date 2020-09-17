@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A14.</span>
            <span>@lang("{$question}.requirement")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-requirement' @endphp

            <div class="px-2 col-auto col-lg-6">
                @php $id = "{$name}-yes" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.contact_requirement" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.requirement.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-auto col-lg-6">
                @php $id = "{$name}-no" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.contact_requirement" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.requirement.no")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent