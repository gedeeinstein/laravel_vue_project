<script type="text/x-template" id="inspection">
    <div v-if="project.approval_request && entry">

        <div class="alert" :class="editable ? 'alert-warning bg-cream': 'border'" role="alert">
            <div class="row mx-n1">
                
                <template v-if="requestDate">
                    <div class="px-1 col-auto">
                        <strong>@{{ requestDate }}</strong>
                    </div>
                </template>
    
                <template v-if="requestAuthor">
                    <div class="px-1 col-auto">
                        <strong>@{{ requestAuthor }}</strong>
                    </div>
                </template>
    
                <div v-if="editable" class="px-1 col-auto">
                    <span>のリクエストをどう処理しますか?</span>
                </div>
            </div>
    
            <template v-for="name in [ 'project_approval_request' ]">
                <div class="row mt-2">

                    <!-- Waiting approval - Start -->
                    <div class="col-md-auto">

                        <!-- Radio input - Start -->
                        <template v-if="editable">
                            @component("{$component->preset}.radio")
                                @slot( 'value', 1 )
                                @slot( 'label', '未承認' )
                                @slot( 'disabled', 'isDisabled' )
                                @slot( 'model', 'entry.examination' )
                            @endcomponent
                        </template>
                        <!-- Radio input - End -->
                        
                        <!-- Static label - Start -->
                        <div v-else class="row mx-n1">
                            <div class="px-1 fs-22 text-black-50">
                                <i v-if="1 === entry.examination" class="fas fa-check-circle"></i>
                                <i v-else class="fal fa-circle"></i>
                            </div>
                            <div class="px-1 d-flex align-items-center">
                                <span class="fs-13 fw-b">未承認</span>
                            </div>
                        </div>
                        <!-- Static label - End -->
                        
                    </div>
                    <!-- Waiting approval - End -->

                    <!-- Approved - Start -->
                    <div class="col-md-auto">

                        <!-- Radio input - Start -->
                        <template v-if="editable">
                            @component("{$component->preset}.radio")
                                @slot( 'value', 2 )
                                @slot( 'label', '承認' )
                                @slot( 'disabled', 'isDisabled' )
                                @slot( 'model', 'entry.examination' )
                            @endcomponent
                        </template>
                        <!-- Radio input - End -->

                        <!-- Static label - Start -->
                        <div v-else class="row mx-n1">
                            <div class="px-1 fs-22 text-black-50">
                                <i v-if="2 === entry.examination" class="fas fa-check-circle"></i>
                                <i v-else class="fal fa-circle"></i>
                            </div>
                            <div class="px-1 d-flex align-items-center">
                                <span class="fs-13 fw-b">承認</span>
                            </div>
                        </div>
                        <!-- Static label - End -->
                        
                    </div>
                    <!-- Approved - End -->
                    
                    <!-- Rejected - Start -->
                    <div class="col-md-auto">

                        <!-- Radio input - Start -->
                        <template v-if="editable">
                            @component("{$component->preset}.radio")
                                @slot( 'value', 3 )
                                @slot( 'label', '非承認' )
                                @slot( 'disabled', 'isDisabled' )
                                @slot( 'model', 'entry.examination' )
                            @endcomponent
                        </template>
                        <!-- Radio input - End -->

                        <!-- Static label - Start -->
                        <div v-else class="row mx-n1">
                            <div class="px-1 fs-22 text-black-50">
                                <i v-if="3 === entry.examination" class="fas fa-check-circle"></i>
                                <i v-else class="fal fa-circle"></i>
                            </div>
                            <div class="px-1 d-flex align-items-center">
                                <span class="fs-13 fw-b">非承認</span>
                            </div>
                        </div>
                        <!-- Static label - End -->
    
                    </div>
                    <!-- Rejected - End -->


                    <div v-if="editable" class="col-md-auto mt-3 mt-md-0">
    
                        <!-- Save button - Start -->
                        <button type="button" class="btn btn-info fs-14" :disabled="isDisabled" @click="update">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto">
                                    <i v-if="status.loading" class="fw-l far fa-cog fa-spin"></i>
                                    <i v-else class="fw-l far fa-check-circle"></i>
                                </div>
                                <div class="px-1 col-auto">送信</div>
                            </div>
                        </button>
                        <!-- Save button - End -->
    
                    </div>
                </div>
            </template>
        </div>

    </div>
</script>