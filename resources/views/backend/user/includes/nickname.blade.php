<!-- Nickname - Start -->
@component( "{$component}.field" )
    @slot( 'label', __('users.nickname'))

    <!-- Nickname - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <input v-model="item.nickname" type="text" id="nickname" name="nickname" class="form-control" placeholder="@lang('users.nickname')" 
            data-parsley-no-focus data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
    <!-- Nickname - End -->

@endcomponent
<!-- Nickname - End -->