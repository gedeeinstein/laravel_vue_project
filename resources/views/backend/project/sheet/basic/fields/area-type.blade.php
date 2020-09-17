<div class="form-group">
    @php 
        $name = 'basic-area-type';
        $label = __('project.sheet.label.area_type');
    @endphp
    <label for="{{ $name }}">{{ $label }}</label>
    @if( !empty( $areaTypes ))
        <select name="{{ $name }}" id="{{ $name }}" class="form-control" v-model.number="project.usage_area" :disabled="status.loading">
            <option value="0"></option>
            @foreach( $areaTypes as $type )
                <option value="{{ $type->id }}">{{ $type->value }}</option>
            @endforeach
        </select>
    @endif
</div>