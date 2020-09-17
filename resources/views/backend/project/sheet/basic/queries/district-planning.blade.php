@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question_1')
        <div class="py-md-1">
            <span>A3.</span>
            <span>@lang("{$question}.district_planning")</span>
        </div>
    @endslot

    @slot('choices_1')
        <div class="row mx-n2">
            @php $name = 'qna-district-planning' @endphp

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.district_planning" />
                    <label for="{{ "{$name}-yes" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-no" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.district_planning" />
                    <label for="{{ "{$name}-no" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.no")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot

    @slot('question_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.district_planning">
                <span>@lang("{$question}.building_restriction")</span>
            </template>
        </transition>
    @endslot

    @slot('choices_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.district_planning">
                <div class="mt-2 mt-md-0">

                    <div class="row mb-2">
                        @php $name = 'qna-building-restriction' @endphp
                        <div class="col-lg-4">
                            <label class="fw-n" for="{{ $name }}">
                                <span>@lang("{$label}.building_restriction")</span>
                            </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="{{ $name }}" name="{{ $name }}" v-model="project.question.building_use_restrictions" 
                                :disabled="status.loading" placeholder="@lang("{$label}.building_restriction")"
                                data-parsley-trigger="change focusout" data-parsley-no-focus />
                            <div class="form-result"></div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        @php $name = 'qna-minimum-area' @endphp
                        <div class="col-lg-4">
                            <label class="fw-n" for="{{ $name }}">
                                <span>@lang("{$label}.minimum_area")</span>
                            </label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <template>
                                    <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.question.minimum_area" 
                                        :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative" 
                                        data-parsley-trigger="change focusout" data-parsley-no-focus 
                                        :disabled="status.loading" placeholder="@lang("{$label}.minimum_area")" />
                                </template>
                                <div class="input-group-append">
                                    <label class="input-group-text fs-14 px-2" for="{{ $name }}">m<sup>2</sup></label>
                                </div>
                            </div>
                            <div class="form-result"></div>
                        </div>
                    </div>

                </div>
            </template>
        </transition>
    @endslot
@endcomponent