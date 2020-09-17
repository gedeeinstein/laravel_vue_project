<ul class="list-group mb-3 mb-md-4">

    <!-- Group heading - Start -->
    @component("{$purchasing->components}.heading") @endcomponent
    <!-- Group heading - End -->

    <!-- Purchase Price - Start -->
    @include("{$purchasing->purchase}.price")
    <!-- Purchase Price - End -->

    <!-- Purchase Broker Commision - Start -->
    @include("{$purchasing->purchase}.commision")
    <!-- Purchase Broker Commision - End -->

</ul>