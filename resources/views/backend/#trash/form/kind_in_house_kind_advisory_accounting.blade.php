{{-- v-if="company.kind_in_house && company.kind_advisory_accounting ///v-if="item.FullName !== null && item.FullName !== ''--}}
{{-- user nick name--}}
<div class="row form-group border-0 align-items-start" v-if="company.kind_in_house || company.kind_advisory_accounting">
    <div class="col-md-3 col-header">
        <span class="bg-success label-required">@lang('label.optional')</span>
        <strong class="field-title">@lang('users.nickname')</strong>
    </div>
    <div class="col-md-9 col-content mt-1 mt-md-0">
        <input v-model="item.nickname" type="text" id="nickname" name="nickname" class="form-control" data-parsley-no-focus
            data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
</div>
{{-- //user nick name--}}

{{-- ADMINISTRATOR ONLY --}}
@if( 3 != $user->admin_role_id )
    <template v-if="company.kind_in_house || company.kind_advisory_accounting">

        {{-- port login authority ADMINISTRATOR--}}
        <div class="row form-group border-0 align-items-start">
            <div class="col-md-3 col-header">
                <span class="bg-success label-required">@lang('label.optional')</span>
                <strong class="field-title">@lang('users.login_authority')</strong>
            </div>
            <div class="col-md-9 col-content mt-1 mt-md-0">
                <select v-model.number="item.login_authority" id="input-login_authority" name="login_authority" class="form-control" data-parsley-gte="1">
                    @foreach( $user_roles as $role )
                        <option value="{{ $role->id }}">{{ $role->label }}</option>
                    @endforeach
                </select>
                <small id="Help" class="form-text text-muted">@lang('users.helpTextPortLoginAuthority')</small>
            </div>
        </div>
        {{-- //port login authority--}}

        {{-- email company user --}}
        <div class="row form-group border-0 align-items-start">
            <div class="col-md-3 col-header">
                <div v-if="item.login_authority === 1">
                    <span class="bg-success label-required">@lang('label.optional')</span>
                </div>
                <div v-else>
                    <span class="bg-danger label-required">@lang('label.required')</span>
                </div>
                <strong class="field-title">@lang('users.emailUser')</strong>
            </div>
            <div class="col-md-9 col-content mt-1 mt-md-0">
                <input v-model="item.email" type="email" id="email" name="email" class="form-control" data-parsley-no-focus
                    :required="parseInt( item.login_authority ) !== 1" data-parsley-trigger="change focusout" :class="{ 'parsley-excluded': parseInt( item.login_authority ) === 1 }" :disabled="parseInt( item.login_authority ) === 1" />
            </div>
        </div>
        {{-- //email company user --}}
    </template>

@endif
<div v-if="!company.kind_in_house" class="row form-group border-0 align-items-start">
    <div class="col-md-3 col-header">
        <div class="icheck-cyan">
            {{-- Display this section when not checked G122-3 of company--}}
            <input v-bind:true-value="1" v-bind:false-value="0" :value="item.cooperation_registration" type="checkbox" id="cooperation_registration" name="cooperation_registration" v-model="item.cooperation_registration" data-parsley-checkmin="1" />
            <label for="cooperation_registration" class="text-uppercase mr-5 fs-12 noselect">@lang('users.cooperation_registration')</label>
        </div>
    </div>
    <div class="col-md-9 col-content" v-if="item.cooperation_registration">
        <div class="partner">
            <div class="px-2 paste-in">
                <div class="icheck-cyan">
                    <input v-bind:true-value="1" v-bind:false-value="0" :value="item.real_estate_information" type="checkbox" id="real_estate_information" name="real_estate_information" v-model="item.real_estate_information" />
                    <label for="real_estate_information" class="text-uppercase mr-5 fs-12">@lang('users.real_estate_information')</label>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-content">
                <div class="py-2 border-0">
                    <textarea v-model="item.real_estate_information_text" id="real_estate_information_text" name="real_estate_information_text" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
        <div class="partner">
            <div class="px-2 col-4 paste-in">
                <div class="icheck-cyan">
                    <input v-bind:true-value="1" v-bind:false-value="0" :value="item.registration" type="checkbox" id="registration" name="registration" v-model="item.registration" />
                    <label for="registration" class="text-uppercase mr-5 fs-12">@lang('users.registration')</label>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-content">
                <div class="py-2 border-0">
                    <textarea v-model="item.registration_text" id="registration_text" name="registration_text" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
        <div class="partner">
            <div class="px-2 col-4 paste-in">
                <div class="icheck-cyan">
                    <input v-bind:true-value="1" v-bind:false-value="0" :value="item.surveying" type="checkbox" id="surveying" name="surveying" v-model="item.surveying" />
                    <label for="surveying" class="text-uppercase mr-5 fs-12">@lang('users.surveying')</label>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-content">
                <div class="py-2 border-0">
                    <textarea v-model="item.surveying_text" id="surveying_text" name="surveying_text" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
        <div class="partner">
            <div class="px-2 col-4 paste-in">
                <div class="icheck-cyan">
                    <input v-bind:true-value="1" v-bind:false-value="0" :value="item.clothes" type="checkbox" id="clothes" name="clothes" v-model="item.clothes" />
                    <label for="clothes" class="text-uppercase mr-5 fs-12">@lang('users.clothes')</label>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-content">
                <div class="py-2 border-0">
                    <textarea v-model="item.clothes_text" id="clothes_text" name="clothes_text" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
        <div class="partner">
            <div class="px-2 col-4 paste-in">
                <div class="icheck-cyan">
                    <input v-bind:true-value="1" v-bind:false-value="0" :value="item.other" type="checkbox" id="other" name="other" v-model="item.other" />
                    <label for="other" class="text-uppercase mr-5 fs-12">@lang('users.other')</label>
                </div>
            </div>
            <div class="col-md-9 col-lg-9 col-content">
                <div class="py-2 border-0">
                    <textarea v-model="item.other_text" id="other_text" name="other_text" class="form-control" rows="4"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

