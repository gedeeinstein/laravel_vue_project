<template v-if="'application-fee' == group.name">
    <div class="row my-n3 mx-n3 mx-md-n4 p-3" :class="{ 'bg-light': groupIndex % 2 }">
    
        @include( "{$includes}.group-title" )
    
        <div class="col-md-9">
            <template v-for="( row, rowIndex ) in group.fields">
                <div class="row" :class="{ 'mt-md-3': rowIndex }">
                    
                    <div class="col-12 d-block d-md-none"><hr/></div>
                    <div class="offset-md-3 offset-lg-2 col-md-9 col-lg-10">
                        <div class="row">
                            <template v-for="( field, fieldIndex ) in row">

                                @component( "{$includes}.column-first" ) @endcomponent
    
                            </template>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    
    </div>                    
    <hr />
</template>