<script type="text/x-template" id="group-stamp">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">16条 印紙</span>
        </label>
        <div class="col-md">

            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">通数</span>
            </div>
            <!-- Heading - End -->

            <!-- Number - Start -->
            <div class="row" v-for="name in [ prefix+ 'number' ]">
                <div class="col-auto">
                                            
                    <div class="icheck-cyan" v-for="id in [ name+ '-1' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="entry.c_article16_contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">契約書1通</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">
                    
                    <div class="icheck-cyan" v-for="id in [ name+ '-2' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="entry.c_article16_contract" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">契約書2通</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Number - End -->

            <!-- Contract burden - Start -->
            <transition name="paste-in">
                <div class="one-contract" v-if="1 === entry.c_article16_contract">

                    <!-- Heading - Start -->
                    <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                        <span class="fw-n" :class="{ 'text-grey': isCompleted }">負担</span>
                    </div>
                    <!-- Heading - End -->
        
                    <!-- Radio options - Start -->
                    <div class="row" v-for="name in [ prefix+ 'burden' ]">
                        <div class="col-auto">
                                                    
                            <div class="icheck-cyan" v-for="id in [ name+ '-main' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="1" v-model="entry.c_article16_burden_contract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">売主負担</span>
                                </label>
                            </div>
        
                        </div>
                        <div class="col-auto">
                            
                            <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="2" v-model="entry.c_article16_burden_contract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">買主負担</span>
                                </label>
                            </div>
        
                        </div>
                        <div class="col-auto">
                            
                            <div class="icheck-cyan" v-for="id in [ name+ '-half' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isDisabled" :value="3" v-model="entry.c_article16_burden_contract" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isCompleted }">折半</span>
                                </label>
                            </div>
        
                        </div>
                    </div>
                    <!-- Radio options - End -->

                    <!-- Base contract - Start -->
                    <transition name="paste-in">
                        <div class="base-contract" v-if="3 === entry.c_article16_burden_contract">

                            <!-- Heading - Start -->
                            <div class="heading rounded bg-grey p-2 mt-2 mb-2">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">原本</span>
                            </div>
                            <!-- Heading - End -->

                            <!-- Radio options - Start -->
                            <div class="row" v-for="name in [ prefix+ 'base' ]">
                                <div class="col-auto">
                                                            
                                    <div class="icheck-cyan" v-for="id in [ name+ '-yes' ]">
                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                            :disabled="isDisabled" :value="1" v-model="entry.c_article16_base_contract" />
                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                            <span :class="{ 'text-black-50': isCompleted }">原本：売主　写し：買主</span>
                                        </label>
                                    </div>

                                </div>
                                <div class="col-auto">
                                    
                                    <div class="icheck-cyan" v-for="id in [ name+ '-no' ]">
                                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                            :disabled="isDisabled" :value="2" v-model="entry.c_article16_base_contract" />
                                        <label :for="id" class="fs-12 fw-n noselect w-100">
                                            <span :class="{ 'text-black-50': isCompleted }">原本：買主　写し：売主</span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <!-- Radio options - End -->

                        </div>
                    </transition>
                    <!-- Base contract - End -->

                </div>
            </transition>
            <!-- Contract burden - End -->

        </div>
    </div>
</script>
