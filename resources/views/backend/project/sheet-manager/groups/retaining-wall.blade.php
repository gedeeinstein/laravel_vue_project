<template v-if="'retaining-wall' == group.name">
    <div class="row my-n3 mx-n3 mx-md-n4 p-3" :class="{ 'bg-light': groupIndex % 2 }">

        @include( "{$includes}.group-title" )

        <div class="col-md-9" v-if="group.fields && group.fields.length">
            <template v-for="( row, rowIndex ) in group.fields">
                <div v-if="row.length" class="row" :class="{ 'mt-md-3': rowIndex }">

                    <div class="col-12 d-block d-md-none"><hr/></div>
                    <div class="col-md-3 col-lg-2 d-flex align-items-center">
                        <span class="fs-13">@{{ row[0].label }}</span>
                    </div>
                    
                    <div class="col-md-9 col-lg-10">
                        <div class="row">
                            <template v-for="( field, fieldIndex ) in row">

                                @component( "{$includes}.column-first" ) 
                                    @slot( 'label', '長さ' )
                                @endcomponent

                                @include( "{$includes}.column-second")

                            </template>
                        </div>
                    </div>

                </div>
            </template>
            
            <div class="row mt-md-3">

                <div class="col-12 d-block d-md-none"><hr/></div>
                <div class="col-md-3 col-lg-2 py-md-2">高さ1.95m超</div>
                
                <div class="col-md-9 col-lg-10">
                    <div class="row">

                        <div class="col-md-8">
                            <div class="row mx-n1">
                                <div class="px-1 col-5 py-2">
                                    <span>自動計算なし</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>                    
    <hr />
</template>