<script type="text/x-template" id="group-confirmation">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">23条 構造状況確認</span>
        </label>
        <div class="col-md">

            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">構造等双方確認</span>
            </div>
            <!-- Heading - End -->

            <!-- Number - Start -->
            <div class="row" v-for="name in [ prefix+ 'structure' ]">
                <div class="col-auto">
                                            
                    <div class="icheck-cyan" v-for="id in [ name+ '-1' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="1" v-model="entry.c_article23_confirm" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">有</span>
                        </label>
                    </div>

                </div>
                <div class="col-auto">
                    
                    <div class="icheck-cyan" v-for="id in [ name+ '-2' ]">
                        <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :value="2" v-model="entry.c_article23_confirm" />
                        <label :for="id" class="fs-12 fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">無</span>
                        </label>
                    </div>

                </div>
            </div>
            <!-- Number - End -->

            <!-- Confirmed - Start -->
            <transition name="paste-in">
                <div class="structure-confirmed" v-if="1 === entry.c_article23_confirm">

                    <!-- Document name - Start -->
                    <template v-for="name in [ prefix+ '-document']">
                        <div class="form-group row mb-1 mb-md-2">
                            <label :for="name" class="col-lg-4 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">確認事項を記載した資料の名称</span>
                            </label>
                            <div class="col-lg">

                                @component("{$component->preset}.text")
                                    @slot( 'disabled', 'isDisabled')
                                    @slot( 'model', 'entry.c_article23_confirm_write' )
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Document name - End -->

                    <!-- Creator - Start -->
                    <template v-for="name in [ prefix+ '-creator']">
                        <div class="form-group row mb-1 mb-md-2">
                            <label :for="name" class="col-lg-4 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">資料作成者</span>
                            </label>
                            <div class="col-lg">

                                @component("{$component->preset}.text")
                                    @slot( 'disabled', 'isDisabled')
                                    @slot( 'model', 'entry.c_article23_creator' )
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Creator - End -->

                    <!-- Date - Start -->
                    <template v-for="name in [ prefix+ '-date']">
                        <div class="form-group row mb-1 mb-md-2">
                            <label :for="name" class="col-lg-4 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">資料作成年月日</span>
                            </label>
                            <div class="col-lg">

                                @component("{$component->preset}.date")
                                    @slot( 'block', true )
                                    @slot( 'disabled', 'isDisabled')
                                    @slot( 'model', 'entry.c_article23_create_date' )
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Date - End -->

                    <!-- Other - Start -->
                    <template v-for="name in [ prefix+ '-other']">
                        <div class="form-group row mb-1 mb-md-2">
                            <label :for="name" class="col-lg-4 col-form-label">
                                <span class="fw-n" :class="{ 'text-grey': isCompleted }">その他</span>
                            </label>
                            <div class="col-lg">

                                @component("{$component->preset}.textarea")
                                    @slot( 'disabled', 'isDisabled')
                                    @slot( 'model', 'entry.c_article23_other' )
                                @endcomponent

                            </div>
                        </div>
                    </template>
                    <!-- Other - End -->

                </div>
            </transition>
            <!-- Confirmed - End -->

        </div>
    </div>
</script>
