<div class="row">
    <div class="col-auto">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'no-purchase-date' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="filter.empty_contract"/>
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>仕入契約日未入力</span>
            </label>
        </div>

    </div>
</div>