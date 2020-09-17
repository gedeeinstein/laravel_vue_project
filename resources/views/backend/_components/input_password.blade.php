<div id="form-group--password" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        <span class="bg-danger label-required">@lang('label.required')</span>
        <i class="fa fa-question-circle tooltip-img" data-toggle="tooltip" data-placement="right" title="@lang('label.choosePasswordLength')"></i>
        <strong class="field-title">@lang('label.password')</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <input type="password" id="input-password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old("password") }}" required data-parsley-minlength="8"/>
    </div>
</div>
