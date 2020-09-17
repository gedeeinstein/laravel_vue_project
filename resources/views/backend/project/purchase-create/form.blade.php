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
   <li class="breadcrumb-item"><a href="{{ route('project.purchase', $project->id) }}">仕入買付</a></li>
   <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection
@section('content')
<div class="">
   <form class="" data-parsley class="pasley-minimal">
      <div class="purchase-input">

        @include('backend.project.purchase-create.form.purchase-information')
        @include('backend.project.purchase-create.form.heads-up')
        @include('backend.project.purchase-create.form.properties-description')
        @include('backend.project.purchase-create.form.front-road')
        @include('backend.project.purchase-create.form.contract')
        @include('backend.project.purchase-create.form.consolidation-request')
        @include('backend.project.purchase-create.form.submit-button')

      </div>
   </form>
   @include('backend.project.purchase-create.form.approval-request')
</div>
@endsection

@if(0) <script> @endif
  @push('extend-parsley')
      // ------------------------------------------------------------------
      options.successClass = false;
      // ------------------------------------------------------------------
      // Exluded elements
      // ------------------------------------------------------------------
      options.excluded = 'input[type=button], input[type=submit], input[type=reset], '+
          'input[type=hidden], input.parsley-excluded, [data-parsley-excluded]';
      // ------------------------------------------------------------------
      // Finding error container
      // ------------------------------------------------------------------
      options.errorsContainer = function( field ){
          // --------------------------------------------------------------
          var formResult = '.form-result';
          var $element = $( field.$element );
          var $result = $element.siblings( formResult );
          // --------------------------------------------------------------
          if( $result.length ) return $result;
          else {
              // ----------------------------------------------------------
              var $parent = $element.parent();
              if( $parent.is('.input-group')){
                  $result = $parent.siblings( formResult );
                  if( $result.length ) return $result;
              }
              // ----------------------------------------------------------
              var $row = $element.closest('.row');
              $result = $row.siblings('.form-result');
              // ----------------------------------------------------------
              if( $result.length ) return $result;
              else {
                  // ------------------------------------------------------
                  var $group = $element.closest('.form-group');
                  $result = $group.children( formResult );
                  // ------------------------------------------------------
                  if( $result.length ) return $result;
                  // ------------------------------------------------------
              }
              // ----------------------------------------------------------
          }
          // --------------------------------------------------------------
      }
      // ------------------------------------------------------------------
  @endpush
@if(0) </script> @endif

@push('vue-scripts')
<script>
   // ----------------------------------------------------------------------
   // ------------------------------------------------------------------
    // Toast default options
    // ------------------------------------------------------------------
    var toast = {
        heading: '@lang('label.error')', icon: 'error',
        position: 'top-right', stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ------------------------------------------------------------------

   mixin = {
       // ------------------------------------------------------------------
       // Mounted state
       // ------------------------------------------------------------------
       mounted: function(){
         this.purchase_doc.properties_description_a = @json( $purchase_description_a );

         // refresh parsley form validation
         refreshParsley(this);

         // triger event on loaded
         $(document).trigger( 'vue-loaded', this );

         // switch loading state
         this.initial.loading = false;
       },

       // ------------------------------------------------------------------
       // Vue Data Binding
       // ------------------------------------------------------------------
       data: function(){
           let initial = {
             loading: true,
             submited: [false, false, false, false],
             submited_index: 0,
             approval: false,
             inspection_url: @json( $inspection_url ),
           }
           let purchase_target = @json( $purchase_target );
           let purchase_sale = @json( $purchase_sale );
           // initial purchase create value
           let purchase_doc = {
             id: null, pj_purchase_target_id: purchase_target.id,

             heads_up_a: null, heads_up_b: null, heads_up_c: 3,
             heads_up_d: 3, heads_up_e: null, heads_up_f: null,
             heads_up_g: null, heads_up_status: null, heads_up_memo: null,

             properties_description_a: null, properties_description_b: null, properties_description_c: 2,
             properties_description_d: null, properties_description_e: null, properties_description_f: null,
             properties_description_status: null, properties_description_memo: null,

             road_size_contract_a: null, road_size_contract_b: null,
             road_size_contract_c: null, road_size_contract_d: null,

             road_type_contract_a: null, road_type_contract_b: null, road_type_contract_c: null,
             road_type_contract_d: null, road_type_contract_e: null, road_type_contract_f: null,
             road_type_contract_g: null, road_type_contract_h: null, road_type_contract_i: null,

             road_type_sub2_contract_a: null, road_type_sub2_contract_b: null,
             road_type_sub1_contract: null, road_type_sub3_contract: null,
             front_road_f: null, front_road_status: null, front_road_memo: null,

             contract_a: null, contract_b: null, contract_c: null,
             contract_d: null, contract_status: null, contract_memo: null,

             gathering_request_title: 1, gathering_request_to: null,
             gathering_request_to_check: 1, gathering_request_third_party: null,

             notices_a: 1, notices_b: null, notices_c: null,
             notices_d: 1, notices_e: 1, notices_f: 1,

             request_permission_a: 1, request_permission_b: 1, request_permission_c: 1,
             request_permission_d: 1, request_permission_e: 0,

             desired_contract_terms_a: null, desired_contract_terms_b: null, desired_contract_terms_c: null,
             desired_contract_terms_d: null, desired_contract_terms_e: null, desired_contract_terms_f: null,
             desired_contract_terms_g: null, desired_contract_terms_h: null, desired_contract_terms_i: null,
             desired_contract_terms_j: null, desired_contract_terms_k: null, desired_contract_terms_l: 1,
             desired_contract_terms_m: null, desired_contract_terms_n: null, desired_contract_terms_o: null,
             desired_contract_terms_p: null, desired_contract_terms_q: null, desired_contract_terms_r: null,
             desired_contract_terms_s: null, desired_contract_terms_t: null, desired_contract_terms_u: null,
             desired_contract_terms_v: null, desired_contract_terms_w: null, desired_contract_terms_x: null,
             desired_contract_terms_y: null, desired_contract_terms_z: null, desired_contract_terms_aa: null,
             desired_contract_terms_ab: null,

             optional_items_a: null, optional_items_b: null, optional_items_c: null,
             optional_items_d: null, optional_items_e: null, optional_items_f: null,
             optional_items_g: null, optional_items_h: 1, optional_items_i: 1,
             optional_items_j: null, optional_items_k: null, optional_memo_content: null,

             desired_contract_date: null, settlement_date: null,
             expiration_date: null, gathering_request_status: null,
             gathering_request_memo: null,
           }
           let purchase_doc_optional_memos = [{
             id: null,
             pj_purchase_doc_id: null,
             content: null,
           }]
           // ------------------------------------------------------------------

           // init data from db to json
           // ------------------------------------------------------------------
           let purchase_target_contractors_group_by_name = @json( $purchase_target_contractors_group_by_name );
           let purchase_third_person_occupied = @json( $purchase_third_person_occupied );
           let project = @json( $project );
           let inspection = @json( $inspection );

           let db_purchase_doc = @json( $purchase_doc );
           let db_purchase_doc_optional_memos = @json( $purchase_doc_optional_memos );
           // ------------------------------------------------------------------

           // assign value from db
           // ------------------------------------------------------------------
           if (db_purchase_doc != null) Object.assign(purchase_doc, db_purchase_doc)
           if (db_purchase_doc_optional_memos.length > 0) Object.assign(purchase_doc_optional_memos, db_purchase_doc_optional_memos)
           // ------------------------------------------------------------------

           // set purchase_doc.gathering_request_third_party
           // ------------------------------------------------------------------           
           if (purchase_third_person_occupied != 2)
                purchase_doc.gathering_request_third_party = null;            
           // ------------------------------------------------------------------

           return {
             initial: initial,
             project: project,
             inspection: inspection,
             purchase_target: purchase_target,
             purchase_doc: purchase_doc,
             purchase_doc_optional_memos: purchase_doc_optional_memos,
             purchase_target_contractors_group_by_name: purchase_target_contractors_group_by_name,
             purchase_third_person_occupied: purchase_third_person_occupied,
             purchase_sale: purchase_sale,
           };
       },

       // ------------------------------------------------------------------
       // Vue watch
       // ------------------------------------------------------------------
       watch: {
         purchase_doc: {
           immediate: true,
           deep: true,
           handler: function(purchase_doc){
             // ----------------------------------------------------------------
             if (this.purchase_third_person_occupied == 1)
                 purchase_doc.purchase_third_person_occupied == null
             // ----------------------------------------------------------------
             if (purchase_doc.properties_description_a == 3 || purchase_doc.properties_description_a == 4){
               if (purchase_doc.notices_b == null)
                  purchase_doc.notices_b = 1
             }
             else
                purchase_doc.notices_b = null
             // ----------------------------------------------------------------
             if (purchase_doc.properties_description_e == 2 || purchase_doc.properties_description_e == 3){
               if (purchase_doc.notices_c == null)
                  purchase_doc.notices_c = 1
             }
             else
                purchase_doc.notices_c = null
             // ----------------------------------------------------------------


             // ----------------------------------------------------------------
             if (purchase_doc.heads_up_a == 1){
                 if (purchase_doc.desired_contract_terms_a == null)
                    purchase_doc.desired_contract_terms_a = 1
             }
             else
                purchase_doc.desired_contract_terms_a = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_a == 1){
                 if (purchase_doc.desired_contract_terms_b == null)
                    purchase_doc.desired_contract_terms_b = 1
             }
             else
                purchase_doc.desired_contract_terms_b = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_a == 2){
                 if (purchase_doc.desired_contract_terms_c == null)
                    purchase_doc.desired_contract_terms_c = 1
             }
             else
                purchase_doc.desired_contract_terms_c = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_b == 1){
                 if (purchase_doc.desired_contract_terms_d == null)
                    purchase_doc.desired_contract_terms_d = 1
             }
             else
                purchase_doc.desired_contract_terms_d = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_b == 2){
                 if (purchase_doc.desired_contract_terms_e == null)
                    purchase_doc.desired_contract_terms_e = 1
             }
             else
                purchase_doc.desired_contract_terms_e = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_b == 3){
                 if (purchase_doc.desired_contract_terms_f == null)
                    purchase_doc.desired_contract_terms_f = 1
             }
             else
                purchase_doc.desired_contract_terms_f = null
             // ----------------------------------------------------------------
             if (purchase_doc.contract_b == 4){
                 if (purchase_doc.desired_contract_terms_g == null)
                    purchase_doc.desired_contract_terms_g = 1
             }
             else
                purchase_doc.desired_contract_terms_g = null
             // ----------------------------------------------------------------



            // -----------------------------------------------------------------
            if (this.purchase_sale.urbanization_area && this.purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_c == 1){
                if (purchase_doc.desired_contract_terms_h == null)
                    purchase_doc.desired_contract_terms_h = 1
            }
            else
                purchase_doc.desired_contract_terms_h = null
            // -----------------------------------------------------------------
            if (this.purchase_sale.urbanization_area && this.purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_c == 2){
                if (purchase_doc.desired_contract_terms_i == null)
                    purchase_doc.desired_contract_terms_i = 1
            }
            else
                purchase_doc.desired_contract_terms_i = null
            // -----------------------------------------------------------------
            if (this.purchase_sale.urbanization_area && this.purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_d == 1){
                if (purchase_doc.desired_contract_terms_j == null)
                    purchase_doc.desired_contract_terms_j = 1
            }
            else
                purchase_doc.desired_contract_terms_j = null
            // -----------------------------------------------------------------
            if (this.purchase_sale.urbanization_area && this.purchase_sale.urbanization_area_sub_2 && purchase_doc.contract_d == 2){
                if (purchase_doc.desired_contract_terms_k == null)
                    purchase_doc.desired_contract_terms_k = 1
            }
            else
                purchase_doc.desired_contract_terms_k = null
            // -----------------------------------------------------------------



            // -----------------------------------------------------------------
            if (this.purchase_third_person_occupied == 1){
                if (purchase_doc.desired_contract_terms_m == null)
                    purchase_doc.desired_contract_terms_m = 1
            }
            else
                purchase_doc.desired_contract_terms_m = null
            // -----------------------------------------------------------------



            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 1) {
              if (purchase_doc.desired_contract_terms_n == null) {
                  purchase_doc.desired_contract_terms_n = 1
              }
            }
            else
                purchase_doc.desired_contract_terms_n = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)) {
                   if (purchase_doc.desired_contract_terms_o == null) {
                      purchase_doc.desired_contract_terms_o = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_o = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)) {
                   if (purchase_doc.desired_contract_terms_p == null) {
                      purchase_doc.desired_contract_terms_p = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_p = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)) {
                   if (purchase_doc.desired_contract_terms_q == null) {
                      purchase_doc.desired_contract_terms_q = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_q = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)) {
                   if (purchase_doc.desired_contract_terms_r == null) {
                      purchase_doc.desired_contract_terms_r = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_r = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 3 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_e == 3)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_e == 3)) {
                   if (purchase_doc.desired_contract_terms_s == null) {
                      purchase_doc.desired_contract_terms_s = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_s = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 2)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_e == 3)) {
                   if (purchase_doc.desired_contract_terms_t == null) {
                      purchase_doc.desired_contract_terms_t = 1
                   }
            }
            else
                purchase_doc.desired_contract_terms_t = null
            // -----------------------------------------------------------------



            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 0) {
                if (purchase_doc.desired_contract_terms_u == null) {
                    purchase_doc.desired_contract_terms_u = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_u = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1)) {
                    if (purchase_doc.desired_contract_terms_v == null) {
                        purchase_doc.desired_contract_terms_v = 1
                    }
            }
            else
                purchase_doc.desired_contract_terms_v = null
            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 0) {
                if (purchase_doc.desired_contract_terms_w == null) {
                    purchase_doc.desired_contract_terms_w = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_w = null
            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1) {
                if (purchase_doc.desired_contract_terms_x == null) {
                    purchase_doc.desired_contract_terms_x = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_x = null
            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 0) {
                if (purchase_doc.desired_contract_terms_y == null) {
                    purchase_doc.desired_contract_terms_y = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_y = null
            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 0) {
                if (purchase_doc.desired_contract_terms_z == null) {
                    purchase_doc.desired_contract_terms_z = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_z = null
            // -----------------------------------------------------------------
            if (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1) {
                if (purchase_doc.desired_contract_terms_aa == null) {
                    purchase_doc.desired_contract_terms_aa = 1
                }
            }
            else
                purchase_doc.desired_contract_terms_aa = null
            // -----------------------------------------------------------------
            if ((purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
                 || (purchase_doc.properties_description_a == 2 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 1 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)
                 || (purchase_doc.properties_description_a == 4 && purchase_doc.properties_description_b == 2 && purchase_doc.properties_description_c == 2 && purchase_doc.properties_description_d == 1 && purchase_doc.properties_description_f == 1)) {
                    if (purchase_doc.desired_contract_terms_ab == null) {
                        purchase_doc.desired_contract_terms_ab = 1
                    }
            }
            else
                purchase_doc.desired_contract_terms_ab = null
            // -----------------------------------------------------------------



            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_size_contract_a)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_d)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_size_contract_a)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_a)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_i && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_i && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_a == null) {
                        purchase_doc.optional_items_a = 1
                    }
            }
            else
                purchase_doc.optional_items_a = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_b && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_type_sub1_contract == 2)
                      || (purchase_doc.road_type_contract_b && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_i && purchase_doc.road_type_sub3_contract == 1)) {
                    if (purchase_doc.optional_items_b == null) {
                        purchase_doc.optional_items_b = 1
                    }
            }
            else
                purchase_doc.optional_items_b = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_b && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_i && purchase_doc.road_type_sub3_contract == 1)) {
                    if (purchase_doc.optional_items_c == null) {
                        purchase_doc.optional_items_c = 1
                    }
            }
            else
                purchase_doc.optional_items_c = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_d == null) {
                        purchase_doc.optional_items_d = 1
                    }
            }
            else
                purchase_doc.optional_items_d = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_a && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d))
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_e == null) {
                        purchase_doc.optional_items_e = 1
                    }
            }
            else
                purchase_doc.optional_items_e = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_size_contract_a)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_size_contract_a)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_a && purchase_doc.road_size_contract_a)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_f == null) {
                        purchase_doc.optional_items_f = 1
                    }
            }
            else
                purchase_doc.optional_items_f = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_a)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_g == null) {
                        purchase_doc.optional_items_g = 1
                    }
            }
            else
                purchase_doc.optional_items_g = null
            // -----------------------------------------------------------------
            if (this.purchase_sale.urbanization_area && this.purchase_sale.urbanization_area_sub_1) {
                    if (purchase_doc.optional_items_h == null) {
                        purchase_doc.optional_items_h = 1
                    }
            }
            else
                purchase_doc.optional_items_h = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_b && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_c && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_e && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_size_contract_a && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_f && purchase_doc.road_type_sub2_contract_b && (purchase_doc.road_size_contract_b || purchase_doc.road_size_contract_c || purchase_doc.road_size_contract_d) && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_g && purchase_doc.road_type_sub2_contract_b && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 2)
                      || (purchase_doc.road_type_contract_i && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_j == null) {
                        purchase_doc.optional_items_j = 1
                    }
            }
            else
                purchase_doc.optional_items_j = null
            // -----------------------------------------------------------------
            if ((purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 1)
                      || (purchase_doc.road_type_contract_h && purchase_doc.road_type_sub3_contract == 2)) {
                    if (purchase_doc.optional_items_k == null) {
                        purchase_doc.optional_items_k = 1
                    }
            }
            else
                purchase_doc.optional_items_k = null
            // -----------------------------------------------------------------
           }
         },
       },

       // ------------------------------------------------------------------
       // Vue Methods
       // ------------------------------------------------------------------
       methods: {
         sub2_contract: function(purchase_doc){
             if ((purchase_doc.road_type_contract_a || purchase_doc.road_type_contract_d)
             && (!purchase_doc.road_type_contract_b && !purchase_doc.road_type_contract_c
             && !purchase_doc.road_type_contract_e && !purchase_doc.road_type_contract_f
             && !purchase_doc.road_type_contract_g
             && !purchase_doc.road_type_contract_h && !purchase_doc.road_type_contract_i)){
                purchase_doc.road_type_sub2_contract_a = 1
            }
            else if ((purchase_doc.road_type_contract_a || purchase_doc.road_type_contract_d)
            && (purchase_doc.road_type_contract_b || purchase_doc.road_type_contract_c
            || purchase_doc.road_type_contract_e || purchase_doc.road_type_contract_f
            || purchase_doc.road_type_contract_g
            || purchase_doc.road_type_contract_h || purchase_doc.road_type_contract_i)) {
                purchase_doc.road_type_sub2_contract_a = 1
            }
            else if (!purchase_doc.road_type_contract_a && !purchase_doc.road_type_contract_d) {
                purchase_doc.road_type_sub2_contract_a = 0
            }

             if ((purchase_doc.road_type_contract_h || purchase_doc.road_type_contract_i)
             && (!purchase_doc.road_type_contract_a && !purchase_doc.road_type_contract_b
             && !purchase_doc.road_type_contract_c && !purchase_doc.road_type_contract_d
             && !purchase_doc.road_type_contract_e && !purchase_doc.road_type_contract_f
             && !purchase_doc.road_type_contract_g)){
                purchase_doc.road_type_sub2_contract_b = 1
            }
            else if ((purchase_doc.road_type_contract_h || purchase_doc.road_type_contract_i)
            && (purchase_doc.road_type_contract_a || purchase_doc.road_type_contract_b
            || purchase_doc.road_type_contract_c || purchase_doc.road_type_contract_d
            || purchase_doc.road_type_contract_e || purchase_doc.road_type_contract_f
            || purchase_doc.road_type_contract_g)) {
                purchase_doc.road_type_sub2_contract_b = 1
            }
            else if (!purchase_doc.road_type_contract_h && !purchase_doc.road_type_contract_i) {
                purchase_doc.road_type_sub2_contract_b = 0
            }
         },
         confirmation: function(value, event){
           if (value == 1) {
             let confirmed = confirm('チェックを外します。本当によろしいですか？')
             if (!confirmed) event.preventDefault();
           }
         },
         addOptionalMemo: function(){
           this.purchase_doc_optional_memos.push({id: null, pj_purchase_doc_id: null, content: null})
         },
         removeOptionalMemo: function(index){
           let id = this.purchase_doc_optional_memos[index].id;
           let confirmed = false;

           if (id) confirmed = confirm('@lang('label.confirm_remove_control')');
           else this.purchase_doc_optional_memos.splice(index, 1);

           if (confirmed) {
               // --------------------------------------------------------------
               // handle delete request
               // --------------------------------------------------------------
               let remove_purchase_doc_optional_memo = axios.delete('{{ $remove_purchase_doc_optional_memo }}', {
                   data: this.purchase_doc_optional_memos[index]
               });
               let vm = this
               // --------------------------------------------------------------
               // --------------------------------------------------------------
               // handle success response
               // --------------------------------------------------------------
               remove_purchase_doc_optional_memo.then(function (response) {
                 vm.purchase_doc_optional_memos.splice(index, 1);
                 $.toast({
                   heading: '成功', icon: 'success',
                   position: 'top-right', stack: false, hideAfter: 3000,
                   text: response.data.message,
                   position: { right: 18, top: 68 }
                 });
               })
               //---------------------------------------------------------------
               // --------------------------------------------------------------
               // handle error response
               // --------------------------------------------------------------
               remove_purchase_doc_optional_memo.catch(function (error) {
                 if (error.response.status == 422){ var error_message = [error.response.data.message]; }
                 if (error.response.status == 500){ var error_message = [error.response.data.message, error.response.data.error]; }
                   $.toast({
                       heading: '失敗', icon: 'error',
                       position: 'top-right', stack: false, hideAfter: 3000,
                       text: error_message,
                       position: { right: 18, top: 68 }
                   });
               });
               // --------------------------------------------------------------
           }
         },
         formSubmited: function(index){
           this.initial.submited_index = index
           $('form').submit()
         },
         approvalRequest: function(){
           this.initial.approval = true

           // handle update request
           // ----------------------------------------------------------
           let data = { updates: { examination: this.inspection.examination } }
           let request = axios.post(this.initial.inspection_url, data)
           vm = this
           // ----------------------------------------------------------

           // handle success response
           // ----------------------------------------------------------
           request.then(function(response){
             // console.log(response);
             // ----------------------------------------------------------
             $.toast({
               heading: '成功', icon: 'success',
               position: 'top-right', stack: false, hideAfter: 3000,
               // text: response.data.message,
               position: { right: 18, top: 68 }
             });
           })
           // ----------------------------------------------------------

           // handle error response
           // ----------------------------------------------------------
           request.catch(function(error){
             var error_message = []
             if (error.response.status == 422) error_message.push(error.response.data.message)
             if (error.response.status == 500) error_message.push(error.response.data.message, error.response.data.error)
             $.toast({
               heading: '失敗', icon: 'error',
               position: 'top-right', stack: false, hideAfter: 3000,
               text: error_message,
               position: { right: 18, top: 68 }
             });
           })
           // ----------------------------------------------------------

           // always execute function
           // ----------------------------------------------------------
           request.finally(function () {
             vm.initial.approval = false
           });
           // ----------------------------------------------------------

         }
       }
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
             if (vm.initial.submited_index == 0) vm.initial.submited = [true, false, false, false]
             if (vm.initial.submited_index == 1) vm.initial.submited = [false, true, false, false]
             if (vm.initial.submited_index == 2) vm.initial.submited = [false, false, true, false]
             if (vm.initial.submited_index == 3) {
               let confirmed = confirm('本当にリクエストしますか？');
               if (confirmed) vm.initial.submited = [false, false, false, true]
             }
             let data = {
               initial: vm.initial,
               purchase_doc: vm.purchase_doc,
               purchase_doc_optional_memos: vm.purchase_doc_optional_memos,
               purchase_target_contractors_group_by_name: vm.purchase_target_contractors_group_by_name,
             }
               // handle update request
               // ----------------------------------------------------------
               let request = axios.post('{{ $form_action }}', data)
               // ----------------------------------------------------------

               // handle success response
               // ----------------------------------------------------------
               request.then(function(response){
                 // console.log(response.data);

                 let show_time = 3000;
                 if (vm.initial.submited_index == 1) show_time = false
                 $.toast({
                   heading: '成功', icon: 'success',
                   position: 'top-right', stack: false, hideAfter: show_time,
                   text: response.data.message,
                   position: { right: 18, top: 68 }
                 });

                 // update data to response data
                 vm.purchase_doc = response.data.data.purchase_doc
                 vm.project = response.data.data.project
                 vm.inspection = response.data.data.inspection
                 vm.initial.inspection_url = response.data.data.inspection_url
                 if (response.data.data.purchase_doc_optional_memos.length > 0) vm.purchase_doc_optional_memos = response.data.data.purchase_doc_optional_memos
                 vm.purchase_target_contractors_group_by_name = response.data.data.purchase_target_contractors_group_by_name

                 //  Add scroll-top on save
                 if( !vm.initial.submited_index ) scrollTop();

                 // if export report button clicked
                 if (vm.initial.submited_index == 1) {
                    vm.initial.submited = [false, true, false, false]
                   let request = axios.post('{{ $contract_contract_report }}', {
                     project: {{ $project->id }},
                     target: {{ $purchase_target->id }}
                   })
                   // console.log(vm.initial.submited);

                   // ------------------------------------------
                    // Receive the request response
                    // ------------------------------------------
                    request.then( function( response ){
                        if( response.data && response.data.status ){
                            var data = response.data;
                            var option = $.extend( {}, toast );
                            // ----------------------------------
                            if( 'success' == data.status ){
                                // ------------------------------
                                // console.log(data.data);
                                // ------------------------------
                                // Download the file
                                // ------------------------------
                                if( data.report && data.report.length ){
                                    // --------------------------
                                    var download = axios({
                                        url: data.report, method: 'GET',
                                        responseType: 'blob', // important
                                        headers: { 'Accept': 'application/vnd.ms-excel' }
                                    });
                                    // --------------------------
                                    download.then( function( response ){
                                        if( response.data ){
                                            // ------------------
                                            var url = window.URL.createObjectURL( new Blob([ response.data ]));
                                            var link = document.createElement('a');
                                            link.href = url;
                                            link.setAttribute( 'download', data.filename );
                                            document.body.appendChild(link);
                                            link.click();
                                            window.URL.revokeObjectURL(url);
                                            link.remove();
                                            // ------------------

                                            // ------------------
                                            // option.icon = 'success';
                                            // option.heading = '@lang('label.success')';
                                            // option.text = '{{ __('label.report_downloaded') }}';
                                            // $.toast( option );
                                            setTimeout(function(){
                                                $.toast({
                                                  heading: '成功', icon: 'success',
                                                  position: 'top-right', stack: false, hideAfter: false,
                                                  text: response.data.message,
                                                  position: { right: 18, top: 68 }
                                              }).reset()
                                          }, 1000);
                                            // ------------------
                                        }
                                    });
                                    // --------------------------
                                    download.finally( function(){
                                        vm.initial.submited = [false, false, false, false]
                                    });
                                    // --------------------------
                                }
                                // ------------------------------
                            }
                            // ----------------------------------

                            // ----------------------------------
                            else {
                                // ------------------------------
                                vm.initial.submited = [false, false, false, false]
                                option.text = '{{ __('label.report_failed') }}';
                                $.toast( option );
                                // ------------------------------
                            }
                            // ----------------------------------
                        }
                    });
                    // ------------------------------------------
                 }

                 // if export checklist button clicked
                 if (vm.initial.submited_index == 2) {
                    vm.initial.submited = [false, false, true, false]
                   let request = axios.post('{{ $contract_checklist_report }}', {
                     project: {{ $project->id }},
                     target: {{ $purchase_target->id }}
                   })

                    // ------------------------------------------
                    // Receive the request response
                    // ------------------------------------------
                    request.then( function( response ){
                        if( response.data && response.data.status ){
                            var data = response.data;
                            var option = $.extend( {}, toast );
                            // ----------------------------------
                            if( 'success' == data.status ){
                                // ------------------------------

                                // ------------------------------
                                // Download the file
                                // ------------------------------
                                if( data.report && data.report.length ){
                                    // --------------------------
                                    var download = axios({
                                        url: data.report, method: 'GET',
                                        responseType: 'blob', // important
                                        headers: { 'Accept': 'application/vnd.ms-excel' }
                                    });
                                    // --------------------------
                                    download.then( function( response ){
                                        if( response.data ){
                                            // ------------------
                                            var url = window.URL.createObjectURL( new Blob([ response.data ]));
                                            var link = document.createElement('a');
                                            link.href = url;
                                            link.setAttribute( 'download', data.filename );
                                            document.body.appendChild(link);
                                            link.click();
                                            window.URL.revokeObjectURL(url);
                                            link.remove();
                                            // ------------------

                                            // ------------------
                                            // option.icon = 'success';
                                            // option.heading = '@lang('label.success')';
                                            // option.text = '{{ __('label.report_downloaded') }}';
                                            // $.toast( option );
                                            // ------------------
                                        }
                                    });
                                    // --------------------------
                                    download.finally( function(){
                                        vm.initial.submited = [false, false, false, false]
                                    });
                                    // --------------------------
                                }
                                // ------------------------------
                            }
                            // ----------------------------------

                            // ----------------------------------
                            else {
                                // ------------------------------
                                vm.initial.submited = [false, false, false, false]
                                option.text = '{{ __('label.report_failed') }}';
                                $.toast( option );
                                // ------------------------------
                            }
                            // ----------------------------------
                        }
                    });
                    // ------------------------------------------
                 }

               })
               // ----------------------------------------------------------

               // handle error response
               // ----------------------------------------------------------
               request.catch(function(error){
                 var error_message = []
                 if (error.response.status == 422) error_message.push(error.response.data.message)
                 if (error.response.status == 500) error_message.push(error.response.data.message, error.response.data.error)
                 $.toast({
                   heading: '失敗', icon: 'error',
                   position: 'top-right', stack: false, hideAfter: 3000,
                   text: error_message,
                   position: { right: 18, top: 68 }
                 });
               })
               // ----------------------------------------------------------

               // always execute function
               // ----------------------------------------------------------
               request.finally(function () {
                   if (vm.initial.submited_index == 0 || vm.initial.submited_index == 3) {
                    vm.initial.submited = [false, false, false, false]
                   }
               });
               // ----------------------------------------------------------
           }
           // --------------------------------------------------------------
       }).on('form:submit', function(){ return false });
   })

   // ----------------------------------------------------------------------
   // Custom function refresh validator
   // ----------------------------------------------------------------------
   var refreshParsley = function(vm){
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
