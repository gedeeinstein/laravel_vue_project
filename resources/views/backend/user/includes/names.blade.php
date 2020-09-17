<!-- Last and first names - Start -->
@component( "{$component}.field" )
    @slot( 'required', true )
    @slot( 'label', __('label.name'))

    <!-- Last name - Start -->
    <div class="col-md-6 mt-2 mt-md-0">
        <input v-model="item.last_name" type="text" placeholder="氏名（姓）" id="last_name" name="last_name" 
            class="form-control" :disabled="status.loading" required data-parsley-no-focus 
            data-parsley-trigger="change focusout" data-parsley-maxlength="128" />
    </div>
    <!-- Last name - End -->

    <!-- First name - Start -->
    <div class="col-md-6 mt-2 mt-md-0">
        <input v-model="item.first_name" type="text" placeholder="氏名（名）" id="first_name" name="first_name" 
            class="form-control" :disabled="status.loading" required data-parsley-no-focus
            data-parsley-trigger="change focusout" data-parsley-maxlength="128" />
    </div>
    <!-- First name - End -->

@endcomponent
<!-- Last and first names - End -->