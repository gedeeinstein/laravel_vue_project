<script type="text/x-template" id="group-delivery">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">8条 引渡（立退き）</span>
        </label>
        <div class="col-md">

            <!-- Delivery - Start -->
            <template v-if="2 === building.purchase_third_person_occupied">
                <div class="row mb-2" v-for="name in [ prefix+ 'eviction' ]">
                    <div class="col-md-6 col-lg-auto">

                        <div class="icheck-cyan" v-for="id in [ name+ '-bearer' ]">
                            <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :value="1" v-model="article8Contract" />
                            <label :for="id" class="fs-12 fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">費用売主負担</span>
                            </label>
                        </div>

                    </div>
                    <div class="col-md-6 col-lg-auto">

                        <div class="icheck-cyan" v-for="id in [ name+ '-buyer' ]">
                            <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :value="2" v-model="article8Contract" />
                            <label :for="id" class="fs-12 fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">費用買主負担</span>
                            </label>
                        </div>

                    </div>
                    <div class="col-md-6 col-lg-auto">

                        <div class="icheck-cyan" v-for="id in [ name+ '-buyer-with-terms' ]">
                            <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :value="3" v-model="article8Contract" />
                            <label :for="id" class="fs-12 fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">費用買主負担、退去が決済条件</span>
                            </label>
                        </div>

                    </div>
                    <div class="col-md-6 col-lg-auto">

                        <div class="icheck-cyan" v-for="id in [ name+ '-other' ]">
                            <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :value="4" v-model="article8Contract" />
                            <label :for="id" class="fs-12 fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">その他</span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- Eviction Other- Start -->
                <label :for="name" class="fs-14 fw-n" :class="{ 'text-grey': isCompleted }">その他の場合の出力内容</label>
                <div class="row mb-2" v-for="name in [ prefix+ 'other' ]">
                    <div class="col col-lg-8" @click="otherContract" ref="otherContract">

                        @component("{$component->preset}.textarea")
                            @slot( 'disabled', 'isDisabled || 4 !== entry.c_article8_contract' )
                            @slot( 'model', 'entry.c_article8_contract_text' )
                            @slot( 'placeholder', "'改行区切りで入力'" )
                        @endcomponent

                    </div>
                </div>
                <!-- Eviction Other - End -->

            </template>
            <!-- Delivery - End -->

            <!-- Not required label - Start -->
            <template v-else>
                <div class="fs-14 my-2" :class="isCompleted ? 'text-grey': 'text-danger'">第三者の占有がないため入力の必要はありません。</div>
            </template>
            <!-- Not required label - End -->

        </div>
    </div>
</script>
