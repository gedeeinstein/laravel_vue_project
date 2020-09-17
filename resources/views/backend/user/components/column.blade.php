<div class="row form-group py-3">
    @if( !empty( $label ))
        <div class="col-md-4 col-lg-3 col-header">
            {{ $label }}
        </div>
    @endif
    @if( !empty( $content ))
        <div class="col-md-8 col-lg-9 col-content">
            <div class="row">
                {{ $content }}
            </div>
        </div>
    @endif
</div>