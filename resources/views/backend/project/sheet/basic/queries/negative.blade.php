@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A12.</span>
            <span>@lang("{$question}.negative")</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row mx-n2">

            <div class="px-2 col-lg-6">
                @php $name = "qna-negative-sale" @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.plus_low_price_sale" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.negative.sale")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-lg-6">
                @php $name = "qna-negative-landslide" @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.minus_landslide_etc" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.negative.landslide")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-lg-6">
                @php $name = "qna-negative-defect" @endphp
                <div class="icheck-cyan">
                    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.minus_psychological_defect" />
                    <label for="{{ $name }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.negative.defect")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-12 my-2">
                <input type="text" class="form-control" name="qna-negative-other" v-model="project.question.minus_others" 
                    placeholder="@lang("{$label}.other")" :disabled="status.loading" />
            </div>

        </div>
    @endslot
@endcomponent