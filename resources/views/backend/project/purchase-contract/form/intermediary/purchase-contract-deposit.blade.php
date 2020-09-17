<table class="table table-bordered table-small buypurchase_table mt-3 mb-0">
   <thead>
      <tr>
         <th width="143px">@lang('project_purchase_contract.contract_deposit')</th>
         <th width="293px">@lang('project_purchase_contract.payment_date')</th>
         <th width="77px">@lang('project_purchase_contract.status')</th>
         <th width="343px">@lang('project_purchase_contract.withdrawal_account')</th>
         <th width="211px">@lang('project_purchase_contract.remarks')</th>
      </tr>
   </thead>
   <tbody align="center">
      <tr v-for="(purchase_contract_deposit, index_contract_deposit) in purchase_targets[{{ $index }}].purchase_contract.purchase_contract_deposits">
         <td>
            <div class="form-group">
               <template v-if="index_contract_deposit == 0">
                 <currency-input class="form-control form-control-w-lg form-control-sm" v-model="purchase_contract_deposit.price"
                     :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false"
                     data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus readonly="readonly"/>
               </template>
               <template v-else>
                 <currency-input class="form-control form-control-w-lg form-control-sm input-money" v-model="purchase_contract_deposit.price"
                     :currency="null" :precision="{min: 0, max: 9}" :allow-negative="false" :disabled="!default_value.editable"
                     data-parsley-decimal-maxlength="[12,4]" data-parsley-trigger="change focusout" data-parsley-no-focus/>
               </template>
               <template v-if="default_value.editable">
                 <span v-if="index_contract_deposit == 0"><i @click="addProjectPurchaseContractDeposit(purchase_targets[{{ $index }}])" class="add_xxxx_button fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="追加"></i></span>
                 <span v-else><i @click="removeProjectPurchaseContractDeposit(purchase_targets[{{ $index }}], index_contract_deposit)" class="add_xxxx_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="追加"></i></span>
               </template>
            </div>
         </td>
         <td>
            <div class="form-group">
               <date-picker v-model="purchase_contract_deposit.date" type="date" input-class="form-control form-control-sm input-date w-100" format="YYYY/MM/DD" value-type="format"
                 :disabled="!default_value.editable"></date-picker>
               <span><i @click="copyToPurchaseContractDepositDate(purchase_contract_deposit, purchase_targets[{{ $index }}].purchase_contract.contract_date)" v-if="index_contract_deposit == 0" class="copy_xxxx_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="契約日からコピーする"></i></span>
            </div>
         </td>
         <td class="auto">
            <div class="form-group">
               <select v-model="purchase_contract_deposit.status" class="form-control" name=""
               :disabled="!default_value.editable">
                  <option value="1">予</option>
                  <option value="2">確</option>
                  <option value="3" :disabled="option_is_disabled">済</option>
               </select>
            </div>
         </td>
         <td>
            <div class="form-group">
               <select v-model="purchase_contract_deposit.account" class="form-control form-control-w-xxl form-control-sm" name=""
               :disabled="!default_value.editable">
                  <option value="0"></option>
                  {{-- <option v-for="company_bank_account in company_bank_accounts" :value="company_bank_account.id">@{{ company_bank_account.bank.company.name_abbreviation }} @{{ company_bank_account.bank.name_branch_abbreviation }} @{{ company_bank_account.company_bank_account }}</option> --}}
                  <option v-for="company_bank_account in banks" :value="company_bank_account.id">@{{ company_bank_account.name }}</option>
               </select>
            </div>
         </td>
         <td class="auto">
            <div class="form-group">
               <input v-model="purchase_contract_deposit.note" class="form-control form-control-sm" name="" type="text" value="" data-parsley-maxlength="128"
               :disabled="!default_value.editable">
            </div>
         </td>
      </tr>
   </tbody>
</table>
