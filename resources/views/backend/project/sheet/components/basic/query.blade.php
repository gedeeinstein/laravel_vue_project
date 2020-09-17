<li class="list-group-item">
    @if( isset( $question, $choices ))
        <div class="row">
            @if( !empty( $question ))
                <div class="col-md-6">
                    {{ $question }}
                </div>
            @endif
            @if( !empty( $choices ))
                <div class="col-md-6">
                    {{ $choices }}
                </div>
            @endif
        </div>
    @else
        @php $index = 1 @endphp
        @while( true )
            @break( empty( ${"question_{$index}"} ))
            @php
                $classes = array('row');
                if( $index > 1 ) $classes[] = 'mt-2';
            @endphp
            <div class="{{ join( ' ', $classes )}}">
                @if( !empty( ${"question_{$index}"} ))
                    <div class="col-md-6">
                        {{ ${"question_{$index}"} }}
                    </div>
                @endif
                @if( !empty( ${"choices_{$index}"} ))
                    <div class="col-md-6">
                        {{ ${"choices_{$index}"} }}
                    </div>
                @endif
            </div>
            @php $index++ @endphp
        @endwhile
    @endif
</li>