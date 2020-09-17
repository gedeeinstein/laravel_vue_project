@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A11.</span>
            <span>@lang("{$question}.positive")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">

            <div class="px-2 col-lg-6">
                @php $name = "qna-positive-popular-area" @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.plus_popular" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.positive.popular")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-lg-6">
                @php $name = "qna-positive-high-value" @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.plus_high_price_sale" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.positive.value")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-12 my-2">
                <input type="text" class="form-control" name="qna-positive-other" v-model="project.question.plus_others" 
                    placeholder="@lang("{$label}.other")" :disabled="status.loading" />
            </div>

        </div>
    @endslot
@endcomponent