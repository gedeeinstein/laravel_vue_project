@php $heading = 'project.sheet.expense.heading' @endphp
<li class="list-group-item py-0">
    <div class="row d-none d-lg-flex">
        <div class="offset-lg-3 col-lg-5 py-2 text-center">
            @if( !empty( $input ))
                <span>{{ $input }}</span>
            @else 
                <span>@lang("{$heading}.budget")</span>
            @endif
        </div>
        <div class="col-lg-4 py-2 text-center">
            @if( !empty( $total ))
                <span>{{ $total }}</span>
            @else 
            <span>@lang("{$heading}.total")</span>
            @endif
        </div>
    </div>
</li>