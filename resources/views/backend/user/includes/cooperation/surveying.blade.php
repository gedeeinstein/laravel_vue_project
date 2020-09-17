<!-- Surveying - Start -->
<div class="row mt-2">
    <div class="col-12 mt-2">
        @php $name = 'cooperation-surveying' @endphp

        <!-- Surveying check - Start -->
        <div class="my-2">
            <div class="icheck-cyan">
                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" data-parsley-checkmin="1"
                    :disabled="status.loading" :true-value="1" :false-value="0" v-model.number="item.surveying" />
                <label for="{{ $name }}">
                    <small class="form-text text-muted my-0">@lang('users.surveying')</small>
                </label>
            </div>
        </div>
        <!-- Surveying check - End -->

        <!-- Surveying text - Start -->
        <textarea id="{{ "{$name}-text" }}" name="{{ "{$name}-text" }}" v-model="item.surveying_text"
            class="form-control" rows="4" placeholder="@lang('users.surveying_text')">
        </textarea>
        <!-- Surveying text - End -->

    </div>
</div>
<!-- Surveying - End -->