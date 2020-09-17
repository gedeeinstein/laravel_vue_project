<!-- Real estate notary - Start -->
@component( "{$component}.field" )
    @slot( 'align', 'top' )
    @slot( 'label', __('users.real_estate.registration'))

    <!-- Real estate notary registration - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <div class="real-estate-notary">

            <div class="icheck-cyan">
                <input type="checkbox" id="real-estate-notary" name="real-estate-notary" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.real_estate_notary_registration" />
                <label for="real-estate-notary" class="text-uppercase mr-5 fs-12 noselect">@lang('users.register')</label>
            </div>

        </div>
        <transition name="paste-in">
            <div class="row" v-if="item.real_estate_notary_registration">
    
                <!-- Real estate office - Start -->
                <div class="col-12 mt-2">
                    <small class="form-text text-muted mb-2">@lang('users.real_estate.office')</small>
                    <select v-model.number="item.real_estate_notary_office_id" type="text" 
                        id="real-estate-office" name="real-estate-office" class="form-control">
                        @foreach( $company->offices as $office )
                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Real estate office - End -->
                
                <!-- Real estate prefecture - Start -->
                <div class="col-12 col-md-6 mt-2">
                    <small class="form-text text-muted mb-2">@lang('users.real_estate.prefecture')</small>
                    <select v-model.number="item.real_estate_notary_prefecture_id" type="text" 
                        id="real-estate-prefecture" name="real-estate-prefecture" class="form-control">
                        @foreach( $prefectures as $entry )
                            <option value="{{ $entry->id }}">{{ $entry->value }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Real estate prefecture - End -->
    
                <!-- Real estate notary number - Start -->
                <div class="col-12 col-md-6 mt-2">
                    <small class="form-text text-muted mb-2">@lang('users.real_estate.number')</small>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">第</span>
                        </div>
                        <input class="form-control" id="real-estate-number" name="real-estate-number" v-model.trim="item.real_estate_notary_number" 
                            type="text" data-parsley-no-focus data-parsley-trigger="change focusout" data-parsley-maxlength="14" />
                        <div class="input-group-append">
                            <span class="input-group-text">号</span>
                        </div>
                    </div>
                    <div class="form-result"></div>
                </div>
                <!-- Real estate notary number - Start -->
    
            </div>
        </transition>
    </div>
    <!-- Real estate notary registration - End -->

@endcomponent
<!-- Real estate notary - End -->