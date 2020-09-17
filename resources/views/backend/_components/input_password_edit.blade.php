<div id="form-group--password" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        <span class="bg-success label-required">@lang('label.optional')</span>
        <i class="fa fa-question-circle tooltip-img" data-toggle="tooltip" data-placement="right" title="@lang('label.choosePasswordLength')"></i>
        <strong class="field-title">@lang('label.password')</strong>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 col-content">
        <button type="button" name="reset" id="reset-button" class="btn btn-outline-info">@lang('label.change')</button>
    </div>
    <div id="reset-field" class="col-xs-10 col-sm-10 col-md-8 col-lg-9 col-content d-none">
        <input type="password" id="input-password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old("password") }}" data-parsley-minlength="8" />
        <label for="show-password">
            <input id="show-password" type="checkbox" name="show-password" value="1">
            <span>@lang('label.showPassword')</span>
        </label>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">

    </div>
</div>
