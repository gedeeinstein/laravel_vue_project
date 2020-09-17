<div class="card-footer text-center">
    <button type="submit" class="btn btn-info" id="input-submit">
        <i class="fas fa-save"></i> {{ $page_type == 'create' ? __('label.register') : __('label.update')  }}
    </button>
    {{ $slot }}
</div>
