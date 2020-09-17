
@if( 'global_admin' == $user->user_role->name )
    <template v-if="project.approval_request">

        <div class="alert alert-warning bg-cream" role="alert">
            <div class="row mx-n1">
                
                <template v-if="project.request_time">
                    <div class="px-1 col-auto">
                        <strong>@{{ project.request_time }}</strong>
                    </div>
                </template>
    
                <template v-if="project.request_author">
                    <div class="px-1 col-auto">
                        <strong>@{{ project.request_author }}</strong>
                    </div>
                </template>
    
                <div class="px-1 col-auto">
                    <span>のリクエストをどう処理しますか?</span>
                </div>
            </div>
    
            <template v-for="name in [ 'project_approval_request' ]">
                <div class="row mt-2">
                    <div class="col-md-auto">
    
                        <!-- Waiting approval - Start -->
                        @component("{$component->preset}.radio")
                            @slot( 'value', 1 )
                            @slot( 'label', '未承認' ))
                            @slot( 'disabled', 'status.approval.loading')
                            @slot( 'model', 'project.approval_request' )
                        @endcomponent
                        <!-- Waiting approval - End -->
    
                    </div>
                    <div class="col-md-auto">
    
                        <!-- Approved - Start -->
                        @component("{$component->preset}.radio")
                            @slot( 'value', 2 )
                            @slot( 'label', '承認' ))
                            @slot( 'disabled', 'status.approval.loading')
                            @slot( 'model', 'project.approval_request' )
                        @endcomponent
                        <!-- Approved - End -->
    
                    </div>
                    <div class="col-md-auto">
    
                        <!-- Rejected - Start -->
                        @component("{$component->preset}.radio")
                            @slot( 'value', 3 )
                            @slot( 'label', '非承認' ))
                            @slot( 'disabled', 'status.approval.loading')
                            @slot( 'model', 'project.approval_request' )
                        @endcomponent
                        <!-- Rejected - End -->
    
                    </div>
                    <div class="col-md-auto mt-3 mt-md-0">
    
                        <!-- Save button - Start -->
                        <button type="button" class="btn btn-info fs-14" :disabled="status.approval.loading" 
                            @click="updateProjectApproval">
                            <div class="row mx-n1">
                                <div class="px-1 col-auto">
                                    <i v-if="status.approval.loading" class="fw-l far fa-cog fa-spin"></i>
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

    </template>
@endif