
<transition name="paste-in">

    <div v-if="item.kind_real_estate_agent">
        <hr class="my-5"/>

        <div class="form-section">
            <h4 class="mb-4">@lang('label.$company.form.header.agent')</h4>

            <!-- Main office address - Start -->
            <div class="row form-group py-2 border-0">
                @php $label = 'label.$company.form.label.main.address' @endphp
                <div class="col-md-3 col-header">
                    <span class="bg-danger label-required">@lang('label.required')</span>
                    <strong class="field-title fs-15">@lang( $label )</strong>
                </div>
                <div class="col-md-9 col-content">
                    <input type="text" id="company-main-office-address" name="company-main-office-address" class="form-control"
                        v-model.trim="item.real_estate_agent_office_main_address" placeholder="@lang( $label )" data-parsley-maxlength="128"
                        required data-parsley-trigger="change focusout" data-parsley-no-focus :disabled="status.loading" />
                </div>
            </div>
            <!-- Main office address - End -->


            <!-- Main office phone - Start -->
            <div class="row form-group py-2 border-0">
                @php $label = 'label.$company.form.label.main.phone' @endphp
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang( $label )</strong>
                </div>
                <div class="col-md-9 col-content">
                    <input type="text" id="company-main-office-phone" name="company-main-office-phone" class="form-control"
                        v-model.trim="item.real_estate_agent_office_main_phone_number" placeholder="@lang( $label )" :disabled="status.loading" 
                        data-parsley-trigger="change focusout" data-parsley-no-focus data-parsley-phone-number="14" />
                </div>
            </div>
            <!-- Main office phone - End -->


            <!-- Representative name - Start -->
            <div class="row form-group py-2 border-0">
                @php $label = 'label.$company.form.label.representative' @endphp
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang( $label )</strong>
                </div>
                <div class="col-md-6 col-content">
                    <input type="text" id="company-representative" name="company-representative" class="form-control" data-parsley-maxlength="128"
                        v-model.trim="item.real_estate_agent_representative_name" placeholder="@lang( $label )" :disabled="status.loading" 
                        data-parsley-trigger="change focusout" data-parsley-no-focus />
                </div>
            </div>
            <!-- Representative name - End -->


            <!-- License number - Start -->
            <div class="row form-group py-2 border-0">
                @php $label = 'label.$company.form.label.license.number' @endphp
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang( $label )</strong>
                </div>
                <div class="col-md-9 col-content">
                    <div class="row mx-n2">
                        <div class="px-2 col-12 col-md-4 mb-2 mb-md-0">
                            <select class="form-control" v-model.number="item.license_authorizer_id" name="company-license-authorizer" id="company-license-authorizer" :disabled="status.loading">
                                <option value="0"></option>
                                @foreach( $authorizers as $entry )
                                    <option value="{{ $entry->id }}">{{ $entry->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="px-2 col-12 col-md-4 mb-2 mb-md-0">
                            <select class="form-control" v-model.number="item.license_update" name="company-license-update" id="company-license-update" :disabled="status.loading">
                                <option value="0"></option>
                                @php $index = 1 @endphp
                                @while( $index <= 15 )
                                    <option value="{{ $index }}">({{ $index }})</option>
                                    @php $index++ @endphp
                                @endwhile
                            </select>
                        </div>
                        <div class="px-2 col-12 col-md-4 mb-2 mb-md-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@lang('label.$company.form.label.license.no')</span>
                                </div>
                                <input type="number" id="company-license-number" min="0" max="99999" name="company-license-number" class="form-control" data-parsley-no-focus
                                    v-model.number="item.license_number" placeholder="@lang( $label )" :disabled="status.loading" data-parsley-trigger="change focusout" />
                                @if( 'en' != App::getLocale())
                                    <div class="input-group-append">
                                        <span class="input-group-text">号</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-result"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- License number - End -->


            <!-- License date - Start -->
            <div class="row form-group py-2 border-0">
                @php $label = 'label.$company.form.label.license' @endphp
                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang( "{$label}.date" )</strong>
                </div>
                <div class="col-md-3 col-content">
                    <date-picker v-model="item.license_date" type="date" class="w-100" input-class="form-control form-control-reset" :disabled="status.loading"
                        :editable="false" format="YYYY/MM/DD" value-type="format" :input-attr="{ placeholder: '@lang( "{$label}.date" )' }">
                    </date-picker>
                </div>
            </div>
            <!-- License date - Start -->


            <!-- Offices - Start -->
            <div class="row form-group py-2 border-0 align-items-start">
                @php $label = 'label.$company.form.label.office' @endphp

                <div class="col-md-3 col-header">
                    <strong class="field-title fs-15">@lang("{$label}.label")</strong>
                </div>

                <div class="col-md-9 col-content mt-1 mt-md-0">

                    <!-- Sortable - Start -->
                    <draggable v-model="item.offices" group="offices" class="sortable offices" handle=".drag-handle"
                        animation="300" easing="cubic-bezier(0.165, 0.84, 0.44, 1)" :disabled="status.loading">

                        <div v-for="( office, index ) in item.offices" class="sortable-item mx-n2 px-2">
                            <div class="border-top">
                                <div class="row mx-n1 mx-md-n2 py-3 py-md-2">

                                    <div class="px-1 px-md-2 col-md-8 col-lg-9">
                                        <div class="row mx-n1">
                                            <div class="px-1 col-md-1 col-lg-1 d-flex align-items-center drag-handle draggable">
                                                <span>#@{{ index +1 }}</span>
                                            </div>
                                            <div class="px-1 col-md-11 col-lg-11 mt-1 mt-sm-0">
                                                <div class="input-container mb-2">
                                                    <input type="text" :name="'office-' +index+ '-name'" class="form-control" v-model.trim="office.name"
                                                        placeholder="@lang( "{$label}.name" )" :disabled="status.loading" data-parsley-maxlength="128"
                                                        :required="( office.address && office.address.length ) || office.number != null"
                                                        data-parsley-trigger="change focusout" data-parsley-no-focus />
                                                </div>

                                                <div class="input-container mb-2">
                                                    <input type="text" :name="'office-' +index+ '-address'" class="form-control" v-model.trim="office.address"
                                                        placeholder="@lang( "{$label}.address" )" :disabled="status.loading" data-parsley-maxlength="128"
                                                        data-parsley-trigger="change focusout" data-parsley-no-focus />
                                                </div>

                                                <div class="input-container">
                                                    <input type="text" :name="'office-' +index+ '-number'" class="form-control" v-model.trim="office.number"
                                                        placeholder="@lang( "{$label}.number" )" :disabled="status.loading" data-parsley-phone-number="14"
                                                        data-parsley-trigger="change focusout" data-parsley-no-focus />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-1 px-md-2 col-md-4 col-lg-3 mt-2 mt-md-0 drag-handle draggable">
                                        <div class="row mx-n1">
                                            <div class="px-1 col-6">

                                                <!-- Add button - Start -->
                                                <button v-if="!index" type="button" class="btn btn-block btn-outline-info btn-sm" @click="addAgentOffice" :disabled="status.loading">
                                                    <i class="fas fa-plus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.add')</span>
                                                </button>
                                                <!-- Add button - End -->

                                                <!-- Delete button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-danger btn-sm" @click="removeAgentOffice( index )" :disabled="status.loading">
                                                    <i class="fas fa-minus-circle fs-13"></i>
                                                    <span>@lang('label.$company.form.button.delete')</span>
                                                </button>
                                                <!-- Delete button - End -->

                                            </div>
                                            <div class="px-1 col-6" v-if="!index">

                                                <!-- Duplicate button - Start -->
                                                <button type="button" class="btn btn-block btn-outline-info btn-sm" @click="duplicateAgentOffice( office )" :disabled="status.loading">
                                                    <i class="fas fa-copy fs-13"></i>
                                                    <span>@lang('label.$company.form.button.copy')</span>
                                                </button>
                                                <!-- Duplicate button - End -->

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
            </div>
            <!-- Offices - Start -->


            <!-- Guarantee Association - Start -->
            <div class="row form-group py-2 border-0 align-items-start" v-if="associations.length">
                @php $label = 'label.$company.form.label.association' @endphp
                <div class="col-md-3 col-header py-2">
                    <strong class="field-title fs-15">@lang( $label )</strong>
                </div>
                <div class="col-md-9 col-content">

                    <!-- Guarantee Association selection - Start -->
                    <div class="row mx-n2 mb-2">
                        <div v-for="( entry, index ) in associations" class="px-2 col-4 col-sm-3 col-lg-2">
                            <div class="icheck-cyan">
                                <input type="radio" :value="entry.key" v-model="item.real_estate_guarantee_association" :id="'guarantee-association-' + index+1" name="guarantee-association" :disabled="status.loading"/>
                                <label :for="'guarantee-association-' + index+1" class="text-uppercase">@{{ entry.value }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- Guarantee Association selection - End -->

                    <template v-if="'other' == item.real_estate_guarantee_association">

                        <!-- Agent Depositary Name - Start -->
                        <div class="row form-group py-2 border-0 paste-in">
                            @php $label = 'label.$company.form.label.depositary.name' @endphp
                            <div class="col-md-3 col-lg-2 col-header">
                                <strong class="field-title fs-15">@lang( $label )</strong>
                            </div>
                            <div class="col-md-9 col-lg-10 col-content">
                                <input type="text" id="company-depositary-name" name="company-depositary-name" class="form-control"
                                    v-model.trim="item.real_estate_agent_depositary_name" placeholder="@lang( $label )" :disabled="status.loading"
                                    data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus />
                            </div>
                        </div>
                        <!-- Agent Depositary Name - End -->

                        <!-- Agent Depositary Address - Start -->
                        <div class="row form-group py-2 border-0 paste-in">
                            @php $label = 'label.$company.form.label.depositary.address' @endphp
                            <div class="col-md-3 col-lg-2 col-header">
                                <strong class="field-title fs-15">@lang( $label )</strong>
                            </div>
                            <div class="col-md-9 col-lg-10 col-content">
                                <input type="text" id="company-depositary-address" name="company-depositary-address" class="form-control"
                                    v-model.trim="item.real_estate_agent_depositary_name_address" placeholder="@lang( $label )" :disabled="status.loading"
                                    data-parsley-maxlength="128" data-parsley-trigger="change focusout" data-parsley-no-focus />
                            </div>
                        </div>
                        <!-- Agent Depositary Address - End -->

                    </template>
                </div>
            </div>
            <!-- Guarantee Association - End -->

        </div>
    </div>
</transition>
