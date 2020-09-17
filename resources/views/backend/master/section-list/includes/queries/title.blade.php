<div class="row">
    <div class="col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'property' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.title" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>物件名称</span>
            </label>
        </div>

    </div>
    <div class="col-sm d-flex align-items-center" v-for="name in [ group+ 'property-name' ]" ref="title" @click="enableTitle">
        <input type="text" class="form-control fs-14" :name="name" :id="name" @keyup.enter="submitFilter"
            :disabled="isDisabled || !check.title" v-model.trim="filter.title" />
    </div>
</div>