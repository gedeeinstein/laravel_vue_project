<li class="expense-row list-group-item py-0" :class="{ 'highlighted': !entry.id && isAdditional }">
    <div class="row mx-n2 pb-3 pb-lg-0">
        <div class="px-2 col-lg-3 mt-3 mt-lg-0">
            <div class="row h-100">
                @if( !empty( $title ))
                    <div class="col d-flex align-items-center justify-content-start justify-content-lg-center py-2">
                        {{ $title }}
                    </div>
                @endif
                @if( !empty( $button ))
                    <div class="col-auto">
                        {{ $button }}
                    </div>
                @endif
            </div>
        </div>
        <div class="px-2 col-lg-5 py-0 py-sm-2">
            <div class="row mx-n1">
                @if( !empty( $input ))
                    {{ $input }}
                @endif
            </div>
        </div>
        <div class="px-2 col-lg-4 d-flex align-items-center">
            <div class="pl-0 pl-lg-2 w-100">
                <div class="row mx-n1">
                    <div class="px-1 col py-0 py-2 py-sm-0 py-lg-2">
                        <div class="row mx-n1">
                            @if( !empty( $total ))
                                {{ $total }}
                            @endif
                        </div>
                    </div>
                    @if( !empty( $delete ))
                        <div class="px-1 col-12 col-lg-auto mt-sm-2 mt-lg-0">
                            <div class="pl-0 pl-lg-3 h-100 text-right">
                                {{ $delete }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</li>