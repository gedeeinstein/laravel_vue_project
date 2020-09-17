<div class="row mx-n3 mb-4">
    <div class="px-3 col-md-6">

        <!-- Sheet Name - Start -->
        @include("{$include->sheet}.fields.sheet.name")
        <!-- Sheet Name - End -->

        <!-- Sheet Static Created Date & Creator - Start -->
        @include("{$include->sheet}.fields.sheet.static")
        <!-- Sheet Static Created Date & Creator - End -->

    </div>
    <div class="px-3 col-md-6">

        <!-- Sheet Memo - Start -->
        @include("{$include->sheet}.fields.sheet.memo")
        <!-- Sheet Memo - End -->

    </div>
</div>