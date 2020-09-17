<script type="text/x-template" id="additional-question">
    <div class="row mb-3">
            
        <div class="col-md-6 py-md-1">
            <span v-if="entry.index">Z@{{ entry.id }}.</span>
            <span v-if="entry.question">@{{ entry.question }}</span>
        </div>

        <div class="col-md-6">

            <!-- Radio type question - Start -->
            <template v-if="1 === entry.input_type">
                <div class="row mx-n2">

                    <!-- When option is empty - Start -->
                    <template v-if="!hasOptions">
                        <div class="px-2 col-auto col-lg-6">-</div>
                    </template>
                    <!-- When option is empty - End -->

                    <template v-else v-for="( option, optionIndex ) in entry.options">
                        <template v-for="id in [ 'additional-qa-' +entry.index+ '-option-' +( optionIndex +1 )]">

                            <div class="px-2 col-auto col-lg-6">
                                <div class="icheck-cyan">
                                    <input type="radio" :id="id" :name="'additional-qa-' +entry.index" data-parsley-checkmin="1"
                                        :disabled="isDisabled" :value="option" v-model="entry.answer.answer" />
                                    <label :for="id" class="fs-12 noselect w-100">
                                        <span>@{{ option }}</span>
                                    </label>
                                </div>
                            </div>

                        </template>
                    </template>
                </div>
            </template>
            <!-- Radio type question - End -->
        

            <!-- Checkbox type question - Start -->
            <template v-else-if="2 == entry.input_type">
                <div class="row mx-n2">
                    
                    <!-- When option is empty - Start -->
                    <template v-if="!hasOptions">
                        <div class="px-2 col-auto col-lg-6">-</div>
                    </template>
                    <!-- When option is empty - End -->

                    <template v-else v-for="( option, optionIndex ) in entry.options">
                        <template v-for="name in [ 'additional-qa-' +entry.index+ '-option-' +( optionIndex +1 )]">

                            <div class="px-2 col-auto col-lg-6">
                                <div class="icheck-cyan">
                                    <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                        :disabled="isDisabled" :value="option" v-model="entry.answer.answer" />
                                    <label :for="name" class="fs-12 noselect w-100">
                                        <span>@{{ option }}</span>
                                    </label>
                                </div>
                            </div>

                        </template>
                    </template>
                </div>
            </template>
            <!-- Checkbox type question - End -->
            
        
            <!-- Text type question - Start -->
            <template v-else-if="3 == entry.input_type" v-for="name in [ 'additional-qa-' +entry.index ]">
                <textarea :name="name" class="form-control fs-15 mt-2 mt-md-0" v-model="entry.answer.answer" 
                    row="1" :disabled="isDisabled"></textarea>
            </template>
            <!-- Text type question - End -->

        </div>
    </div>
</script>