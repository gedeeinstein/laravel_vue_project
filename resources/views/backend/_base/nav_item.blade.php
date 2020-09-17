<li class="nav-item">
    @php
        $active = false;
        $currentURL = url()->full();
        $classes = array( 'nav-link' );
        $void = 'javascript:void(0);';
        
        if( empty( $url )) $url = $void;
        if( empty( $label )) $label = 'Nav';
        if( $currentURL == $url ){
            $active = true;
            $classes[] = 'active';
        }
    @endphp
    <a class="{{ join( ' ', $classes )}}" href="{{ $active ? $void : $url }}">
        <span>{{ $label }}</span>
    </a>
</li>