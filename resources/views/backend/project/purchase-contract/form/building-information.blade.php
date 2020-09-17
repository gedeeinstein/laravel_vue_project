<table class="table table-bordered table-small mt-3 buypurchase_table w-100">
   <tr>
      <th rowspan="3">@lang('project_purchase_contract.purchase_rejection_input')</th>
      <td align="center">
        @if ($editable)
          <button type="submit" @click="buttonAction(purchase_targets[{{ $index }}], 'purchase response', {{ $index }})" :disabled="default_value.data[{{ $index }}].is_disabled" class="btn btn-wide btn-info px-4">
              <i v-if="!default_value.data[{{ $index }}].response_submited" class="fas fa-save"></i>
              <i v-else class="fas fa-spinner fa-spin"></i>
              @lang('project_purchase_contract.save_detail')
          </button>
        @endif
      </td>
      <td class="auto">@lang('project_purchase_contract.amount_reflected_contract')</td>
   </tr>
   <tr>
      <td>@lang('project_purchase_contract.sale_price')</td>
      <td class="">
         <div class="form-group">
            <template>
              <currency-input class="form-control form-control-w-xl" v-model="purchase_targets[{{ $index }}].purchase_price"
                  :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" readonly="readonly" />
            </template>
            <span><i @click="copyToPurchaseContractPrice(purchase_targets[{{ $index }}], {{ $index }}, purchase_targets[{{ $index }}].purchase_price)" class="copy_xxxx_button far fa-copy cur-pointer text-secondary ml-2 mr-2" data-toggle="tooltip" title="左の値を右へコピー"></i></span>
            <currency-input @keyup="keyupFromPurchaseContractPrice(purchase_targets[{{ $index }}], {{ $index }})" class="form-control form-control-w-xl input-money" v-model="purchase_targets[{{ $index }}].purchase_contract.contract_price"
                :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                 data-parsley-trigger="change focusout" data-parsley-no-focus />
         </div>
      </td>
   </tr>
   <tr>
      <td>@lang('project_purchase_contract.inward_deposit')</td>
      <td>
         <div class="form-group">
            <template>
              <currency-input class="form-control form-control-w-xl" v-model="purchase_targets[{{ $index }}].purchase_deposit"
                  :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable" readonly="readonly" />
            </template>
            <span><i @click="copyToPurchaseContractDeposit(purchase_targets[{{ $index }}], purchase_targets[{{ $index }}].purchase_deposit)" class="copy_xxxx_button far fa-copy cur-pointer text-secondary ml-2 mr-2" data-toggle="tooltip" title="左を右にコピー"></i></span>
            <currency-input @keyup="keyupFromPurchaseContractDeposit(purchase_targets[{{ $index }}])" class="form-control form-control-w-xl input-money" v-model="purchase_targets[{{ $index }}].purchase_contract.contract_deposit"
                :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                 data-parsley-trigger="change focusout" data-parsley-no-focus />
         </div>
      </td>
   </tr>
</table>
<table class="table table-bordered table-small mt-3 buypurchase_table w-100">
  @if (count($purchase_target->purchase_target_buildings) > 0)
      @if ($purchase_target->purchase_target_buildings[0]->purchase_third_person_occupied == 2)
      <tr>
         <th width="160px">@lang('project_purchase_contract.third_party')</th>
         <td class="">
            <div class="form-group">
               <input value="有" class="form-control form-control-w-xs" name="" type="text" readonly="readonly">
            </div>
         </td>
      </tr>
      @else
      <tr>
         <th width="160px">@lang('project_purchase_contract.third_party')</th>
         <td class="">
            <div class="form-group">
               <input value="無" class="form-control form-control-w-xs" name="" type="text" readonly="readonly">
            </div>
         </td>
      </tr>
      @endif
   @else
   <tr>
      <th width="160px">@lang('project_purchase_contract.third_party')</th>
      <td class="">
         <div class="form-group">
            <input value="無" class="form-control form-control-w-xs" name="" type="text" readonly="readonly">
         </div>
      </td>
   </tr>
   @endif
   <tr>
      <th>@lang('project_purchase_contract.nukesuke')</th>
      <td class="auto">
         <div class="form-group">
            <div class="icheck-cyan d-inline">
               <input v-model="purchase_targets[{{ $index }}].purchase_contract.mediation" class="form-check-input" type="radio" name="mediation" id="mediation-1-{{ $index }}" value="1"
               :disabled="!default_value.editable">
               <label class="form-check-label" for="mediation-1-{{ $index }}">@lang('project_purchase_contract.no')</label>
            </div>
            <div class="icheck-cyan d-inline">
               <input v-model="purchase_targets[{{ $index }}].purchase_contract.mediation" class="form-check-input" type="radio" name="mediation" id="mediation-2-{{ $index }}" value="2"
               :disabled="!default_value.editable">
               <label class="form-check-label" for="mediation-2-{{ $index }}">@lang('project_purchase_contract.have')</label>
            </div>
         </div>
      </td>
   </tr>
   <tr>
      <th width="160px">@lang('project_purchase_contract.seller')</th>
      <td>
         <div class="form-group">
            <div class="icheck-cyan d-inline" style="-webkit-appearance: slider-vertical;">
               <input v-model="purchase_targets[{{ $index }}].purchase_contract.seller" class="form-check-input" type="radio" name="" id="seller-1-{{ $index }}" value="1"
               :disabled="!default_value.editable">
               <label class="form-check-label" for="seller-1-{{ $index }}">@lang('project_purchase_contract.not_a_trader')</label>
            </div>
            <div class="icheck-cyan d-inline" style="-webkit-appearance: slider-vertical;">
               <input v-model="purchase_targets[{{ $index }}].purchase_contract.seller" class="form-check-input" type="radio" name="" id="seller-2-{{ $index }}" value="2"
               :disabled="!default_value.editable">
               <label class="form-check-label" for="seller-2-{{ $index }}">@lang('project_purchase_contract.trader')</label>
            </div>
            <div v-if="purchase_targets[{{ $index }}].purchase_contract.seller == 2" class="form-check form-check-inline pl-4">
               <span>@lang('project_purchase_contract.operator_name')</span>
               <div class="form-group">
                 {{-- <v-select :options="seller_broker_companies.data[{{ $index }}]"
                         class="ml-2"
                         style="border-color: #bfb3da; background-color: #e2dcee; min-width:100px"
                         :reduce="name => name.id"
                         label="name" :disabled="!default_value.editable"
                         v-model="purchase_targets[{{ $index }}].purchase_contract.seller_broker_company_id">
                         <template #search="{attributes, events}">
                             <input class="vs__search" :required="purchase_targets[{{ $index }}].purchase_contract.seller == 2 && !purchase_targets[{{ $index }}].purchase_contract.seller_broker_company_id" v-bind="attributes"
                                 v-on="events" />
                         </template>
                 </v-select> --}}
                 <v-select :options="real_estates"
                         class="ml-2"
                         style="border-color: #bfb3da; background-color: #e2dcee; min-width:100px"
                         :reduce="name => name.id"
                         label="name" :disabled="!default_value.editable"
                         v-model="purchase_targets[{{ $index }}].purchase_contract.seller_broker_company_id">
                         <span slot="no-options">選択できる業者はありません</span>
                         <template #search="{attributes, events}">
                             <input class="vs__search" :required="purchase_targets[{{ $index }}].purchase_contract.seller == 2 && !purchase_targets[{{ $index }}].purchase_contract.seller_broker_company_id" v-bind="attributes"
                                 v-on="events" />
                         </template>
                 </v-select>
               </div>
            </div>
         </div>
      </td>
   </tr>
</table>
