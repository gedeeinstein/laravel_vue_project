<div class="{{ $column->left }}">
    <div class="question">
        <span>E1.</span>
        <span>@lang('project.sheet.checklist.label.development.cost')</span>
    </div>
</div>

<div class="{{ $column->right }}">

    <div class="form-group mb-0" v-for="name in [ 'sheet-' +( sheetIndex +1 )+ '-checklist-development-cost' ]">
        <div class="row">
            @php 
                $col = 'col-sm-6 col-md-auto';
                $label = 'project.sheet.checklist.option.construction';
            @endphp
            <div class="{{ $col }}">

                @component("{$component->common}.radio")
                    @slot( 'value', 1 )
                    @slot( 'model', 'checklist.development_cost' )
                    @slot( 'label', __( "{$label}.cost.flat" ))
                @endcomponent
    
            </div>
            <div class="{{ $col }}">

                @component("{$component->common}.radio")
                    @slot( 'value', 2 )
                    @slot( 'model', 'checklist.development_cost' )
                    @slot( 'label', __( "{$label}.cost.one" ))
                @endcomponent
    
            </div>
            <div class="{{ $col }}">

                @component("{$component->common}.radio")
                    @slot( 'value', 3 )
                    @slot( 'model', 'checklist.development_cost' )
                    @slot( 'label', __( "{$label}.cost.two" ))
                @endcomponent
    
            </div>
            <div class="{{ $col }}">

                @component("{$component->common}.radio")
                    @slot( 'value', 4 )
                    @slot( 'model', 'checklist.development_cost' )
                    @slot( 'label', __( "{$label}.cost.above_two" ))
                @endcomponent
    
            </div>
        </div>
    </div>

    <!-- Distant pipe / infastructure - Start -->
    <div class="form-group mb-0" v-for="name in [ 'sheet-' +( sheetIndex +1 )+ '-checklist-distant-pipe' ]">

        @component("{$component->common}.checkbox")
            @slot( 'name', "name" )
            @slot( 'model', 'checklist.main_pipe_is_distant' )
            @slot( 'label', __( "{$label}.distant" ))
        @endcomponent

    </div>
    <!-- Distant pipe / infastructure - Start -->

</div>