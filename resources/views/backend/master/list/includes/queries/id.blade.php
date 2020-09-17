<div class="row">
    <div class="col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'id' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.id" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>ID範囲</span>
            </label>
        </div>

    </div>
    <div class="col-sm d-flex align-items-center" @click="enableID">
        <div class="row mx-n1 flex-grow-1">

            <!-- Starting ID - Start -->
            <div class="px-1 col" v-for="name in [ group+ 'id-min' ]" ref="min">
                <currency-input class="form-control fs-14" :name="name" :id="name" v-model="filter.min" @keyup.enter="submitFilter"
                    :currency="null" :precision="0" :allow-negative="false" :disabled="isDisabled || !check.id" />
            </div>
            <!-- Starting ID - End -->

            <!-- Separator - Start -->
            <div class="px-1 col-auto d-flex align-items-center">
                <span>-</span>
            </div>
            <!-- Separator - End -->

            <!-- Ending ID - Start -->
            <div class="px-1 col" v-for="name in [ group+ 'id-max' ]">
                <currency-input class="form-control fs-14" :name="name" :id="name" v-model="filter.max" @keyup.enter="submitFilter"
                    :currency="null" :precision="0" :allow-negative="false" :disabled="isDisabled || !check.id" />
            </div>
            <!-- Ending ID - End -->

        </div>
    </div>
</div>