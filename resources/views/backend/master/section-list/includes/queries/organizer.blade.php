<div class="row">
    <div class="col-sm-112px">

        <div class="icheck-cyan icheck-sm" v-for="name in [ group+ 'operator' ]">
            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="check.organizer" />
            <label :for="name" class="fs-12 fw-n noselect w-100">
                <span>主事業者</span>
            </label>
        </div>

    </div>
    <div class="col-sm d-flex align-items-center" v-for="name in [ group+ 'main-operator' ]" ref="organizer" @click="enableOrganizer">
        <select type="text" class="form-control fs-14" :name="name" :id="name" @change="submitFilter"
            :disabled="isDisabled || !check.organizer" v-model.number="filter.organizer">
            <option v-for="option in preset.organizers" :value="option.id">@{{ option.name }}</option>
        </select>
    </div>
</div>