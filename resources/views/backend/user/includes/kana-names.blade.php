<!-- Last and first Kana names - Start -->
@component( "{$component}.field" )
    @slot( 'required', true )
    @slot( 'label', __('users.name_kana'))

    <!-- Last Kana name - Start -->
    <div class="col-md-6 mt-2 mt-md-0">
        <input v-model="item.last_name_kana" type="text" placeholder="氏名（カナ）（姓）" 
            id="last_name_kana" name="last_name_kana" class="form-control" data-parsley-no-focus
            required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
    <!-- Last Kana name - End -->

    <!-- First Kana name - Start -->
    <div class="col-md-6 mt-2 mt-md-0">
        <input v-model="item.first_name_kana" type="text" placeholder="氏名（カナ）（名）" 
            id="first_name_kana" name="first_name_kana" class="form-control" data-parsley-no-focus
            required data-parsley-trigger="change focusout" :disabled="status.loading" data-parsley-maxlength="128" />
    </div>
    <!-- First Kana name - End -->

@endcomponent
<!-- Last and first Kana names - End -->