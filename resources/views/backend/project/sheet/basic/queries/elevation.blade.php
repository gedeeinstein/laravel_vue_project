@component("{$basic->components}.query")
    @php $choices = 'project.sheet.choices' @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A4.</span>
            <span>@lang('project.sheet.question.elevation')</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            @php $name = 'qna-elevation' @endphp

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-under-half" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.difference_in_height" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.elevation.under_half")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-half-one" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.difference_in_height" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.elevation.half_one")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-one-two" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="project.question.difference_in_height" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.elevation.one_two")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-above-two" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="4" v-model="project.question.difference_in_height" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.elevation.above_two")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-unconfirmed" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="5" v-model="project.question.difference_in_height" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.unconfirmed")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent