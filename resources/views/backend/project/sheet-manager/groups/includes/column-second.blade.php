<div class="col-md-12 col-lg-4 mt-2 mt-lg-0" v-else>
    <div class="row mx-n1">
        <div class="px-1 col-auto py-2">
            <span>+</span>
        </div>
        <div class="px-1 col">
            <div class="input-group input-money">
                <div class="input-group-prepend">
                    <button class="btn btn-input-group btn-dimmed fs-10 px-2" type="button" @click="resetDefault(field)"
                        :disabled="status.loading" title="リセット">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                </div>
                <template>
                    <currency-input v-model="field.value" class="form-control text-right" :name="'field-id-' +field.id" :disabled="status.loading"
                        :currency="config.currency.currency" :precision="config.currency.precision" :allow-negative="config.currency.allowNegative" 
                        data-parsley-decimal-maxlength="[12,0]" data-parsley-trigger="change focusout" data-parsley-no-focus />
                </template>
                <div class="input-group-append">
                    <span class="input-group-text fs-12 px-2">円</span>
                </div>
            </div>
            <div class="form-result"></div>
        </div>
    </div>
</div>