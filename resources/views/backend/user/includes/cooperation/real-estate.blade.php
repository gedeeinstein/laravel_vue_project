<!-- Real estate information - Start -->
<div class="row mt-2">
    <div class="col-12 mt-2">
        @php $name = 'cooperation-real-estate' @endphp

        <!-- Real estate information check - Start -->
        <div class="my-2">
            <div class="icheck-cyan">
                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.real_estate_information" />
                <label for="{{ $name }}">
                    <small class="form-text text-muted my-0">@lang('users.real_estate_information')</small>
                </label>
            </div>
        </div>
        <!-- Real estate information check - End -->

        <!-- Real estate information text - Start -->
        <textarea id="{{ "{$name}-text" }}" name="{{ "{$name}-text" }}" v-model="item.real_estate_information_text"
            class="form-control" rows="4" placeholder="@lang('users.real_estate_information_text')">
        </textarea>
        <!-- Real estate information text - End -->

    </div>
</div>
<!-- Real estate information - End -->