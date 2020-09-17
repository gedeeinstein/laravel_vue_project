@component("{$basic->components}.query")
    @php 
        $label = 'project.sheet.label';
        $choices = 'project.sheet.choices';
    @endphp
    
    @slot('question')
        <div class="py-md-1">
            <span>A5.</span>
            <span>@lang('project.sheet.question.retaining_wall')</span>
        </div>
    @endslot

    @slot('choices')
        <div class="row">
            @php $name = 'qna-retaining-wall' @endphp

            <div class="col-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-yes" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="1" v-model="project.question.retaining_wall" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.yes")</span>
                    </label>
                </div>
            </div>

            <div class="col-auto col-lg-4">
                <div class="icheck-cyan">
                    @php $id = "{$name}-no" @endphp
                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                        :disabled="status.loading" :value="2" v-model="project.question.retaining_wall" />
                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                        <span>@lang("{$choices}.no")</span>
                    </label>
                </div>
            </div>

        </div>

        <transition name="paste-in">
            <template v-if="1 === project.question.retaining_wall">

                <div class="row mt-2">
                    @php $name = 'qna-retaining-wall-location' @endphp
                    
                    <div class="col-lg-4 py-lg-1">
                        <label class="m-0 fw-n" for="{{ $name }}">
                            <span>@lang("{$label}.retaining_wall.location")</span>
                        </label>
                    </div>

                    <div class="col-lg-8">
                        <div class="row mx-n2">

                            <div class="px-2 col-sm-auto col-lg-4">
                                <div class="icheck-cyan">
                                    @php $id = "{$name}-neighbouring-land" @endphp
                                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                        :disabled="status.loading" :value="1" v-model="project.question.retaining_wall_location" />
                                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                                        <span>@lang("{$choices}.retaining_wall.neighbouring_land")</span>
                                    </label>
                                </div>
                            </div>
                
                            <div class="px-2 col-sm-auto col-lg-4">
                                <div class="icheck-cyan">
                                    @php $id = "{$name}-current-area" @endphp
                                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                        :disabled="status.loading" :value="2" v-model="project.question.retaining_wall_location" />
                                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                                        <span>@lang("{$choices}.retaining_wall.current_area")</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </template>
        </transition>

        <transition name="paste-in">
            <template v-if="1 === project.question.retaining_wall">

                <div class="row mt-2">
                    @php $name = 'qna-retaining-wall-breakage' @endphp
                    
                    <div class="col-lg-4 py-lg-1">
                        <label class="m-0 fw-n" for="{{ $name }}">
                            <span>@lang("{$label}.retaining_wall.breakage")</span>
                        </label>
                    </div>

                    <div class="col-lg-8">
                        <div class="row mx-n2">

                            <div class="px-2 col-sm-auto col-lg-4">
                                <div class="icheck-cyan">
                                    @php $id = "{$name}-damaged" @endphp
                                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                        :disabled="status.loading" :value="1" v-model="project.question.retaining_wall_breakage" />
                                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                                        <span>@lang("{$choices}.retaining_wall.damaged")</span>
                                    </label>
                                </div>
                            </div>
                
                            <div class="px-2 col-sm-auto col-lg-4">
                                <div class="icheck-cyan">
                                    @php $id = "{$name}-no-damage" @endphp
                                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                        :disabled="status.loading" :value="2" v-model="project.question.retaining_wall_breakage" />
                                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                                        <span>@lang("{$choices}.retaining_wall.no_damage")</span>
                                    </label>
                                </div>
                            </div>

                            <div class="px-2 col-sm-auto col-lg-4">
                                <div class="icheck-cyan">
                                    @php $id = "{$name}-unconfirmed" @endphp
                                    <input type="radio" id="{{ $id }}" name="{{ $name }}" data-parsley-checkmin="1"
                                        :disabled="status.loading" :value="3" v-model="project.question.retaining_wall_breakage" />
                                    <label for="{{ $id }}" class="fs-12 noselect w-100">
                                        <span>@lang("{$choices}.unconfirmed")</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </template>
        </transition>
    @endslot
@endcomponent