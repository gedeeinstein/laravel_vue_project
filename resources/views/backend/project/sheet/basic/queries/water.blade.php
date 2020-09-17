@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question_1')
        <div class="py-md-1">
            <span>A6.</span>
            <span>@lang("{$question}.water")</span>
        </div>
    @endslot

    @slot('choices_1')
        <div class="row mx-n2">
            @php $name = 'qna-water' @endphp

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.water" />
                    <label for="{{ "{$name}-yes" }}" class="text-uppercase fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-no" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.water" />
                    <label for="{{ "{$name}-no" }}" class="text-uppercase fs-12 noselect w-100">
                        <span>@lang("{$choices}.no")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot

    @slot('question_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.water">
                <span>@lang("{$question}.water_capacity")</span>
            </template>
        </transition>
    @endslot

    @slot('choices_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.water">
                <div class="mt-2 mt-md-0">

                    <div class="row mb-2">
                        @php $name = 'qna-water-supply' @endphp
                        <div class="col-lg-4 py-md-2">
                            <label class="fw-n" for="{{ $name }}">
                                <span>@lang("{$label}.water.supply")</span>
                            </label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <template>
                                    <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.question.water_supply_pipe" 
                                        :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative" 
                                        data-parsley-trigger="change focusout" data-parsley-no-focus
                                        :disabled="status.loading" placeholder="@lang("{$label}.water.supply")" />
                                </template>
                                <div class="input-group-append">
                                    <label class="input-group-text fs-14 px-2" for="{{ $name }}">@lang("{$label}.water.unit")</label>
                                </div>
                            </div>
                            <div class="form-result"></div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        @php $name = 'qna-water-meter' @endphp
                        <div class="col-lg-4 py-md-2">
                            <label class="fw-n" for="{{ $name }}">
                                <span>@lang("{$label}.water.meter")</span>
                            </label>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <template>
                                    <currency-input class="form-control" name="{{ $name }}" id="{{ $name }}" v-model="project.question.water_meter" 
                                        :currency="null" :precision="config.currency.precision" :allow-negative="config.currency.negative" 
                                        data-parsley-trigger="change focusout" data-parsley-no-focus
                                        :disabled="status.loading" placeholder="@lang("{$label}.water.meter")" />
                                </template>
                                <div class="input-group-append">
                                    <label class="input-group-text fs-14 px-2" for="{{ $name }}">@lang("{$label}.water.unit")</label>
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