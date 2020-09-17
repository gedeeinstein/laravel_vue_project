<!-- Company - Start -->
@component( "{$component}.field" )
    @slot( 'label', __('users.company'))

    <!-- Company - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <select id="email" name="email" class="form-control" v-model="item.company_id">
            <option :value="null">@lang('users.individual')</option>
            @foreach( $companies as $company )
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>
    <!-- Company - End -->

@endcomponent
<!-- Company - End -->