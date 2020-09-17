@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A10.</span>
            <span>@lang("{$question}.road")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-road' @endphp

            <div class="px-2 col-sm-auto col-lg-4">
                @php $id = "{$name}-under-four" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.width_of_front_road" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.road.under_four")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                @php $id = "{$name}-four-six" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.width_of_front_road" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.road.four_six")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                @php $id = "{$name}-above-six" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="project.question.width_of_front_road" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.road.above_six")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent