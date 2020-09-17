@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question_1')
        <div class="py-md-1">
            <span>A13.</span>
            <span>@lang("{$question}.survey")</span>
        </div>
    @endslot

    @slot('choices_1')
        <div class="row mx-n2">
            @php $name = 'qna-survey' @endphp

            <div class="px-2 col-sm-auto col-md-6">
                @php $id = "{$name}-sellers" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.fixed_survey" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.survey.sellers")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-md-6">
                @php $id = "{$name}-buyers" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.fixed_survey" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.survey.buyers")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-md-6">
                @php $id = "{$name}-completed" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="3" v-model="project.question.fixed_survey" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.survey.completed")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-sm-auto col-md-6">
                @php $id = "{$name}-not-implemented" @endphp
                <div class="icheck-cyan">
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="4" v-model="project.question.fixed_survey" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.survey.not_implemented")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot

    @slot('question_2')
        <transition name="paste-in">
            <template v-if="3 === project.question.fixed_survey">
                <div class="py-md-2">
                    <span>@lang("{$question}.survey_season")</span>
                </div>
            </template>
        </transition>
    @endslot

    @slot('choices_2')
        <transition name="paste-in">
            <template v-if="3 === project.question.fixed_survey">
                <div class="mt-2 mt-md-0">
                    <div class="row mx-n2">
                        @php $name = 'qna-survey-season' @endphp

                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-after-earthquake" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="1" v-model="project.question.fixed_survey_season" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.season.after_earthquake")</span>
                                </label>
                            </div>
                        </div>
            
                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-before-heisei" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="2" v-model="project.question.fixed_survey_season" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.season.before_heisei")</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-before-showa" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="3" v-model="project.question.fixed_survey_season" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.season.before_showa")</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-unconfirmed" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="4" v-model="project.question.fixed_survey_season" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.season.unconfirmed")</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </transition>
    @endslot

    @slot('question_3')
        <transition name="paste-in">
            <template v-if="4 === project.question.fixed_survey">
                <div class="py-md-2">
                    <span>@lang("{$question}.survey_reason")</span>
                </div>
            </template>
        </transition>
    @endslot

    @slot('choices_3')
        <transition name="paste-in">
            <template v-if="4 === project.question.fixed_survey">
                <div class="mt-2 mt-md-0">
                    <div class="row mx-n2">
                        @php $name = 'qna-survey-reason' @endphp

                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-restoration" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="1" v-model="project.question.fixed_survey_reason" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.reason.restoration")</span>
                                </label>
                            </div>
                        </div>
            
                        <div class="px-2 col-sm-auto col-md-6">
                            @php $id = "{$name}-not-required" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="2" v-model="project.question.fixed_survey_reason" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.survey.reason.not_required")</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </transition>
    @endslot
@endcomponent