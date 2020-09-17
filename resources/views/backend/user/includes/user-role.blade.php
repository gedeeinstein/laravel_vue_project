<!-- User role - Start -->
@component( "{$component}.field" )
    @slot( 'label', __('users.login_authority'))

    <!-- User role - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <select v-model.number="item.user_role_id" id="user-role" name="user-role" class="form-control" data-parsley-gte="1"
            data-parsley-no-focus data-parsley-trigger="change focusout" :disabled="status.loading">

            @foreach( $user_roles as $role )
                <option value="{{ $role->id }}">{{ $role->label }}</option>
            @endforeach

        </select>
        {{-- <small id="user-role-info" class="form-text text-muted">@lang('users.notification')</small> --}}
    </div>
    <!-- User role - End -->

@endcomponent
<!-- User role - End -->