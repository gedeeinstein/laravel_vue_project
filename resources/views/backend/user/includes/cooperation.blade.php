<!-- Cooperation registration - Start -->
@component( "{$component}.field" )
    @slot( 'align', 'top' )
    @slot( 'label', __('users.cooperation_registration'))

    <!-- Cooperation registration - Start -->
    <div class="col-lg-10 mt-2 mt-lg-0">
        <div class="cooperation">

            <div class="icheck-cyan">
                <input type="checkbox" id="cooperation" name="cooperation" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.cooperation_registration" />
                <label for="cooperation" class="text-uppercase mr-5 fs-12 noselect">@lang('users.register')</label>
            </div>

        </div>
        <transition name="paste-in">
            <div class="cooperation-form" v-if="item.cooperation_registration">

                <!-- Real estate information - Start -->
                @include("{$include}.cooperation.real-estate")
                <!-- Real estate information - End -->

                <!-- Registration - Start -->
                @include("{$include}.cooperation.registration")
                <!-- Registration - End -->

                <!-- Surveying - Start -->
                @include("{$include}.cooperation.surveying")
                <!-- Surveying - End -->

                <!-- Clothes - Start -->
                @include("{$include}.cooperation.clothes")
                <!-- Clothes - End -->

                <!-- Other - Start -->
                @include("{$include}.cooperation.other")
                <!-- Other - End -->

            </div>
        </transition>
    </div>
    <!-- Cooperation registration - End -->

@endcomponent
<!-- Cooperation registration - End -->