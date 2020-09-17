<div class="row mx-n1">
    <div class="px-1 col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group + 'sales-date' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.salePayment" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>売決済年月</span>
            </label>
        </div>

    </div>
    <div class="px-1 col-sm d-flex align-items-center" ref="salePayment" @click="enableSalePayment">
        <div class="row mx-n1 flex-grow-1">
            <div class="px-1 col-7" v-for="name in [ group + 'sales-date-year' ]">

                <!-- Sale contract payment year - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.salePayment" 
                    v-model="filter.sale_payment_year" @change="submitFilter">
                    <option v-for="option in preset.years" :value="option">@{{ option }}年</option>
                </select>
                <!-- Sale contract payment year - End -->

            </div>
            <div class="px-1 col-5" v-for="name in [ group + 'sales-date-month' ]">

                <!-- Sale contract payment month - Start -->
                <select :name="name" :id="name" class="form-control fs-14" :disabled="isDisabled || !check.salePayment || !filter.sale_payment_year"
                    v-model="filter.sale_payment_month" @change="submitFilter">
                    <option :value="null"></option>
                    <option v-for="option in 12" :value="option">@{{ option }}月</option>
                </select>
                <!-- Sale contract payment month - End -->

            </div>
        </div>
    </div>
</div>