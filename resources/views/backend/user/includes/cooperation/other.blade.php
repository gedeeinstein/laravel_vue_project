<!-- Other - Start -->
<div class="row mt-2">
    <div class="col-12 mt-2">
        @php $name = 'cooperation-other' @endphp

        <!-- Other check - Start -->
        <div class="my-2">
            <div class="icheck-cyan">
                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.other" />
                <label for="{{ $name }}">
                    <small class="form-text text-muted my-0">@lang('users.surveying')</small>
                </label>
            </div>
        </div>
        <!-- Other check - End -->

        <!-- Other text - Start -->
        <textarea id="{{ "{$name}-text" }}" name="{{ "{$name}-text" }}" v-model="item.other_text"
            class="form-control" rows="4" placeholder="@lang('users.surveying_text')">
        </textarea>
        <!-- Other text - End -->

    </div>
</div>
<!-- Other - End -->