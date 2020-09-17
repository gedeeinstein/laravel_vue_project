<div id="form-group--{{ $name }}" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <div class="field-group clearfix @error($name) is-invalid @enderror">
            @foreach($options as $key => $option)
                @php
                    $current_value = !empty($is_indexed_value) ? $loop->index + 1 : $option;
                    if (isset($value_key) && $value_key) {
                        $current_value = $key;
                    }
                @endphp
                <div class="icheck-cyan d-inline">
                    <input type="radio" value="{{ $current_value }}" id="input-{{ $name }}-{{ $loop->index }}" name="{{ $name }}" {{ !empty($loop->first) && (!empty($value) || $value == "") ? "checked" : "" }} {{ $value == $current_value ? "checked" : "" }} />
                    <label for="input-{{ $name }}-{{ $loop->index }}" class="text-uppercase mr-5">{{ $option }}</label>
                </div>
            @endforeach
            {{ $slot }}
        </div>
    </div>
</div>
