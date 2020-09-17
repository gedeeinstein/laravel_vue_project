<div class="row mx-0" :class="{ 'text-black-50': isDisabled }">
    <div class="px-0 col-lg-150px column d-flex align-items-center">
        <div class="w-100 py-2 px-2">
            <div class="row mx-n2">
                <div class="px-2 col-120px col-md-200px d-block d-lg-none">
                    <strong>種別</strong>
                </div>
                <div class="px-2 col" v-if="entry.type && entry.type.value">
                    <span>@{{ entry.type.value }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="px-0 col-lg-150px column column d-flex align-items-center">
        <div class="w-100 py-2 px-2">
            <div class="row mx-n2">
                <div class="px-2 col-120px col-md-200px d-block d-lg-none">
                    <strong>リクエスト日時</strong>
                </div>
                <div class="px-2 col" v-if="timestamp">
                    <span>@{{ timestamp }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="px-0 col-md column d-flex align-items-center">
        <div class="w-100 py-2 px-2">
            <div class="row mx-n2">
                <div class="px-2 col-120px d-block d-md-none">
                    <strong>番号</strong>
                </div>
                <div class="px-2 col" v-if="portNumber">
                    <a :href="entryURL" target="_blank" :class="{ 'text-black-50': isDisabled }">
                        <span>@{{ portNumber }}</span>
                        <template v-if="requestSheet">
                            <span>-</span>
                            <span>@{{ requestSheet.name }}</span>
                        </template>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="px-0 col-md column d-flex align-items-center">
        <div class="w-100 py-2 px-2">
            <div class="row mx-n2">
                <div class="px-2 col-120px d-block d-md-none">
                    <strong>PJ名称</strong>
                </div>
                <div class="px-2 col" v-if="project.title">
                    <span>@{{ project.title }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="px-0 col-md-280px column d-flex align-items-center">
        <div class="w-100 py-2 px-2" v-for="name in [ 'request-' +entry.id+ '-' ]">

            <!-- Inspection options - Start -->
            <div class="row mx-0">
                <div class="px-0 col d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="row mx-n1">
                        <div class="px-1 col-auto">

                            <div :class="isRequestDisabled ? 'icheck-secondary': 'icheck-cyan'" v-for="id in [ name + 'undecided' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isRequestDisabled" :value="1" v-model="entry.examination" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="{ 'text-black-50': isRequestDisabled }">未承認</span>
                                </label>
                            </div>

                        </div>
                        <div class="px-1 col-auto">

                            <div :class="isRequestDisabled ? 'icheck-secondary': 'icheck-cyan'" v-for="id in [ name + 'approved' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isRequestDisabled" :value="2" v-model="entry.examination" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="isRequestDisabled ? 'text-black-50': 'text-blue'">承認</span>
                                </label>
                            </div>

                        </div>
                        <div class="px-1 col-auto">

                            <div :class="isRequestDisabled ? 'icheck-secondary': 'icheck-cyan'" v-for="id in [ name + 'rejected' ]">
                                <input type="radio" :id="id" :name="name" data-parsley-checkmin="1"
                                    :disabled="isRequestDisabled" :value="3" v-model="entry.examination" />
                                <label :for="id" class="fs-12 fw-n noselect w-100">
                                    <span :class="isRequestDisabled ? 'text-black-50': 'text-red'">非承認</span>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="px-0 col-50px d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-block btn-sm" :disabled="isRequestDisabled" @click="update"
                        :class="isRequestDisabled ? 'btn-secondary': 'btn-primary'">
                        <i v-if="status.loading" class="far fa-spinner fa-spin"></i>
                        <span v-else>更新</span>
                    </button>
                </div>
            </div>
            <!-- Inspection options - End -->

            <!-- Inspection memp - Start -->
            <div class="form-group mt-2 mb-0" v-if="3 === entry.kind">
                @component("{$components->preset}.text")
                    @slot( 'name', "name + 'memo'" )
                    @slot( 'disabled', 'isRequestDisabled' )
                    @slot( 'model', 'entry.examination_text' )
                    @slot( 'maxlength', 256 )
                    @slot( 'size', 'small' )
                @endcomponent
            </div>
            <!-- Inspection memp - End -->

        </div>
    </div>
</div>