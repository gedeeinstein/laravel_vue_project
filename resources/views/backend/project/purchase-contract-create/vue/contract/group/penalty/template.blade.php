<script type="text/x-template" id="group-penalty">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">12条 違約金</span>
        </label>
        <div class="col-md">
            
            <!-- Penalty - Start -->
            <div class="row" v-for="name in [ prefix+ 'option' ]">
                <div class="col-sm-auto d-flex align-items-center">
                                            
                    <div class="icheck-cyan" v-for="id in [ name+ '-deposit-amount' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="penaltyOption" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">手付金の額</span>
                        </label>
                    </div>

                </div>
                <div class="col-sm-auto d-flex align-items-center">
                    
                    <div class="icheck-cyan" v-for="id in [ name+ '-trading-value' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="penaltyOption" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売買代金の20%相当額</span>
                        </label>
                    </div>

                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    
                    <div class="row mx-0 align-items-center w-100" v-for="id in [ name+ '-other' ]" @click="otherOption">
                        <div class="px-0 col-auto">

                            <div class="icheck-cyan">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="3" v-model="penaltyOption" />
                                <label :for="id" class="fs-12 fw-n noselect w-100"></label>
                            </div>

                        </div>
                        <div class="px-0 col py-1" ref="otherInput">
                            @component("{$component->preset}.text")
                                @slot( 'name', 'id' )
                                @slot( 'group', true )
                                @slot( 'append', '円' )
                                @slot( 'disabled', 'isDisabled || 3 !== entry.c_article12_contract' )
                                @slot( 'model', 'entry.c_article12_contract_text' )
                            @endcomponent
                        </div>
                    </div>

                </div>
            </div>
            <!-- Penalty - End -->

        </div>
    </div>
</script>
