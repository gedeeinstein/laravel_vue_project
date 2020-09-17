@extends('backend._base.content_project')

@section('preloader')
<transition name="preloader">
    <div class="preloader preloader-fullscreen d-flex justify-content-center align-items-center" v-if="initial.loading">
        <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
    </div>
</transition>
@endsection

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-1"></i> @lang('label.dashboard')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">仕入</a></li>
    <li class="breadcrumb-item"><a href="{{ route('project.purchase.contract', ['project' => $project->id, 'purchase' => $purchase_target->purchase->id]) }}">仕入契約</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
<form data-parsley class="parsley-minimal">
    @csrf
    @include('backend.project.purchase-response.form.purchase_information')
    @include('backend.project.purchase-response.form.notice')
    @include('backend.project.purchase-response.form.request_for_permission')
    @include('backend.project.purchase-response.form.contract_desired_conditions')
    @include('backend.project.purchase-response.form.optional_items')
    <div class="col-md-12" align="center">
        <div v-if="purchase_contract_create" class="text-danger mt-3 mb-3">
            @lang('pj_purchase_response.contract_created')
        </div>
        <!-- start - contract_update -->
        <div class="icheck-cyan d-inline">
            <input v-model="purchase_response.contract_update" id="update_contract_only"
                type="radio" class="custom-control-input" value="1"
            >
            <label class="text-uppercase fw-n mr-5"
                for="update_contract_only">@lang('pj_purchase_response.update_contract_only')
            </label>
        </div>
        <div class="icheck-cyan d-inline">
            <input v-model="purchase_response.contract_update" id="modify_created_contract"
                type="radio" class="custom-control-input" value="2"
            >
            <label class="text-uppercase fw-n mr-5"
                for="modify_created_contract">@lang('pj_purchase_response.modify_created_contract')
            </label>
        </div>
    </div>
    <!-- start - contract_update -->

    <!-- start - save button -->
    <div class="bottom mt-3 mb-5 text-center">
        <button type="submit" class="btn btn-wide btn-info px-4">
        <i v-if="!initial.submited" class="fas fa-save"></i>
        <i v-else class="fas fa-spinner fa-spin"></i>
        保存
        </button>
    </div>
    <!-- end - save button -->
</form>
@endsection

@push('vue-scripts')
<script>
    // ----------------------------------------------------------------------
    mixin = {
        // ------------------------------------------------------------------
        // Mounted state
        // ------------------------------------------------------------------
        mounted: function(){
          // change initial loading value
          this.initial.loading = false

          // refresh parsley form validation
          refreshParsley(this);

          // triger event on loaded
          $(document).trigger( 'vue-loaded', this );
        },

        // ------------------------------------------------------------------
        // Vue Data Binding
        // ------------------------------------------------------------------
        data: function(){

            // initial purchase response value
            // -----------------------------------------------------------------
            let initial = @json( $initial );
            initial.loading = true;
            initial.submited = false;
            // -----------------------------------------------------------------

            // assign value from database
            // -----------------------------------------------------------------
            let purchase_target          = @json( $purchase_target ) ?? initial.target;
            let purchase_doc             = @json( $purchase_doc ) ?? initial.doc;
            let purchase_response        = @json( $purchase_response ) ?? initial.response;
            let purchase_contract_create = @json( $purchase_contract_create );
            // -----------------------------------------------------------------

            // return value
            // -----------------------------------------------------------------
            return {
                initial: initial,
                purchase_target: purchase_target,
                purchase_doc: purchase_doc,
                purchase_response: purchase_response,
                purchase_contract_create: purchase_contract_create,
            };
            // -----------------------------------------------------------------
        },

        // ------------------------------------------------------------------
        // Vue watch
        // ------------------------------------------------------------------
        watch: {
            purchase_response:{
                deep: true,
                handler: function(data){
                    if (data.notices_a == 2) this.purchase_response.notices_a_text = null;
                    if (data.notices_b == 2) this.purchase_response.notices_b_text = null;
                    if (data.notices_c == 2) this.purchase_response.notices_c_text = null;
                    if (data.notices_d == 2) this.purchase_response.notices_d_text = null;
                    if (data.notices_e == 2) this.purchase_response.notices_e_text = null;
                    if (data.desired_contract_terms_m == 1) this.purchase_response.desired_contract_terms_m_text = null;
                    if (data.desired_contract_terms_r_1 == 2) this.purchase_response.desired_contract_terms_r_1_text = null;
                    if (data.desired_contract_terms_r_2 == 2) this.purchase_response.desired_contract_terms_r_2_text = null;
                }
            },

            purchase_doc:{
                immediate: true,
                deep: true,
                handler: function(data){

                    // assign default value if purchase doc is null
                    // ---------------------------------------------------------
                    if (data == null) {
                        this.purchase_doc = this.initial.doc;
                        data              = this.initial.doc;
                    }
                    // ---------------------------------------------------------

                    let fields = []; // initial data

                    // notice
                    // ---------------------------------------------------------
                    fields = ["a", "b", "c", "d", "e"];

                    fields.forEach((field, index) => {
                        field = 'notices_' + field;
                        if (data[field] != 1) {
                            this.purchase_response[field] = null;
                        }
                    });
                    // ---------------------------------------------------------

                    // request permission
                    // ---------------------------------------------------------
                    fields = ["a", "b", "c", "d", "e"];

                    fields.forEach((field, index) => {
                        field = 'request_permission_' + field;
                        if (data[field] != 1) {
                            this.purchase_response[field] = null;
                        }
                    });
                    // ---------------------------------------------------------

                    // desired contract terms
                    // ---------------------------------------------------------
                    fields = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
                                    "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                                    "w", "x", "y", "z", "aa", "am"
                             ];

                    fields.forEach((field, index) => {
                        field = 'desired_contract_terms_' + field;
                        if (data[field] != 1 && field != 'desired_contract_terms_r') {
                            this.purchase_response[field] = null;
                        }else if (data[field] != 1 && field == 'desired_contract_terms_r') {
                            this.purchase_response.desired_contract_terms_r_1 = null;
                            this.purchase_response.desired_contract_terms_r_2 = null;
                        }
                    });
                    // ---------------------------------------------------------

                    // request permission
                    // ---------------------------------------------------------
                    fields = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k"];
                    let response_fields = ["ab", "ac", "ad", "ae", "af", "ag", "ah",
                                            "ai", "aj", "ak", "al"
                                          ];

                    fields.forEach((field, index) => {
                        field = 'optional_items_' + field;
                        let response_field = 'desired_contract_terms_' + response_fields[index];
                        if (data[field] != 1) {
                            this.purchase_response[response_field] = null;
                        }
                    });
                    // ---------------------------------------------------------

                    // purchase_response.desired_contract_terms_an
                    // ---------------------------------------------------------
                    if (!data.gathering_request_third_party)
                        this.purchase_response.desired_contract_terms_an = null;
                    // ---------------------------------------------------------
                }
            }
        },

        // ------------------------------------------------------------------
        // Vue Methods
        // ------------------------------------------------------------------
        methods: {

        }
        // ------------------------------------------------------------------
    }

    /*
    ## ----------------------------------------------------------------------
    ## VUE LOADED EVENT
    ## handle submit data and form validation
    ## ----------------------------------------------------------------------
    */
    $(document).on('vue-loaded', function( event, vm ){
        // init parsley form validation
        // ------------------------------------------------------------------
        let $form = $('form[data-parsley]');
        let form = $form.parsley();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On form submit
        // ------------------------------------------------------------------
        form.on( 'form:validated', function(){
            // --------------------------------------------------------------
            // on form not valid
            // --------------------------------------------------------------
            let valid = form.isValid();
            if( !valid ) setTimeout( function(){
                var $errors = $('.parsley-errors-list.filled').first();
                animateScroll( $errors );
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // on valid form
            // --------------------------------------------------------------
            else {
                 // initialize data
                 let data = {
                   purchase_doc: vm.purchase_doc,
                   purchase_response: vm.purchase_response,
                   purchase_target: vm.purchase_target,
                 }

                 // set loading state
                 // ----------------------------------------------------------
                 vm.initial.submited = true;

                // handle update request
                // ----------------------------------------------------------
                let request = axios.post('{{ $form_action }}', data)
                // ----------------------------------------------------------

                // handle success response
                // ----------------------------------------------------------
                request.then(function(response){

                  // update data to response data
                  // ------------------------------------------------------------
                  vm.purchase_target = response.data.data.purchase_target
                  vm.purchase_doc = response.data.data.purchase_doc
                  vm.purchase_response = response.data.data.purchase_response
                  // ------------------------------------------------------------

                  // redirect to A-13 Page
                  // ------------------------------------------------------------
                  if (vm.purchase_response.contract_update == 2) {
                    // window.location.href = response.data.data.purchase_contract_create_url
                    // return false
                  }
                  // ------------------------------------------------------------

                  $.toast({
                    heading: '成功', icon: 'success',
                    position: 'top-right', stack: false, hideAfter: 3000,
                    text: '編集した内容を保存しました。',
                    position: { right: 18, top: 68 }
                  });

                  scrollTop(); // Add scroll-top

                })
                // ----------------------------------------------------------

                // handle error response
                // ----------------------------------------------------------
                request.catch(function(error){
                  var error_response = []
                  if (error.response.status == 422) error_response.push(error.response.data.message)
                  else if (error.response.status == 500) error_response.push(error.response.data.error)
                  $.toast({
                    heading: '失敗', icon: 'error',
                    position: 'top-right', stack: false, hideAfter: 3000,
                    text: error_response,
                    position: { right: 18, top: 68 }
                  });
                })
                // ----------------------------------------------------------

                // always execute function
                // ----------------------------------------------------------
                request.finally(function () {
                    vm.initial.submited = false;
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }).on('form:submit', function(){ return false });
    })

    // ----------------------------------------------------------------------
    // Custom function refresh validator
    // ----------------------------------------------------------------------
    var refreshParsley = function(){
       setTimeout( function(){
           var $form = $('form[data-parsley]');
           $form.parsley().refresh();
       });
    }
    var refreshTooltip = function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
        Vue.nextTick(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
    // ----------------------------------------------------------------------
    var animateScroll = function( scroll, duration ){
        duration = duration || 800;
        var offset = 160;
        // ------------------------------------------------------------------
        if( !_.isInteger( scroll )){
            var $target = $( scroll );
            if( $target.length ) scroll = $target.offset().top;
        }
        // ------------------------------------------------------------------
        var $html = $('html');
        var scrolltop = scroll - offset;
        if( scrolltop <= 0 ) scrolltop = 0;
        // ------------------------------------------------------------------
        anime({
            targets: $html.get()[0], scrollTop: scrolltop,
            duration: duration, easing: 'linear'
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
</script>
@endpush
