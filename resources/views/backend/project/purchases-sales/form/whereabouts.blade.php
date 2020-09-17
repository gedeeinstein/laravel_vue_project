<div class="card card-project">
    <div class="card-header">
        <strong>@lang('project_purchases_sales.whereabouts')</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-header">
                        <span class="text-danger">※</span>
                        <strong>@lang('project_purchases_sales.property_name')</strong>
                    </div>

                    <!-- start - project.title -->
                    <input class="form-control col-8" type="text" v-model="project.title"
                        data-parsley-no-focus required data-parsley-trigger="change focusout"
                        :disabled="status.loading || !initial.editable"
                    >
                    <!-- end - project.title -->

                </div>
            </div>
            <div class="col-6">
                <div class="form-group row pl-3">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-header">
                        <span class="text-info">※</span>
                        <strong>@lang('project_purchases_sales.main_operator')</strong>
                    </div>

                    <!-- start - purchase_sale.company_id_organizer -->
                    <select class="form-control col-sm-12 col-md-7 col-lg-7" name="company_id_organizer"
                        @change="getCompanyUser(purchase_sales.company_id_organizer)"
                        v-model="purchase_sales.company_id_organizer"
                        :disabled="status.loading || !initial.editable">
                        <option value="0"></option>
                        @foreach($kindinhouse as $key => $company)
                        <option value="{{$company->id}}" {{ $company->id == $key ? "selected" : "" }}>{{$company->name}}</option>
                        @endforeach
                    </select>
                    <!-- end - purchase_sale.company_id_organizer -->

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group row">
                    <label class="col-3 col-form-label">@lang('project_purchases_sales.residence_indication')</label>

                    <!-- start - purchase_sale.project_address -->
                    <input class="form-control col-8" type="text" v-model="purchase_sales.project_address"
                        :disabled="!initial.editable" data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    >
                    <!-- end - purchase_sale.project_address -->

                    <!-- start - copy icon -->
                    <span v-if="initial.editable" class="col-1">
                        <i @click="copyField(project.title)" class="copy_button far fa-copy cur-pointer m-2 text-muted" data-toggle="tooltip" title="" data-original-title="地番コピー"></i>
                    </span>
                    <!-- end - copy icon -->

                </div>
            </div>
            <div class="col-6">
                <div class="form-group row pl-3">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-header">
                        <strong>@lang('project_purchases_sales.house_builder')</strong>
                    </div>

                    <!-- start - purchase_sale.organizer_realestate_explainer -->
                    <select class="form-control col-sm-12 col-md-7 col-lg-7"
                        v-model="purchase_sales.organizer_realestate_explainer"
                        :required="purchase_sales.company_id_organizer"
                        data-parsley-trigger="change focusout"
                        :disabled="status.loading || !initial.editable">
                        <option v-if="purchase_sales.company_id_organizer"></option>
                        <option v-else value="0"></option>
                        <option v-for="user in users_real_estate_notary_registration" :value="user.id">@{{ user.full_name }}</option>
                    </select>
                    <!-- end - purchase_sale.organizer_realestate_explainer -->

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group row">

                    <!-- start - project.title -->
                    <input class="form-control col-8 offset-md-3" name="project_address_extra"
                        type="text" v-model="purchase_sales.project_address_extra"
                        placeholder="続き" :disabled="!initial.editable"
                        data-parsley-trigger="keyup" data-parsley-maxlength="128"
                    >
                    <!-- end - project.title -->

                </div>
            </div>
        </div>
    </div>
</div>
