<div class="row">
    <div class="col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'sales-period' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.salePaymentPeriod"/>
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>売決済期</span>
            </label>
        </div>

    </div>
    <div class="col-sm d-flex align-items-center" v-for="name in [ group+ 'sales-period-option' ]" 
        ref="salePaymentPeriod" @click="enableSalePaymentPeriod">

        <!-- Sale contract payment period - Start -->
        <select type="text" class="form-control fs-14" :name="name" :id="name" @change="submitFilter"
            :disabled="isDisabled || !check.salePaymentPeriod" v-model="filter.sale_payment_period">
            <option v-for="( period, index ) in preset.periods" :value="index">@{{ period.label }}</option>
        </select>
        <!-- Sale contract payment period - End -->

    </div>
</div>