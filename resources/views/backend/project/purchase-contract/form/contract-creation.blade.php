<div class="bottom mt-2 mb-5 text-center">
    @if ($editable)
      <button type="submit" @click="buttonAction(purchase_targets[{{ $index }}], 'purchase contract create', {{ $index }})" class="btn btn-wide btn-info px-4">
          <i v-if="!default_value.data[{{ $index }}].contract_create_submited" class="fas fa-save"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          @lang('project_purchase_contract.contract_creation')
      </button>
    @endif
    <div class="form-check form-check-inline mr-0 icheck-cyan">
        <input v-model="purchase_targets[{{ $index }}].purchase_contract.contract_not_create_documents" class="form-check-input" type="checkbox" id="contract_not_create_documents{{ $index }}" value="1"
        :disabled="!default_value.editable">
        <label class="form-check-label" for="contract_not_create_documents{{ $index }}">@lang('project_purchase_contract.contract_created_by_broker')</label>
    </div>
</div>
