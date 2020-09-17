@component("{$basic->components}.query")
    @php
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
        $question = 'project.sheet.question';
    @endphp
    
    @slot('question_1')
        <div class="py-md-1">
            <span>A9.</span>
            <span>@lang("{$question}.obstructive_pole")</span>
        </div>
    @endslot

    @slot('choices_1')
        <div class="row mx-n2">
            @php $name = 'qna-obstructive-pole' @endphp

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-yes" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.telegraph_pole_hindrance" />
                    <label for="{{ "{$name}-yes" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="px-2 col-auto col-lg-4">
                <div class="icheck-cyan">
                    <input type="radio" id="{{ "{$name}-no" }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.telegraph_pole_hindrance" />
                    <label for="{{ "{$name}-no" }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.no")</span>
                    </label>
                </div>
            </div>

        </div>
    @endslot

    @slot('question_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.telegraph_pole_hindrance">
                <span>@lang("{$question}.water_capacity")</span>
            </template>
        </transition>
    @endslot

    @slot('choices_2')
        <transition name="paste-in">
            <template v-if="1 === project.question.telegraph_pole_hindrance">
                <div class="mt-2 mt-md-0">
                    <div class="row mx-n2">
                        @php $name = 'qna-obstructive-pole-move' @endphp

                        <div class="px-2 col-sm-auto col-lg-4">
                            @php $id = "{$name}-movable" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="1" v-model="project.question.telegraph_pole_move" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.pole.movable")</span>
                                </label>
                            </div>
                        </div>
            
                        <div class="px-2 col-sm-auto col-lg-4">
                            @php $id = "{$name}-not-movable" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="2" v-model="project.question.telegraph_pole_move" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.pole.not_movable")</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-2 col-sm-auto col-lg-4">
                            @php $id = "{$name}-unknown" @endphp
                            <div class="icheck-cyan">
                                <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :value="3" v-model="project.question.telegraph_pole_move" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.pole.unknown")</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-2 col-sm-auto">
                            @php 
                                $id = "{$name}-expensive";
                                $name = 'qna-obstructive-pole-expensive';
                            @endphp
                            <div class="icheck-cyan">
                                <input type="checkbox" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                    :disabled="status.loading" :true-value="1" :false-value="0" v-model="project.question.telegraph_pole_high_cost" />
                                <label for="{{ $id }}" class="fs-12 noselect w-100">
                                    <span>@lang("{$choices}.pole.expensive")</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </transition>
    @endslot
@endcomponent