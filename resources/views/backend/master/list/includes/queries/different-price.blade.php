<div class="row">
    <div class="col-auto">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'different-property' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.different_price"/>
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>支出と支出簿価が違う物件</span>
            </label>
        </div>

    </div>
</div>