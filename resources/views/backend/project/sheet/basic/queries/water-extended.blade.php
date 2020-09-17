@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A8.</span>
            <span>@lang("{$question}.water_extended")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">
            
            <div class="px-2 col-sm-auto col-lg-4">
                @php $name = 'qna-private-pipe' @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.private_pipe" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.water.private_pipe")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                @php $name = 'qna-crossing-others' @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.cross_other_land" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.water.crossing_others")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-lg-4">
                @php $name = 'qna-insufficient' @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.insufficient_capacity" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.water.insufficient")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot
@endcomponent