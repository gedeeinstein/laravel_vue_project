<div class="form-group row @error($name) is-invalid @enderror" id="div-{{ $name }}" style="padding: 5px;">
  <label style="font-weight: normal;" for="question-{{ $name }}" class="col-8 col-form-label">@lang( 'pj_purchase_response.'.$question )</label>
  <div class="col-4 {{ !empty($text) ? 'row' : '' }} {{ !empty($text2) ? 'row' : '' }}">

    <div class="icheck-cyan d-inline">
        <input type="radio" id="{{ $radio1 }}" name="{{ $name }}"  value="1" {{ old($name) == "1" ? 'checked' : '' }} data-name="{{ $name }}" />
        <label style="font-weight: normal;" for="{{ $radio1 }}" class="text-uppercase mr-5">@lang('pj_purchase_response.'.$yes)</label>
    </div>

    @if (!empty($text))
      <input style="display: {{ old($text) ? 'block' : 'none' }};" class="form-control col-5 mr-3" name="{{ $text }}" id="{{ $text }}" type="text" value="{{ old($text) }}">
    @endif

    <div class="icheck-cyan d-inline">
        <input type="radio" id="{{ $radio2 }}" name="{{ $name }}" class="custom-control-input" value="2" {{ old($name) == "2" ? 'checked' : '' }} data-name="{{ $name }}"/>
        <label style="font-weight: normal;" for="{{ $radio2 }}" class="text-uppercase mr-5">@lang('pj_purchase_response.'.$no)</label>
    </div>

    @if (!empty($text2))
      <input style="display: {{ old($text2) ? 'block' : 'none' }};" class="form-control col-5 mr-3" name="{{ $text2 }}" id="{{ $text2 }}" type="text" value="{{ old($text2) }}">
    @endif
  </div>
</div>
