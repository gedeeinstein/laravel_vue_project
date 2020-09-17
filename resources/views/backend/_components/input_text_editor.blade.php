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
        <textarea id="input-{{ $name }}" name="{{ $name }}" class="text-editor datepicker    @error($name) is-invalid @enderror" {{ !empty($required) ? 'required' : '' }}>{{ !empty($value) ? $value : old($name) }}</textarea>
    </div>
</div>
