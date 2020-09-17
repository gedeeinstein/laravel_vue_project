<table class="table table-bordered table-small table-parcel-list mt-3">
   <thead>
      <tr>
         <th class="">@lang('project_purchase_contract.trading_price')</th>
         <th class="">@lang('project_purchase_contract.contract_date')</th>
         <th class="">@lang('project_purchase_contract.settlement_date')</th>
         <th class="">@lang('project_purchase_contract.building_price')</th>
      </tr>
   </thead>
   <tbody align="center">
      <tr>
         <td>
            <div class="form-group">
               <currency-input class="form-control form-control-w-lg form-control-sm" v-model="default_value.data[{{ $index }}].acceptance_price"
                   :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                   data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus readonly="readonly"/>
            </div>
         </td>
         <td>
            <div class="form-group">
               <date-picker v-model="purchase_targets[{{ $index }}].purchase_contract.contract_date" type="date" input-class="form-control form-control-sm input-date w-100" format="YYYY/MM/DD" value-type="format"
                 :disabled="!default_value.editable"></date-picker>
            </div>
         </td>
         <td>
            <div class="form-group">
               <date-picker v-model="purchase_targets[{{ $index }}].purchase_contract.contract_payment_date" type="date" input-class="form-control form-control-sm input-date w-100" format="YYYY/MM/DD" value-type="format"
                 :disabled="!default_value.editable"></date-picker>
            </div>
         </td>
         <td>
            <div class="form-group">
               <template>
                  <currency-input class="form-control form-control-w-lg form-control-sm input-decimal" v-model="purchase_targets[{{ $index }}].purchase_contract.contract_price_building"
                     :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                     data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus/>
               </template>
            
               <div class="form-check form-check-inline mr-0">
                     <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_price_building_no_tax" class="form-check-input" type="checkbox" name="" id="" value="1"
                     :disabled="!default_value.editable">
                     <label class="form-check-label" for="">非</label>
               </div>
            </div>
         </td>
      </tr>
   </tbody>
</table>
