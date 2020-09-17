<!-- Password - Start -->
@component( "{$component}.field" )
    @php $required = 'create' == $page_type @endphp
    
    @slot( 'required', $required )
    @slot( 'label', __('users.password'))

    <!-- Password - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text fs-12" for="password">
                    <i class="fas fa-key"></i>
                </label>
            </div>
            <input v-model.trim="item.password" type="password" id="password" name="password" class="form-control" placeholder="@lang('users.password')"
                data-parsley-minlength="8" data-parsley-no-focus data-parsley-trigger="change focusout"
                :required="{{ $required ? 'true' : 'false' }}" :disabled="status.loading" />
        </div>
        <div class="form-result"></div>
    </div>
    <!-- Password - End -->

@endcomponent
<!-- Password - End -->