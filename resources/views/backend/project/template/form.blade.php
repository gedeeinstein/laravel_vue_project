@extends('backend._base.content_project')

@section('content')

    <h5 class="my-4">Regular Card</h5>

    <div class="card card-project">
        <div class="card-header">
            Featured
        </div>
        <div class="card-body">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit soluta adipisci aut exercitationem dolorum assumenda minus harum veniam dolore eum nesciunt,
            placeat, voluptates inventore, voluptate vitae facilis dolores esse corporis.
        </div>
    </div>

    <h5 class="my-4">Collapsible group</h5>

    <div class="collapsible">
        <div class="card">
            <div class="card-header p-2" id="headingOne">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                    aria-controls="collapseOne">
                    Collapsible Group Item #1
                </button>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent=".collapsible">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header p-2" id="headingTwo">
                <button type="button" class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="false" aria-controls="collapseTwo">
                    Collapsible Group Item #2
                </button>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent=".collapsible">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header p-2" id="headingThree">
                <button type="button" class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="false" aria-controls="collapseThree">
                    Collapsible Group Item #3
                </button>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent=".collapsible">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>
    </div>

    <h5 class="my-4">Accordion group</h5>

    <div class="accordion">
        <div class="card mb-0">
            <div class="card-header p-2" id="headingOne">
                <button type="button" class="btn btn-accordion" data-toggle="collapse" data-target="#accordion-one" aria-expanded="true"
                    aria-controls="collapseOne">
                    Accordion Group Item #1
                </button>
            </div>

            <div id="accordion-one" class="collapse show" aria-labelledby="headingOne" data-parent=".accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>

        <div class="card mb-0">
            <div class="card-header p-2" id="headingTwo">
                <button type="button" class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#accordion-two"
                    aria-expanded="false" aria-controls="collapseTwo">
                    Accordion Group Item #2
                </button>
            </div>
            <div id="accordion-two" class="collapse" aria-labelledby="headingTwo" data-parent=".accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header p-2" id="headingThree">
                <button type="button" class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#accordion-three"
                    aria-expanded="false" aria-controls="collapseThree">
                    Accordion Group Item #3
                </button>
            </div>
            <div id="accordion-three" class="collapse" aria-labelledby="headingThree" data-parent=".accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
        </div>
    </div>

    <h5 class="my-4">Responsive Editable Tabs</h5>
    <p>This only the template, the functionality can be done using Vue</p>

    <!-- Project Sheet Tab - Start -->
    <div class="card project-sheets mt-4">
        <div class="card-header card-header-tabs compact-tabs"> <!-- compact-tabs -->
            <ul class="nav nav-tabs" id="projet-sheet-tabs" role="tablist">

                {{-- <!-- Tab button Vue binding sample - Start  -->
                <template v-for="tab in tabs">
                    <li class="nav-item">
                        <div class="nav-link cursor-pointer p-0" role="button" :class="{ active: tab.active }">
                            <div class="btn-group">
                                <button type="button" class="btn btn-tab pr-0" @click="activateTab(tab)">
                                    <span>@{{ tab.label }}</span>
                                </button>
                                <div class="btn-group dropdown" role="group">
                                    <button type="button" class="btn btn-tab dropdown-toggle dropdown-toggle-split position-static pl-2" data-toggle="dropdown">
                                        <span class="sr-only">Tab Options</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
                <!-- Tab button Vue binding sample - End --> --}}

                <li class="nav-item">
                    <div class="nav-link active cursor-pointer p-0" role="button">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tab pr-0"> <!-- Activate your tab here -->
                                <span>Tab 1</span>
                            </button>
                            <div class="btn-group dropdown" role="group">
                                <button type="button" class="btn btn-tab dropdown-toggle dropdown-toggle-split position-static pl-2" data-toggle="dropdown">
                                    <span class="sr-only">Tab Options</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <div class="nav-link cursor-pointer p-0" role="button">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tab pr-0"> <!-- Activate your tab on-click here -->
                                <span>Tab 2</span>
                            </button>
                            <div class="btn-group dropdown" role="group">
                                <button type="button" class="btn btn-tab dropdown-toggle dropdown-toggle-split position-static pl-2" data-toggle="dropdown">
                                    <span class="sr-only">Tab Options</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Create Tab Button - Start -->
                <li class="nav-item">
                    <div class="nav-link cursor-pointer p-0" role="button">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tab pr-0"> <!-- Bind Create new tab on-click here -->
                                <span>New</span>
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-tab position-static pl-2">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Create Tab Button - End -->

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="project-sheet-boards">

                <div class="tab-pane fade show active" role="tabpanel">
                    <span>Panel 1</span>
                </div>

                {{-- <div class="tab-pane fade" role="tabpanel">
                    <span>Panel 2</span>
                </div> --}}

                {{-- <!-- Vue binding sample - Start -->
                <template v-for="tab in tabs">
                    <div class="tab-pane fade" :class="{ 'show active paste-in-up': tab.active }" role="tabpanel">
                        <span>@{{ tab.label }}</span>
                    </div>
                </template>
                <!-- Vue binding sample - End --> --}}

            </div>
        </div>
    </div>
    <!-- Project Sheet Tab - End -->

@endsection