<table class="table table-bordered table-small mt-3">
   <thead>
      <tr>
         <th width="143px">@lang('project_purchase_contract.extradition')</th>
         <th width="293px">@lang('project_purchase_contract.payment_date')</th>
         <th width="77px">@lang('project_purchase_contract.status')</th>
         <th width="343px">@lang('project_purchase_contract.withdrawal_account')</th>
         <th width="211px">@lang('project_purchase_contract.remarks')</th>
      </tr>
   </thead>
   <tbody align="center">
      <tr>
         <td>
            <div class="form-group">
               <template>
                 <currency-input class="form-control form-control-w-lg form-control-sm" v-model="purchase_targets[{{ $index }}].purchase_contract.contract_delivery_money"
                     :currency="null" :precision="{min: 0, max: 9}" :allow-negative="true"
                     data-parsley-trigger="change focusout" data-parsley-no-focus :readonly="default_value.data[{{ $index }}].is_readonly" />
               </template>
               <span v-if="default_value.editable"><i @click="editableInput(purchase_targets[{{ $index }}], {{ $index }})" class="add_xxxx_button fa fa-edit cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="編集"></i></span>
            </div>
         </td>
         <td>
            <div class="form-group">
               <date-picker v-model="purchase_targets[{{ $index }}].purchase_contract.contract_delivery_date" type="date" input-class="form-control form-control-sm input-date w-100" format="YYYY/MM/DD" value-type="format"
               :disabled="!default_value.editable"></date-picker>
               <span><i @click="copyToPurchaseContractDeliveryDate(purchase_targets[{{ $index }}], purchase_targets[{{ $index }}].purchase_contract.contract_payment_date)" class="copy_xxxx_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="決済日からコピーする"></i></span>
            </div>
         </td>
         <td class="auto">
            <div class="form-group">
               <select v-model="purchase_targets[{{ $index }}].purchase_contract.contract_delivery_status" class="form-control" name=""
               :disabled="!default_value.editable">
                  <option value="1">予</option>
                  <option value="2">確</option>
                  <option value="3" :disabled="option_is_disabled">済</option>
               </select>
            </div>
         </td>
         <td>
            <div class="form-group">
               <select v-model="purchase_targets[{{ $index }}].purchase_contract.contract_delivery_bank" class="form-control form-control-w-xxl form-control-sm" name=""
               :disabled="!default_value.editable">
                  <option value="0"></option>
                  {{-- <option v-for="company_bank_account in company_bank_accounts" :value="company_bank_account.id">@{{ company_bank_account.bank.company.name_abbreviation }} @{{ company_bank_account.bank.name_branch_abbreviation }} @{{ company_bank_account.company_bank_account }}</option> --}}
                  <option v-for="company_bank_account in banks" :value="company_bank_account.id">@{{ company_bank_account.name }}</option>
               </select>
            </div>
         </td>
         <td class="auto">
            <div class="form-group">
               <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_delivery_note" class="form-control form-control-sm" name="" type="text" value=""
               :disabled="!default_value.editable">
            </div>
         </td>
      </tr>
   </tbody>
</table>
