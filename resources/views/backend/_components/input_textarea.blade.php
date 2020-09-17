<div id="form-group--{{ $name }}" class="row form-group {{ !empty( $compact ) ? 'py-2 border-0': '' }}">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        @if (isset($tooltip))
            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="{{ $tooltip }}"></i>
        @endif
        <strong class="field-title">{{ $label  }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <textarea data-role="tagsinput" id="input-{{ $name }}" rows="{{ $rows ?? 3 }}" name="{{ $name }}" class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror" {{ !empty($required) ? 'required' : '' }}>{{ !empty($value) ? $value : old($name) }}</textarea>
        {{ $slot }}
    </div>
</div>
