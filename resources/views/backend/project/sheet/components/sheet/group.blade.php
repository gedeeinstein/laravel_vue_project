@if( !empty( $model ))
    @php
        // ------------------------------------------------------------------
        $id = $id ?? 'accordion-group';
        $alias = $alias ?? false;
        $title = $title ?? 'Title';
        $parent = $parent ?? false;
        $content = $content ?? false;
        $expanded = $expanded ?? false;
        // ------------------------------------------------------------------
        $collapseClasses = collect([ 'accordion-collapse', 'collapse' ]);
        if( $expanded ) $collapseClasses->push( 'show' );
        // ------------------------------------------------------------------
    @endphp

    <template v-if="{{ $model }}" @if( $alias ) v-for="{{ $alias }} in [ {{ $model }} ]" @endif>
        <div class="card mb-0">
            <template v-for="id in [{!! $id !!}]">
                <div class="card-header p-2 cursor-pointer collapsed" id="group-purchasing" data-toggle="collapse" :data-target="'#'+id" 
                    aria-expanded="{{ $expanded ? 'true' : 'false' }}" aria-controls="collapse-group-purchasing">
                    <button type="button" class="btn btn-accordion">
                        <span>{{ $title }}</span>
                    </button>
                </div>
                @if( $content )
                    <div :id="id" class="{{ $collapseClasses->join(' ') }}" aria-labelledby="group-purchasing" @if( $parent) :data-parent="'.' +{{ $parent }}" @endif>
                        <div class="card-body p-2 p-md-3">
                            {{ $content }}
                        </div>
                    </div>
                @endif
            </template>
        </div>
    </template>
@endif