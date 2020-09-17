<!-- Registration - Start -->
<div class="row mt-2">
    <div class="col-12 mt-2">
        @php $name = 'cooperation-registration' @endphp

        <!-- Registration check - Start -->
        <div class="my-2">
            <div class="icheck-cyan">
                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.registration" />
                <label for="{{ $name }}">
                    <small class="form-text text-muted my-0">@lang('users.registration')</small>
                </label>
            </div>
        </div>
        <!-- Registration check - End -->

        <!-- Registration text - Start -->
        <textarea id="{{ "{$name}-text" }}" name="{{ "{$name}-text" }}" v-model="item.registration_text"
            class="form-control" rows="4" placeholder="@lang('users.registration_text')">
        </textarea>
        <!-- Registration text - End -->

    </div>
</div>
<!-- Registration - End -->