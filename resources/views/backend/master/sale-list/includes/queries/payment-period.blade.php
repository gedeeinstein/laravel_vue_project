<div class="row">
    <div class="col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'purchase-period' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.paymentPeriod" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>仕入決済期</span>
            </label>
        </div>

    </div>
    <div class="col-sm d-flex align-items-center" v-for="name in [ group+ 'purchase-period-option' ]" 
        ref="paymentPeriod" @click="enablePaymentPeriod">

        <!-- Contract payment period - Start -->
        <select type="text" class="form-control fs-14" :name="name" :id="name" @change="submitFilter"
            :disabled="isDisabled || !check.paymentPeriod" v-model="filter.payment_period">
            <option v-for="( period, index ) in preset.periods" :value="index">@{{ period.label }}</option>
        </select>
        <!-- Contract payment period - End -->

    </div>
</div>