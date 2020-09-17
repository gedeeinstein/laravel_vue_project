<div v-if="company.kind_real_estate_agent" class="row form-group border-1 align-items-start">
    <div class="col-md-3 col-header py-2">
        <div class="paste-in">
            <div class="icheck-cyan">
                <input v-model="item.real_estate_notary_registration" :value="item.real_estate_notary_registration" v-bind:true-value="1" v-bind:false-value="0" type="checkbox" id="real_estate_notary_registration" name="real_estate_notary_registration" />
                <label for="real_estate_notary_registration" class="text-uppercase mr-5 fs-12">@lang('users.real_estate_notary_registration')</label>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-content mt-md-0">
        <div v-if="item.real_estate_notary_registration" class="py-2 border-0 mb-2">
            <div class="row form-group py-2 border-0 paste-in">
                <div class="col-md-12 col-lg-6 col-content">
                    <strong class="field-title fs-15 mb-2">@lang('users.real_estate_notary_office_id')</strong>
                    <select v-model.number="item.real_estate_notary_office_id" type="text" 
                        id="real_estate_notary_office_id" name="real_estate_notary_office_id" class="form-control">
                        @foreach( $company->offices as $office )
                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row form-group py-2 border-0 paste-in">
                <div class="col-md-6 col-lg-6 col-content">
                    <strong class="field-title fs-15 mb-2">@lang('users.real_estate_notary_prefecture_id')</strong>
                    <select v-model.number="item.real_estate_notary_prefecture_id" type="text" 
                        id="real_estate_notary_prefecture_id" name="real_estate_notary_prefecture_id" class="form-control">
                        @foreach( $prefectures as $entry )
                            <option value="{{ $entry->id }}" id="{{ $entry->key }}">{{ $entry->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-lg-6 col-content">
                    <strong class="field-title fs-15 mb-2">@lang('users.real_estate_notary_number')</strong>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">第</span>
                        </div>
                        <input v-model="item.real_estate_notary_number" type="text" id="real_estate_notary_number" name="real_estate_notary_number" class="form-control" />
                        <div class="input-group-append">
                            <span class="input-group-text">号</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>