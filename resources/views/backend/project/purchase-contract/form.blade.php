@extends('backend._base.content_project')

@section('preloader')
    <transition name="preloader">
        <div class="preloader preloader-fullscreen d-flex justify-content-center align-items-center" v-if="default_value.initial.loading">
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
    <li class="breadcrumb-item"><a href="{{ route('project.purchase.contract', $project->id) }}">仕入契約</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
</ol>
@endsection

@section('content')
@php $building_no_val = []; @endphp
@foreach ($purchase_targets as $index => $purchase_target)
  <form id="form-{{ $index }}" data-parsley class="parsley-minimal">
     <div class="cards-purchase">
        <div class="card card-project" style="border-color:#191970; min-width:1100px;">
           <div class="card-header" style="color:#fff; background:#191970;">@lang('project_purchase_contract.purchase')No.{{ $index + 1 }}</div>
           <div class="card-body">
              @include('backend.project.purchase-contract.form.purchase-information')
              @include('backend.project.purchase-contract.form.building-information')
              @include('backend.project.purchase-contract.form.intermediary.purchase-contract-mediation')
              @include('backend.project.purchase-contract.form.intermediary.purchase-contract-price')
              @include('backend.project.purchase-contract.form.intermediary.purchase-contract-deposit')
              @include('backend.project.purchase-contract.form.intermediary.purchase-contract-delivery')
              @include('backend.project.purchase-contract.form.contract-creation')
           </div>
           <!--row-->
        </div>
        <!--card-->
     </div>
  </form>
@endforeach

<div class="row">
   <div class="col-12">
      <table class="table-bordered table-small buypurchase_table ml-1 mb-2">
         <tr>
            <th>@lang('project_purchase_contract.trading_price')</th>
            <td>
              <template>
                <currency-input class="form-control form-control-w-lg" :name="name" :id="name" v-model="purchase_targets[0].purchase_contract.contract_price_total"
                    :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                    data-id="A121-68" readonly="readonly"
                    data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus />
              </template>
              @lang('project_purchase_contract.circle')
            </td>
         </tr>
      </table>
   </div>
</div>

@if ($editable)
  <div class="bottom mt-2 mb-5 text-center">
      <button type="submit" @click="submitForms" class="btn btn-wide btn-info px-4">
          <i v-if="!submited" class="fas fa-save"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          保存
      </button>
  </div>
@endif


@endsection
@push('vue-scripts')
<script>
   // ----------------------------------------------------------------------
   mixin = {
       // ------------------------------------------------------------------
       // Mounted state
       // ------------------------------------------------------------------
       mounted: function(){
         let building_numbers = @json($building_numbers);

         this.purchase_targets.forEach((purchase_target, i) => {
           // set contract building_number value (A121-11)
           if (building_numbers) {
             if (!typeof(building_numbers[i]) == undefined) {
              purchase_target.purchase_contract.contract_building_number = building_numbers[i][0]
             }
           }

           // button A121-21 disabled
           // ------------------------------------------------------------------
           if (purchase_target.purchase_not_create_documents == 1) this.default_value.data[i].is_disabled = true
           else this.default_value.data[i].is_disabled = false
           // ------------------------------------------------------------------

           // copy A121-23 value to A121-51
           this.default_value.data[i].acceptance_price = purchase_target.purchase_contract.contract_price
           // function to set A121-44 value
           this.purchaseContractMediationCalculation(i)
         });

         // set A121-68 value
         this.contractPriceTotal()

         // refresh parsley form validation
         refreshParsley(this);

         // switch loading state
         this.default_value.initial.loading = false;

         // triger event on loaded
         $(document).trigger( 'vue-loaded', this );
       },
       computed: {

       },

       // ------------------------------------------------------------------
       // Vue Data Binding
       // ------------------------------------------------------------------
       data: function(){
           // initial purchase response value
           // ------------------------------------------------------------------
            // option disabled for A121-46, A121-58, A121-63
            // -----------------------------------------------------------------
            let option_is_disabled = true
            @if (Auth::user()->user_role->name == 'global_admin' || Auth::user()->user_role->name == 'registration_manager' || Auth::user()->user_role->name == 'accountant')
              option_is_disabled = false
            @endif
            // -----------------------------------------------------------------
           let submited = false
           let default_value = {
             valid_counter: 0,
             editable: @json( $editable ),
             initial : {
               loading: true,
               is_readonly: true, is_disabled: true,
               acceptance_price: 0, purchase_contract_mediation_calculation: 0,
               button_action: "save", contract_create_submited: false,
               response_submited: false,
             },
             data: [],
           }

           let project = {
             id: null,
           }
           let purchase_target_buildings = [{
             id: null, pj_purchase_target_id: null,
             kind: null, exist_unregistered: null,
             purchase_third_person_occupied: null,
           }]
           let purchase_contract = {
              // purchase contract
              id: null, contract_building_number: null,
              contract_building_kind: null, contract_building_unregistered: null,
              contract_price: null, contract_deposit: null,
              mediation: null, seller: null,
              seller_broker_company_id: null, contract_date: null,
              contract_payment_date: null, contract_price_building: 0,
              contract_price_building_no_tax: null, contract_delivery_money: 0,
              contract_delivery_date: null, contract_delivery_status: 0,
              contract_delivery_bank: 0, contract_delivery_note: null,
              contract_not_create_documents: null, contract_price_total: 0,
              // has many purchase contract mediations
              purchase_contract_mediations: [{
                  id: null, dealtype: 0,
                  balance: 0, reward: 0,
                  date: null, status: 0,
                  bank: 0, trader_company_id: null,
              }],
              // has many purchase contract deposits
              purchase_contract_deposits: [{
                  id: null, price: null,
                  date: null, status: 0,
                  account: 0, note: null,
                  pj_purchase_contract_id: null,
              }],
           }
           let purchase_contract_mediations = [{
               id: null, dealtype: 0,
               balance: 0, reward: 0,
               date: null, status: 0,
               bank: 0, trader_company_id: null,
           }]

           let real_estates = [{
               id: null, name:'',
           }]
           let company_bank_accounts = [{
             id: null,
           }]
           let banks = [{
             id: null,
           }]
           // ------------------------------------------------------------------

           // init data from db to json
           // ------------------------------------------------------------------
           let db_project = @json($project);
           let db_real_estates = @json($real_estates);
           let db_company_bank_accounts = @json($company_bank_accounts);
           let db_banks = @json($banks);

           let db_purchase_targets = @json($purchase_targets);
           db_purchase_targets.forEach((purchase_target, i) => {
             // default value
             default_value.data.push(_.cloneDeep(default_value.initial))
             // seller_broker_companies.data.push([])

             // if purchase_contract == null
             if (purchase_target.purchase_contract == null) {
               purchase_target.purchase_contract = _.cloneDeep(purchase_contract)
             }

             // if purchase_contract_mediations == null
             if (purchase_target.purchase_contract.purchase_contract_mediations.length == 0) {
               purchase_target.purchase_contract.purchase_contract_mediations = _.cloneDeep(purchase_contract.purchase_contract_mediations)
             }
             this.setBackground(purchase_target)

             // if purchase_contract_deposits == null
             if (purchase_target.purchase_contract.purchase_contract_deposits.length == 0) {
               purchase_target.purchase_contract.purchase_contract_deposits = _.cloneDeep(purchase_contract.purchase_contract_deposits)
               purchase_target.purchase_contract.purchase_contract_deposits[0].price = purchase_target.purchase_contract.contract_deposit ?? 0
             }

             // if purchase target building == null
             if (purchase_target.purchase_target_buildings.length == 0) {
               purchase_target.purchase_target_buildings[0] = _.cloneDeep(purchase_target_buildings)
             }
           });
           // ------------------------------------------------------------------

           // assign value from db
           // ------------------------------------------------------------------
           if (db_project != null)                  Object.assign(project, db_project)
           if (db_real_estates != null)             Object.assign(real_estates, db_real_estates)
           if (db_company_bank_accounts != null)    Object.assign(company_bank_accounts, db_company_bank_accounts)
           if (db_banks != null)                    Object.assign(banks, db_banks)
           let sale_mediation_inputed   = @json( $sale_mediation_inputed );
           let in_house_n_real_estates  = @json( $in_house_n_real_estates );
           // ------------------------------------------------------------------           

           return {
             option_is_disabled: option_is_disabled,
             submited: submited,
             default_value: default_value,
             project: project,
             purchase_targets: db_purchase_targets,
             company_bank_accounts: company_bank_accounts,
             banks: banks,
             real_estates: real_estates,
             purchase_contract_mediations: purchase_contract_mediations,
             sale_mediation_inputed: sale_mediation_inputed,
             in_house_n_real_estates: in_house_n_real_estates,
             contract_delivery_money_auto: false,
           };
       },

       // ------------------------------------------------------------------
       // Vue watch
       // ------------------------------------------------------------------
       watch: {
         purchase_targets: {
           deep: true,
           handler: function(purchase_targets){
             refreshParsley(this)
             resetParsley(this)

             purchase_targets.forEach((purchase_target, purchase_target_key) => {
               // if A121-13 checked then set A121-14 to null
               if (purchase_target.purchase_contract.contract_building_unregistered == 0) purchase_target.purchase_contract.contract_building_unregistered_kind = null
               // if A121-33 value = 1 then set A121-34 to null
               if (purchase_target.purchase_contract.seller == 1) purchase_target.purchase_contract.seller_broker_company_id = null

               this.setBackground(purchase_target)

               // set seller_broker_companies option
               // this.setSellerBrokerCompanies(purchase_target_key, purchase_target.purchase_contract.purchase_contract_mediations)

               // if purchase_contract_mediations == null
               if (purchase_target.purchase_contract.purchase_contract_mediations.length == 0) {
                 purchase_target.purchase_contract.purchase_contract_mediations = _.cloneDeep(this.purchase_contract_mediations)
               }

               purchase_target.purchase_contract.purchase_contract_mediations.forEach((purchase_contract_mediation, i) => {
                 // if A121-42 = 3 or none set purchase contract mediation to 0
                 if (purchase_contract_mediation.balance == 3) {
                   purchase_contract_mediation.bank = 0
                 }
               });

               // calculation for A121-61 value
               let total_deposit = 0
               purchase_target.purchase_contract.purchase_contract_deposits.forEach((purchase_contract_deposit, i) => {
                  total_deposit += purchase_contract_deposit.price
               });
               if(purchase_target.purchase_contract.contract_delivery_money == parseInt(purchase_target.purchase_contract.contract_price) - parseInt(total_deposit)) {
                this.contract_delivery_money_auto = true;
               } else {
                this.default_value.data[purchase_target_key].is_readonly = false;
               }
               if(this.contract_delivery_money_auto) {
                purchase_target.purchase_contract.contract_delivery_money = parseInt(purchase_target.purchase_contract.contract_price) - parseInt(total_deposit)
                this.default_value.data[purchase_target_key].is_readonly = true;
               }
             });

           }
         },
         real_estates: {
           deep: true,
           handler: function(data){

           }
         }
       },

       // ------------------------------------------------------------------
       // Vue Methods
       // ------------------------------------------------------------------
       methods: {
         // setSellerBrokerCompanies: function(purchase_target_key, purchase_contract_mediations){
         //   let seller_broker_company = [];
         //   this.seller_broker_companies.data[purchase_target_key] = []
         //   purchase_contract_mediations.forEach((purchase_contract_mediation, i) => {
         //     seller_broker_company = this.real_estates.filter(function(data) {
         //         return data.id == purchase_contract_mediation.trader_company_id
         //     })
         //     if (seller_broker_company.length > 0)
         //      this.seller_broker_companies.data[purchase_target_key].push(_.cloneDeep(seller_broker_company[0]))
         //   });
         // },
         setBackground: function(purchase_target){
           purchase_target.purchase_contract.purchase_contract_mediations.forEach((purchase_contract_mediation, i) => {
              if (purchase_contract_mediation.balance == 1) purchase_contract_mediation.background = '#ADD8E6'
              else if (purchase_contract_mediation.balance == 2) purchase_contract_mediation.background = '#FF0000'
              else if (purchase_contract_mediation.balance == 3) purchase_contract_mediation.background = '#7CFC00'
              else purchase_contract_mediation.background = ''
           });
         },
         addProjectPurchaseMediation: function(purchase_target){
           purchase_target.purchase_contract.purchase_contract_mediations.push({id: null, reward: 0, pj_purchase_contract_id: null})
         },
         removeProjectPurchaseMediation: function(purchase_target, index_mediation){
           let id = purchase_target.purchase_contract.purchase_contract_mediations[index_mediation].id;
           let confirmed = false;

           if (id) confirmed = confirm('@lang('label.confirm_remove_control')');
           else purchase_target.purchase_contract.purchase_contract_mediations.splice(index_mediation, 1);

           if (confirmed) {
               // --------------------------------------------------------------
               // handle delete request
               // --------------------------------------------------------------
               let remove_purchase_project_mediation = axios.delete('{{ $remove_purchase_project_mediation }}', {
                   data: purchase_target.purchase_contract.purchase_contract_mediations[index_mediation]
               });
               // --------------------------------------------------------------
               // --------------------------------------------------------------
               // handle success response
               // --------------------------------------------------------------
               remove_purchase_project_mediation.then(function (response) {
                 purchase_target.purchase_contract.purchase_contract_mediations.splice(index_mediation, 1);
                 $.toast({
                   heading: '成功', icon: 'success',
                   position: 'top-right', stack: false, hideAfter: 3000,
                   text: __('label.success_delete_message'),
                   position: { right: 18, top: 68 }
                 });
               })
               // --------------------------------------------------------------
               // --------------------------------------------------------------
               // handle error response
               // --------------------------------------------------------------
               remove_purchase_project_mediation.catch(function (error) {
                   $.toast({
                       heading: '失敗', icon: 'error',
                       position: 'top-right', stack: false, hideAfter: 3000,
                       text: [error.response.data.message, error.response.data.error],
                       position: { right: 18, top: 68 }
                   });
               });
               // --------------------------------------------------------------
           }
         },
         addProjectPurchaseContractDeposit: function(purchase_target){
           purchase_target.purchase_contract.purchase_contract_deposits.push({id: null, price: null})
         },
         removeProjectPurchaseContractDeposit: function(purchase_target, index_contract_deposit){
           let id = purchase_target.purchase_contract.purchase_contract_deposits[index_contract_deposit].id;
           let confirmed = false;

           if (id) confirmed = confirm('@lang('label.confirm_remove_control')');
           else purchase_target.purchase_contract.purchase_contract_deposits.splice(index_contract_deposit, 1);

           if (confirmed) {
               // --------------------------------------------------------------
               // handle delete request
               // --------------------------------------------------------------
               let remove_purchase_project_deposit = axios.delete('{{ $remove_purchase_project_deposit }}', {
                   data: purchase_target.purchase_contract.purchase_contract_deposits[index_contract_deposit]
               });
               // --------------------------------------------------------------
               // --------------------------------------------------------------
               // handle success response
               // --------------------------------------------------------------
               remove_purchase_project_deposit.then(function (response) {
                 purchase_target.purchase_contract.purchase_contract_deposits.splice(index_contract_deposit, 1);
                 $.toast({
                   heading: '成功', icon: 'success',
                   position: 'top-right', stack: false, hideAfter: 3000,
                   text: __('label.success_delete_message'),
                   position: { right: 18, top: 68 }
                 });
               })
               //---------------------------------------------------------------
               // --------------------------------------------------------------
               // handle error response
               // --------------------------------------------------------------
               remove_purchase_project_deposit.catch(function (error) {
                   $.toast({
                       heading: '失敗', icon: 'error',
                       position: 'top-right', stack: false, hideAfter: 3000,
                       text: [error.response.data.message, error.response.data.error],
                       position: { right: 18, top: 68 }
                   });
               });
               // --------------------------------------------------------------
           }
         },
         editableInput: function(purchase_target, index){
           if (this.default_value.data[index].is_readonly == false) {
             this.default_value.data[index].is_readonly = true;
             this.contract_delivery_money_auto = true;
             // calculation for A121-61 value
            //  let total_deposit = 0
            //    purchase_target.purchase_contract.purchase_contract_deposits.forEach((purchase_contract_deposit, i) => {
            //       total_deposit += purchase_contract_deposit.price
            //    });
            //    purchase_target.purchase_contract.contract_delivery_money = parseInt(purchase_target.purchase_contract.contract_price) - parseInt(total_deposit);
           } else if (this.default_value.data[index].is_readonly == true) {
             this.default_value.data[index].is_readonly = false;
             this.contract_delivery_money_auto = false;
           }
         },
         // copy data from A121-23 to A121-51
         keyupFromPurchaseContractPrice: function(purchase_target, index){
           this.default_value.data[index].acceptance_price = purchase_target.purchase_contract.contract_price
           this.purchaseContractMediationCalculation(index)
           this.contractPriceTotal()
         },
         // copy data from A121-24 to A121-56
         keyupFromPurchaseContractDeposit: function(purchase_target){
           purchase_target.purchase_contract.purchase_contract_deposits[0].price = purchase_target.purchase_contract.contract_deposit
         },
         // copy data purchase contract mediation calculation result to A121-43
         copyFromPurchaseContractMediationCalculation: function(purchase_target, index, index_mediation){
           purchase_target.purchase_contract.purchase_contract_mediations[index_mediation].reward = this.default_value.data[index].purchase_contract_mediation_calculation
         },
         // copy data from A121-22 to A121-23
         copyToPurchaseContractPrice: function(purchase_target, index, value){
           purchase_target.purchase_contract.contract_price = value
           this.keyupFromPurchaseContractPrice(purchase_target, index)
           this.contractPriceTotal()
         },
         // copy data from A121-24 to A121-25
         copyToPurchaseContractDeposit: function(purchase_target, value){
           purchase_target.purchase_contract.contract_deposit = value
           this.keyupFromPurchaseContractDeposit(purchase_target)
         },
         // calculation from A121-51
         purchaseContractMediationCalculation: function(index){
           this.default_value.data[index].purchase_contract_mediation_calculation = Math.floor( ( (this.default_value.data[index].acceptance_price * 0.03) + 60000) * 1.1 );
         },
         // set A121-68 value
         contractPriceTotal: function(){
           let contract_price_total = _.sumBy(this.default_value.data, function(data) {
               return Number(data.acceptance_price);
           });

           this.purchase_targets.forEach((purchase_target, i) => {
             purchase_target.purchase_contract.contract_price_total = contract_price_total
           });
         },
         // copy data from A121-52 to A121-57
         copyToPurchaseContractDepositDate: function(purchase_contract_deposit, value){
           purchase_contract_deposit.date = value
         },
         // copy data from A121-53 to A121-62
         copyToPurchaseContractDeliveryDate: function(purchase_target, value){
           purchase_target.purchase_contract.contract_delivery_date = value
         },
         buttonAction: function(purchase_target, action, index){
           this.default_value.data[index].button_action = action
         },
         submitForms: function(){
           this.default_value.valid_counter = 0
           this.purchase_targets.forEach((purchase_target, i) => {
             this.default_value.data[i].button_action = 'save'
             $("#form-"+i).submit();
           });
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
     vm.purchase_targets.forEach((purchase_target, i) => {
       // init parsley form validation
       // ------------------------------------------------------------------
       let $form = $('#form-'+i);
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
                if(vm.purchase_targets[i].purchase_contract.contract_delivery_money == null) {
                  vm.purchase_targets[i].purchase_contract.contract_delivery_money = 0;
                }
                let data = [vm.purchase_targets[i]]
                // condition check
               // ----------------------------------------------------------
                 // if A121-67 is checked
                 if (vm.default_value.data[i].button_action == 'purchase contract create' && vm.purchase_targets[i].purchase_contract.contract_not_create_documents == 1) {
                   $.toast({
                       position: 'top-right', icon:'warning',
                       stack: false, hideAfter: 3000,
                       text: '「仕入契約書は仲介業者にて作成」がチェックされているので作成できません。',
                       position: { right: 18, top: 68 }
                   });
                   return false

                 // if button save clicked
                 }else if (vm.default_value.data[i].button_action == 'save' && valid)
                 {
                   vm.default_value.valid_counter++
                   // check valid counter when button save clicked before send request
                   if (vm.default_value.valid_counter == vm.purchase_targets.length){
                     vm.submited = true,
                     data = vm.purchase_targets
                   }
                   else return false
                 }
               // ----------------------------------------------------------

               // handle update request
               // ----------------------------------------------------------
               if (vm.default_value.data[i].button_action == 'purchase contract create') vm.default_value.data[i].contract_create_submited = true
               if (vm.default_value.data[i].button_action == 'purchase response') vm.default_value.data[i].response_submited = true
               let request = axios.post('{{ $form_action }}', data)
               // ----------------------------------------------------------

               // handle success response
               // ----------------------------------------------------------
               request.then(function(response){
                 // page redirect after success request
                 // ----------------------------------------------------------
                 if (vm.default_value.data[i].button_action == 'save') {
                   // window.location.href = response.data.data.purchase_response
                   console.log('save result');
                   vm.submited = false
                 }
                 else if (vm.default_value.data[i].button_action == 'purchase response') {
                   window.location.href = response.data.data.purchase_response
                   vm.default_value.data[i].response_submited = false
                 }else if (vm.default_value.data[i].button_action == 'purchase contract create') {
                   window.location.href = response.data.data.purchase_contract_create
                   vm.default_value.data[i].contract_create_submited = false
                 }
                 // ----------------------------------------------------------
                 // update data to response data
                 vm.purchase_targets = response.data.data.purchase_targets

                 $.toast({
                   heading: '成功', icon: 'success',
                   position: 'top-right', stack: false, hideAfter: 3000,
                   text: '編集した内容を保存しました。',
                   position: { right: 18, top: 68 }
                 });
               })
               // ----------------------------------------------------------

               // handle error response
               // ----------------------------------------------------------
               request.catch(function(error){
                 console.log(error.response, 'error');
                 vm.submited = false
                 vm.default_value.data[i].response_submited = false
                 vm.default_value.data[i].contract_create_submited = false

                 var error_message = []
                 if (error.response.status == 422){ error_message.push(error.response.data.message) }
                 if (error.response.status == 500){ error_message.push(error.response.data.message, error.response.data.error) }

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

               // ----------------------------------------------------------
           }
           // --------------------------------------------------------------
       }).on('form:submit', function(){ return false });
     });

   })

   // ----------------------------------------------------------------------
   // Custom function refresh validator
   // ----------------------------------------------------------------------
   var refreshParsley = function(vm){
       vm.purchase_targets.forEach((purchase_target, i) => {
         Vue.nextTick(function () {
           var $form = $('#form-'+i);
           $form.parsley().refresh();
         });
       });
   }
   var resetParsley = function(vm){
     vm.purchase_targets.forEach((purchase_target, i) => {
       Vue.nextTick(function () {
           var $form = $('#form-'+i);
           $form.parsley().reset();
       });
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
