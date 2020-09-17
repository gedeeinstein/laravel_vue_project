<script type="text/x-template" id="group-boundary">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">5条 境界</span>
        </label>
        <div class="col-md">

            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">確定測量</span>
            </div>
            <!-- Heading - End -->

            <!-- Fixed survey - Start -->
            <div class="row" v-for="name in [ prefix+ 'fixed-survey' ]">
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-completed' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="article5FixedSurveyContract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">済</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="article5FixedSurveyContract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">有</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">

                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="3" v-model="article5FixedSurveyContract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">無</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Fixed survey - End -->

            <!-- Fixed survey options - Start -->
            <transition name="paste-in">
                <div class="fixed-survey" v-if="3 === entry.c_article5_fixed_survey_contract">

                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">確定測量：無</span>
                    </div>
                    <!-- Heading - End -->

                    <!-- Radio options - Start -->
                    <div class="row" v-for="name in [ prefix+ 'fixed-survey-options' ]">
                        <div class="col-md-6 col-lg-4 col-xl-auto d-flex align-items-center">

                            <div class="icheck-cyan" v-for="id in [ name+ '-current' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="1" v-model="surveyOption" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">現況測量</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-auto d-flex align-items-center">

                            <div class="icheck-cyan" v-for="id in [ name+ '-geotechnical' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="2" v-model="surveyOption" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">地積測量</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-auto d-flex align-items-center">

                            <div class="icheck-cyan" v-for="id in [ name+ '-unconfirmed-and-explicit' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="3" v-model="surveyOption" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">確測無・明示有</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-auto d-flex align-items-center">

                            <div class="icheck-cyan" v-for="id in [ name+ '-unconfirmed-or-explicit' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="4" v-model="surveyOption" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">確測無・明示無</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-md-12 col-lg-8 col-xl d-flex align-items-center">

                            <div class="row mx-n1 align-items-center w-100" v-for="id in [ name+ '-other' ]" @click="otherOption">
                                <div class="px-1 col-auto">

                                    <div class="icheck-cyan">
                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                            :disabled="isDisabled" :value="5" v-model="surveyOption" />
                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                            <span :class="{ 'text-black-50': isCompleted }">その他</span>
                                        </label>
                                    </div>

                                </div>
                                <div class="px-1 col py-1" ref="otherInput">
                                    @component("{$component->preset}.text")
                                        @slot( 'name', 'id' )
                                        @slot( 'disabled', 'isDisabled || 5 !== entry.c_article5_fixed_survey_options_contract' )
                                        @slot( 'model', 'entry.c_article5_fixed_survey_options_other_contract' )
                                    @endcomponent
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Radio options - End -->

                    <!-- Land survey - Start -->
                    <transition name="paste-in">
                        <div class="land-survey" v-if="landSurvey">

                            <!-- Heading - Start -->
                            <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">地積測量</span>
                            </div>
                            <!-- Heading - End -->

                            <!-- Radio options - Start -->
                            <div class="row" v-for="name in [ prefix+ 'land-survey' ]">
                                <div class="col-auto">

                                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                            :disabled="isDisabled" :value="1" v-model="entry.c_article5_land_surveying" />
                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                            <span :class="{ 'text-black-50': isCompleted }">有</span>
                                        </label>
                                    </div>

                                </div>
                                <div class="col-auto">

                                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                            :disabled="isDisabled" :value="2" v-model="entry.c_article5_land_surveying" />
                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                            <span :class="{ 'text-black-50': isCompleted }">無</span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <!-- Radio options - End -->

                        </div>
                    </transition>
                    <!-- Land survey - End -->

                </div>
            </transition>
            <!-- Fixed survey options - End -->

            <!-- Contract burden - Start -->
            <transition name="paste-in">
                <div class="contract-burden" v-if="2 === entry.c_article5_fixed_survey_contract">

                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">確測負担</span>
                    </div>
                    <!-- Heading - End -->

                    <!-- Radio options - Start -->
                    <div class="row" v-for="name in [ prefix+ 'contract-burden' ]">
                        <div class="col-auto">

                            <div class="icheck-cyan" v-for="id in [ name+ '-seller' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="1" v-model="article5BurdenContract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">売主</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-auto">

                            <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="2" v-model="article5BurdenContract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">買主</span>
                                </label>
                            </div>

                        </div>
                    </div>
                    <!-- Radio options - End -->


                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">地積更正登記負担</span>
                    </div>
                    <!-- Heading - End -->

                    <!-- Radio options - Start -->
                    <div class="row" v-for="name in [ prefix+ 'registration-burden' ]">
                        <div class="col-auto">

                            <div class="icheck-cyan" v-for="id in [ name+ '-seller' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="1" v-model="article5Burden2Contract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">売主</span>
                                </label>
                            </div>

                        </div>
                        <div class="col-auto">

                            <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="2" v-model="article5Burden2Contract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">買主</span>
                                </label>
                            </div>

                        </div>
                    </div>
                    <!-- Radio options - End -->

                </div>
            </transition>
            <!-- Contract burden - Start -->

            <!-- Date and creator - Start -->
            <transition name="paste-in">
                <div class="date-and-author" v-if="1 === entry.c_article5_fixed_survey_contract">

                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">日付と作製者</span>
                    </div>
                    <!-- Heading - End -->

                    <!-- Date input - Start -->
                    <div class="row mt-3" v-for="name in [ prefix+ 'date' ]">
                        <label :for="name" class="col-md-3 col-form-label">
                            <span class="fw-n" :class="{ 'text-grey': isCompleted }">日付</span>
                        </label>
                        <div class="col-md col-lg-8 col-xl-6">
                            @component("{$component->preset}.date")
                                @slot( 'name', 'name' )
                                @slot( 'block', 'true' )
                                @slot( 'disabled', 'isDisabled' )
                                @slot( 'model', 'entry.c_article5_date_contract' )
                            @endcomponent
                        </div>
                    </div>
                    <!-- Date input - End -->

                    <!-- Creator input - Start -->
                    <div class="row mt-2" v-for="name in [ prefix+ 'creator' ]">
                        <label :for="name" class="col-md-3 col-form-label">
                            <span class="fw-n" :class="{ 'text-grey': isCompleted }">作製者</span>
                        </label>
                        <div class="col-md col-lg-8 col-xl-6">
                            @component("{$component->preset}.text")
                                @slot( 'name', 'name' )
                                @slot( 'disabled', 'isDisabled' )
                                @slot( 'model', 'entry.c_article5_creator_contract' )
                            @endcomponent
                        </div>
                    </div>
                    <!-- Creator input - End -->

                </div>
            </transition>
            <!-- Date and creator - Start -->

        </div>
    </div>
</script>
