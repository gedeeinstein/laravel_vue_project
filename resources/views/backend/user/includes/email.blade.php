<!-- Email - Start -->
@component( "{$component}.field" )
    @slot( 'required', true )
    @slot( 'label', __('users.emailUser'))

    <!-- Email - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text fs-12" for="email">
                    <i v-if="verification.loading" class="fas fa-cog fa-spin"></i>
                    <i v-else class="fas fa-envelope"></i>
                </label>
            </div>
            <input v-model="item.email" type="email" id="email" name="email" class="form-control" placeholder="@lang('users.emailUser')"
                data-parsley-no-focus required data-parsley-trigger="change focusout" data-parsley-type="email" 
                :disabled="status.loading" @change="verifyUserEmail( item )" @blur="verifyUserEmail( item )" />
        </div>
        <div class="form-result">
            <template v-if="!verification.verified && verification.message">
                <div class="verification-error text-danger fs-12">
                    <span>@{{ verification.message }}</span>
                </div>
            </template>
        </div>
    </div>
    <!-- Email - End -->

@endcomponent
<!-- Email - End -->